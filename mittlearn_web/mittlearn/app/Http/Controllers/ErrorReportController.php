<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ErrorReport;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ErrorReportController extends Controller
{
    public function index(Request $request)
    {
        return view('errors.report-error');
    }
    public function store(Request $request)
    {
        $request->validate([
            'url' => 'required|url|max:255',
            'user_note' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();

        $errorTicket = ErrorReport::create([
            'url'         => $request->url,
            'user_note'   => $request->user_note,
            'user_agent'  => $request->header('User-Agent'),
            'user_id'     => $user ? $user->id : null,
            'ip_address'  => $request->ip(),
        ]);

        // Send email after saving
        $this->sendErrorReportedMail($errorTicket);

        return redirect()->route('/')->with('success', 'Error reported successfully! Our team will check it soon.');
    }

    protected function sendErrorReportedMail($ticket)
    {
        try {
            $recipients = ["krishan.gopal@qdegrees.com"];

            // Get user name if available
            $userName = null;
            if ($ticket->user_id) {
                $user = User::find($ticket->user_id);
                $userName = $user ? $user->name : 'Unknown User';
            } else {
                $userName = 'Guest User';
            }
            // Get user note if available
            $userNote = 'NOT ADDED';
            if (!empty($ticket->user_note)) {
                $userNote = $ticket->user_note;
            }

            // Prepare email data
            $templateId = 29; // your mail template ID
            $data = [
                'TICKET_ID' => $ticket->id,
                'MODULE'    => $ticket->url,
                'ISSUE'     => $userNote,
                'NAME'      => $userName,
            ];

            sendEmail($templateId, $recipients, $data);
        } catch (\Exception $e) {
            \Log::error("error reported email send failed: " . $e->getMessage());
        }
    }
}
