<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use App\Models\Ticket;
use App\Models\TicketTimeLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class TicketController extends Controller
{

    public function index(Request $request)
    {
        $user           = Auth::user();
        $perPageRecords = Session::get('per_page_records', config('constants.PAGINATION.default'));

        $query = Ticket::with(['creator', 'assignee', 'watchers', 'timeLogs'])->orderBy('updated_at', 'desc');

        // Apply role restriction
        if (! in_array(getUserRoles(), ['super_admin', 'qd_developer'])) {
            $query->where(function ($q) use ($user) {
                $q->where('created_by', $user->id)
                    ->orWhere('assigned_to', $user->id)
                    ->orWhereHas('watchers', fn($w) => $w->where('user_id', $user->id));
            });
        }

        // Filters
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('issue', 'like', "%{$request->search}%")
                    ->orWhere('id', $request->search);
            });
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('assignee')) {
            $query->where('assigned_to', $request->assignee);
        }

        if ($request->filled('created_from')) {
            $query->whereDate('created_at', '>=', $request->created_from);
        }

        if ($request->filled('created_to')) {
            $query->whereDate('created_at', '<=', $request->created_to);
        }

        $tickets = $query->latest()->paginate($perPageRecords);

        foreach ($tickets as $ticket) {
            $ticket->total_time_logs = $ticket->timeLogs->sum('hours');
        }

        $totalTimeFromLogs = $query->get()->sum(fn($t) => $t->timeLogs->sum('hours'));

        // --- Monthly Summary Based on Logged Date ---
        $currentMonth  = Carbon::now()->month;
        $previousMonth = Carbon::now()->subMonth()->month;

        // Define allotted hours per month (as per AMC)
        $allottedHours = 50;

        // Get total used hours from ticket_time_logs table by logged_date
        $previousMonthUsed = TicketTimeLog::whereMonth('logged_date', $previousMonth)->sum('hours');
        $currentMonthUsed  = TicketTimeLog::whereMonth('logged_date', $currentMonth)->sum('hours');

        // Carry forward logic
        $previousMonthUnused   = max($allottedHours - $previousMonthUsed, 0);
        $currentMonthAvailable = $allottedHours + $previousMonthUnused;

        // Remaining hours for current month
        $currentMonthRemaining = max($currentMonthAvailable - $currentMonthUsed, 0);

        $monthlyData = [
            'previous' => [
                'allotted' => $allottedHours,
                'used'     => $previousMonthUsed,
                'unused'   => $previousMonthUnused,
            ],
            'current'  => [
                'allotted'      => $allottedHours,
                'used'          => $currentMonthUsed,
                'carry_forward' => $previousMonthUnused,
                'available'     => $currentMonthAvailable,
                'remaining'     => $currentMonthRemaining,
            ],
        ];
        return view('admin.tickets.index', compact('tickets', 'totalTimeFromLogs', 'monthlyData'));
    }

    public function create()
    {
        $data = [
            'priority'     => ['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'],
            'qdDevelopers' => User::whereHas('userRole', fn($q) => $q->where('role_slug', 'qd_developer'))->pluck('name', 'id'),
            'users'        => User::where('id', '!=', Auth::id())->pluck('name', 'id'),
        ];
        return view('admin.tickets.add', $data);
    }
    public function store(Request $request)
    {
        $rules = [
            'module'          => 'required|max:255',
            'issue'           => 'required',
            'logged_by_user'  => 'required|max:255',
            'status'          => 'required|in:open,in_progress,resolved,closed',
            'screenshot_path' => 'nullable',
            'watchers'        => 'nullable|array',
            'watchers.*'      => 'exists:users,id',
            'work_duration'   => 'sometimes|numeric|min:0.1|max:24',
        ];
        $rules['priority'] = $request->id ? 'sometimes|in:low,medium,high,critical' : 'required|in:low,medium,high,critical';
        $request->validate($rules);
        $ticketData = $request->only(['date_created', 'module', 'issue', 'logged_by_user', 'priority', 'status', 'assigned_to', 'remarks_qd', 'further_remarks']);
        if (! $request->id) {
            $ticketData['created_by'] = Auth::id();

            // Get last ticket_id
            $lastTicket = Ticket::orderBy('id', 'desc')->first();

            if ($lastTicket && $lastTicket->ticket_id) {
                // Extract number from ticket#XXX
                preg_match('/\d+/', $lastTicket->ticket_id, $matches);
                $nextNumber = isset($matches[0]) ? ((int) $matches[0] + 1) : 101;
            } else {
                $nextNumber = 101;
            }

            $ticketData['ticket_id'] = 'ticket#' . $nextNumber;
        }
        if (! $request->has('priority')) {
            unset($ticketData['priority']);
        }
        // if (!in_array(getUserRoles(), ['super_admin', 'qd_developer'])) {
        //     unset($ticketData['assigned_to']);
        // }

        if ($request->hasFile('screenshot_path')) {
            $filenames = [];

            // If editing, merge old + new files (optional)
            if ($request->id) {
                $existingTicket = Ticket::find($request->id);
                if ($existingTicket && $existingTicket->screenshot_path) {
                    $filenames = explode(',', $existingTicket->screenshot_path); // keep old files
                }
            }

            foreach ($request->file('screenshot_path') as $file) {
                $extension = $file->getClientOriginalExtension();
                $filename  = time() . '_' . uniqid() . '.' . $extension;
                Storage::disk('public')->put('uploads/tickets/' . $filename, file_get_contents($file));
                $filenames[] = $filename;
            }

            // Save comma separated
            $ticketData['screenshot_path'] = implode(',', $filenames);
        }
        if ($request->id) {
            $ticket = Ticket::findOrFail($request->id);
            $ticket->update($ticketData);
        } else {

            $ticket = Ticket::create($ticketData);
            // Send mail ONLY when new ticket created
            $this->sendTicketCreatedMail($ticket);
        }

        if (in_array(getUserRoles(), ['super_admin', 'qd_developer'])) {
            if ($request->watchers) {
                $ticket->watchers()->sync($request->watchers);
            }
        }

        return redirect()->route('tickets.index')->with('success', $request->id ? 'Ticket updated!' : 'Ticket created!');
    }
    protected function sendTicketCreatedMail($ticket)
    {
        try {
            $recipients = [];

            // 1. Created by user
            // if ($ticket->created_by) {
            //     $creator = User::find($ticket->created_by);
            //     if ($creator) {
            //         $recipients[] = $creator->email;
            //     }
            // }

            // 2. Assigned user
            if ($ticket->assigned_to) {
                $assignee = User::find($ticket->assigned_to);
                if ($assignee) {
                    $recipients[] = $assignee->email;
                }
            }

                              // prepare template data
            $templateId = 28; // your template ID
            $data       = [
                'TICKET_ID' => $ticket->id,
                'MODULE'    => $ticket->module,
                'ISSUE'     => strip_tags($ticket->issue),
                'STATUS'    => ucfirst($ticket->status),
                'PRIORITY'  => ucfirst($ticket->priority),
                'LINK'      => 'https://mittlearn.com/admin/tickets',
            ];

            $recipients = [
                "support.pd@qdegrees.com",
                "krishan.gopal@qdegrees.com",
                "abhishek.kumar@qdegrees.com",
                "itconsultant@mittsure.in",
                "ranjita.raj@mittsure.in",
            ];

            sendEmail($templateId, $recipients, $data, true); // true means use BCC mode
        } catch (\Exception $e) {
            \Log::error("Ticket email failed: " . $e->getMessage());
        }
    }

    public function show(Ticket $ticket)
    {
        $users = User::where('id', '!=', auth()->id())->orderBy('name')->get();
        $ticket->load(['creator', 'assignee', 'comments.user', 'watchers.user', 'attachments.user', 'timeLogs.user']);
        return view('admin.tickets.show', compact('ticket', 'users'));
    }
    public function edit(Ticket $ticket)
    {
        $qdDevelopers = User::whereHas('userRole', fn($q) => $q->where('role_slug', 'qd_developer'))
            ->pluck('name', 'id');
        if ($ticket->assigned_to && ! $qdDevelopers->has($ticket->assigned_to)) {
            $qdDevelopers->put($ticket->assigned_to, $ticket->assignee->name ?? ('User #' . $ticket->assigned_to));
        }
        $data = [
            'data'         => $ticket,
            'priority'     => ['low' => 'Low', 'medium' => 'Medium', 'high' => 'High', 'critical' => 'Critical'],
            'qdDevelopers' => $qdDevelopers->toArray(),
            'users'        => User::where('id', '!=', Auth::id())->pluck('name', 'id'),
        ];
        return view('admin.tickets.add', $data);
    }
    public function update(Request $request, Ticket $ticket)
    {
        $request->merge(['id' => $ticket->id]);
        return $this->store($request);
    }
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('tickets.index')->with('success', 'Ticket deleted!');
    }
    public function addComment(Request $request, Ticket $ticket)
    {
        $request->validate(['comment' => 'required', 'is_internal' => 'boolean']);
        $ticket->comments()->create([
            'user_id'     => Auth::id(),
            'comment'     => $request->comment,
            'is_internal' => $request->boolean('is_internal') && in_array(getUserRoles(), ['super_admin', 'qd_developer']),
        ]);
        return back()->with('success', 'Comment added!');
    }
    public function addWatcher(Request $request, Ticket $ticket)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);
        if (! in_array(getUserRoles(), ['admin', 'qd_developer', 'super_admin'])) {
            abort(403);
        }
        $ticket->watchers()->firstOrCreate(['user_id' => $request->user_id]);
        return back()->with('success', 'Watcher added!');
    }
    public function uploadAttachment(Request $request, Ticket $ticket)
    {
        $request->validate(['file' => 'required|file|max:10240']);
        $file         = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $filename     = time() . '_' . $originalName;
        try {
            $relativeDir = 'uploads/tickets/attachments';
            Storage::disk('public')->put($relativeDir . '/' . $filename, file_get_contents($file));
            /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
            $disk       = Storage::disk('public');
            $storedPath = $relativeDir . '/' . $filename;
            $mime       = null;
            $size       = null;
            try {
                $mime = $disk->mimeType($storedPath);
            } catch (\Throwable $e) {
                $mime = $file->getClientMimeType();
            }
            try {
                $size = $disk->size($storedPath);
            } catch (\Throwable $e) {
                $size = $file->getSize();
            }
            $ticket->attachments()->create([
                'user_id'       => Auth::id(),
                'filename'      => $filename,
                'original_name' => $originalName,
                'mime_type'     => $mime,
                'size'          => $size,
            ]);
        } catch (\Throwable $e) {
            return back()->withErrors(['file' => 'Failed to upload attachment: ' . $e->getMessage()]);
        }
        return back()->with('success', 'File uploaded!');
    }
    public function logTime(Request $request, Ticket $ticket)
    {
        $request->validate([
            'hours'       => 'required|numeric|min:0.1|max:24',
            'description' => 'nullable',
            'logged_date' => 'required|date',
        ]);
        $ticket->timeLogs()->create([
            'user_id'     => Auth::id(),
            'hours'       => $request->hours,
            'description' => $request->description,
            'logged_date' => $request->logged_date,
        ]);
        return back()->with('success', 'Time logged!');
    }
    public function updateStatus(Request $request, Ticket $ticket)
    {
        $rules = [
            'status'              => 'required|in:open,in_progress,resolved,closed',
            'work_duration'       => 'nullable|numeric|min:0.1|max:24',
            'closure_hours'       => 'nullable|numeric|min:0.1|max:24',
            'closure_description' => 'nullable',
        ];
        if (getUserRoles() === 'qd_developer') {
            $rules['work_duration'] = 'required_without:closure_hours|numeric|min:0.1|max:24';
            $rules['closure_hours'] = 'required_without:work_duration|numeric|min:0.1|max:24';
        }
        $request->validate($rules);

        $ticket->update(['status' => $request->status]);

        $hours = $request->input('work_duration', $request->input('closure_hours'));
        if ($hours) {
            $ticket->timeLogs()->create([
                'user_id'     => Auth::id(),
                'hours'       => $hours,
                'description' => $request->closure_description
                    ? strip_tags($request->closure_description)
                    : ('Time logged on status update to ' . str_replace('_', ' ', $request->status)),
                'logged_date' => $request->logged_date ?? now()->toDateString(),
            ]);
        }

        $commentParts = ['Status changed to ' . str_replace('_', ' ', $request->status)];
        if ($hours) {
            $commentParts[] = number_format((float) $hours, 2) . ' hrs';
        }
        if ($request->filled('closure_description')) {
            $commentParts[] = strip_tags($request->closure_description);
        }
        $ticket->comments()->create([
            'user_id'     => Auth::id(),
            'comment'     => implode(' — ', $commentParts),
            'is_internal' => false,
        ]);

        // Send closure mail when ticket is closed or resolved
        if (in_array($request->status, ['closed', 'resolved'])) {
            $this->sendTicketClosedMail($ticket, $request->status, $request->closure_description);
        }

        return back()->with('success', 'Status updated!');
    }
    protected function sendTicketClosedMail($ticket, string $status, ?string $closureDescription = null)
    {
        try {
            $settings = getSettings();

            // Guard: if mail settings are missing, bail out
            if (
                empty($settings['mail_host']) ||
                empty($settings['mail_port']) ||
                empty($settings['mail_user_name']) ||
                empty($settings['mail_password']) ||
                empty($settings['mail_encryption']) ||
                empty($settings['from_mail_address'])
            ) {
                \Log::warning("Ticket closure mail skipped: mail settings not configured.");
                return false;
            }

            $fromName = ! empty($settings['from_mail_name'])
                ? $settings['from_mail_name']
                : 'Mittlearn | Mittsure Technologies';

            // Apply dynamic SMTP config — same as sendEmail() helper
            config([
                'mail.mailers.smtp.transport'  => 'smtp',
                'mail.mailers.smtp.host'       => $settings['mail_host'],
                'mail.mailers.smtp.port'       => $settings['mail_port'],
                'mail.mailers.smtp.encryption' => $settings['mail_encryption'],
                'mail.mailers.smtp.username'   => $settings['mail_user_name'],
                'mail.mailers.smtp.password'   => $settings['mail_password'],
                'mail.from.address'            => $settings['from_mail_address'],
                'mail.from.name'               => $fromName,
            ]);

            $statusLabel     = ucfirst(str_replace('_', ' ', $status));
            $descriptionText = $closureDescription ? strip_tags($closureDescription) : 'N/A';
            $ticketLink      = 'https://mittlearn.com/admin/tickets';
            $updatedAt       = now()->format('d M Y, h:i A');
            $statusColor     = $status === 'closed' ? '#dc3545' : '#198754';
            $ticketId        = ucfirst($ticket->ticket_id);

            $subject = "Mittlearn {$ticketId} — {$statusLabel}";

            $body = "
        <div style='font-family: Arial, sans-serif; max-width: 620px; margin: 0 auto; border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden;'>

            <div style='padding: 24px 30px; background-color: #ffffff;'>
                <p style='margin: 0 0 16px; color: #333; font-size: 14px;'>
                    Hello Team,

                </p>
                <p style='margin: 0 0 16px; color: #333; font-size: 14px;'>
                    The following ticket has been marked as <strong>{$statusLabel}</strong>.
                    Please review the details.
                </p>

                <table style='width: 100%; border-collapse: collapse; font-size: 14px;'>
                    <tr style='background-color: #f8f9fa;'>
                        <td style='padding: 10px 14px; font-weight: bold; color: #555; width: 35%; border: 1px solid #dee2e6;'>Ticket ID</td>
                        <td style='padding: 10px 14px; color: #333; border: 1px solid #dee2e6;'>{$ticketId}</td>
                    </tr>
                    <tr>
                        <td style='padding: 10px 14px; font-weight: bold; color: #555; border: 1px solid #dee2e6;'>Module / Section</td>
                        <td style='padding: 10px 14px; color: #333; border: 1px solid #dee2e6;'>" . strip_tags($ticket->module) . "</td>
                    </tr>
                    <tr style='background-color: #f8f9fa;'>
                        <td style='padding: 10px 14px; font-weight: bold; color: #555; border: 1px solid #dee2e6;'>Status</td>
                        <td style='padding: 10px 14px; border: 1px solid #dee2e6;'>
                            <span style='
                                display: inline-block;
                                padding: 3px 12px;
                                border-radius: 12px;
                                font-size: 12px;
                                font-weight: bold;
                                background-color: {$statusColor};
                                color: #fff;
                            '>{$statusLabel}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style='padding: 10px 14px; font-weight: bold; color: #555; border: 1px solid #dee2e6;'>Work Description</td>
                        <td style='padding: 10px 14px; color: #333; border: 1px solid #dee2e6;'>{$descriptionText}</td>
                    </tr>
                    <tr style='background-color: #f8f9fa;'>
                        <td style='padding: 10px 14px; font-weight: bold; color: #555; border: 1px solid #dee2e6;'>Closed At</td>
                        <td style='padding: 10px 14px; color: #333; border: 1px solid #dee2e6;'>{$updatedAt}</td>
                    </tr>
                </table>

                          </div>

            <div style='background-color: #f1f1f1; padding: 14px 30px; text-align: center;'>
                <p style='margin: 0; font-size: 12px; color: #888;'>
                    This is an automated notification from the MittLearn Ticket System. Please do not reply to this email.
                </p>
            </div>

        </div>";

            $recipients = [
                "support.pd@qdegrees.com",
                "krishan.gopal@qdegrees.com",
                "abhishek.kumar@qdegrees.com",
                "itconsultant@mittsure.in",
                "ranjita.raj@mittsure.in",
            ];

            // Use the same emails.template view + BCC pattern as sendEmail() helper
            \Mail::send(
                'emails.template',
                ['messageBody' => $body, 'subject' => $subject],
                function ($message) use ($recipients, $subject, $fromName, $settings) {
                    $message->to($settings['from_mail_address']) // "to" is the sender (BCC pattern)
                        ->bcc($recipients)
                        ->subject($subject)
                        ->from($settings['from_mail_address'], $fromName);
                }
            );
        } catch (\Exception $e) {
            \Log::error("Ticket closure email failed: " . $e->getMessage());
        }
    }
    public function reopen(Request $request, Ticket $ticket)
    {
        if (! in_array(getUserRoles(), ['admin', 'super_admin'])) {
            abort(403);
        }
        if (! in_array($ticket->status, ['resolved', 'closed'])) {
            return back()->with('error', 'Only resolved/closed tickets can be reopened.');
        }
        $data = $request->validate([
            'reason'        => 'required|min:5',
            'target_status' => 'nullable|in:in_progress,open',
        ]);
        $target = $data['target_status'] ?? 'in_progress';
        $ticket->update(['status' => $target]);
        $ticket->comments()->create([
            'user_id'     => Auth::id(),
            'comment'     => 'Reopened by ' . (Auth::user()->name ?? 'User') . ': ' . $data['reason'] . ' (to ' . str_replace('_', ' ', $target) . ')',
            'is_internal' => false,
        ]);
        return back()->with('success', 'Ticket reopened to ' . str_replace('_', ' ', $target) . '.');
    }
}
