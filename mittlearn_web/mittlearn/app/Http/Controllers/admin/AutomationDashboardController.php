<?php

namespace App\Http\Controllers\admin;


use App\Http\Controllers\Controller;
use App\Models\Schools;
use App\Models\User;
use App\Models\CrmSchoolAddon;
use App\Models\SchoolAssignedDigitalContent;
use App\Models\UserAdditionalDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AutomationDashboardController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', null);
        $perPage = $request->get('per_page', 10);

        // ── Stat Counts ──────────────────────────────────────────────────────
        $baseQuery = fn() => Schools::whereHas('user', fn($q) => $q->whereNotNull('soid')->where('soid', '!=', ''))
            ->whereHas('assignedDigitalContents');

        $stats = [
            'total'         => (clone $baseQuery())->count(),
            'activated'     => (clone $baseQuery())->whereHas('user', fn($q) => $q->where('status', 1))->count(),
            'not_activated' => (clone $baseQuery())->whereHas('user', fn($q) => $q->where('status', '!=', 1))->count(),
            'logged_once'   => (clone $baseQuery())->whereHas('user', fn($q) => $q->whereHas('loginLogs'))->count(),
            'not_logged'    => (clone $baseQuery())->whereHas('user', fn($q) => $q->whereDoesntHave('loginLogs'))->count(),
            'addon_alloted' => (clone $baseQuery())->whereHas('user', function ($q) {
                $q->whereHas('crmAddons', fn($a) => $a->where(fn($b) => $b->where('mittleance', '>', 0)->orWhere('techlite', '>', 0)));
            })->count(),
        ];

        // ── School List based on filter ──────────────────────────────────────
        $schools = collect();

        if ($filter) {
            $query = Schools::with([
                'user',
                'user_additional_details',
                'assignedDigitalContents.series',
            ])
                ->whereHas('user', fn($q) => $q->whereNotNull('soid')->where('soid', '!=', ''))
                ->whereHas('assignedDigitalContents');

            match ($filter) {
                'total'         => null,
                'activated'     => $query->whereHas('user', fn($q) => $q->where('status', 1)),
                'not_activated' => $query->whereHas('user', fn($q) => $q->where('status', '!=', 1)),
                'logged_once'   => $query->whereHas('user', fn($q) => $q->whereHas('loginLogs')),
                'not_logged'    => $query->whereHas('user', fn($q) => $q->whereDoesntHave('loginLogs')),
                'addon_alloted' => $query->whereHas('user', function ($q) {
                    $q->whereHas('crmAddons', fn($a) => $a->where(fn($b) => $b->where('mittleance', '>', 0)->orWhere('techlite', '>', 0)));
                }),
                default => null,
            };

            // ── Search by School Name or SOID ────────────────────────────────
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhereHas('user', fn($u) => $u->where('soid', 'like', "%{$search}%"));
                });
            }

            $schools = $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();
        }

        return view('admin.dashboard.automation-dashboard', compact('stats', 'schools', 'filter'));
    }

    public function export(Request $request)
    {
        $filter = $request->get('filter', 'total');

        $query = Schools::with([
            'user',
            'user_additional_details',
            'assignedDigitalContents.series',
        ])
            ->whereHas('user', fn($q) => $q->whereNotNull('soid')->where('soid', '!=', ''))
            ->whereHas('assignedDigitalContents');

        match ($filter) {
            'activated'     => $query->whereHas('user', fn($q) => $q->where('status', 1)),
            'not_activated' => $query->whereHas('user', fn($q) => $q->where('status', '!=', 1)),
            'logged_once'   => $query->whereHas('user', fn($q) => $q->whereHas('loginLogs')),
            'not_logged'    => $query->whereHas('user', fn($q) => $q->whereDoesntHave('loginLogs')),
            'addon_alloted' => $query->whereHas('user', function ($q) {
                $q->whereHas('crmAddons', fn($a) => $a->where(fn($b) => $b->where('mittleance', '>', 0)->orWhere('techlite', '>', 0)));
            }),
            default => null,
        };

        $schools = $query->orderBy('created_at', 'desc')->get();

        // Build Excel using PhpSpreadsheet (no package needed)
        $filename = 'crm_schools_' . $filter . '_' . now()->format('Ymd_His') . '.xlsx';
        $filepath = storage_path('app/public/exports/' . $filename);

        if (!file_exists(storage_path('app/public/exports'))) {
            mkdir(storage_path('app/public/exports'), 0755, true);
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header row
        $headers = [
            'S.No',
            'SOID',
            'School Name',
            'Email',
            'Mobile No',
            'State',
            'City',
            'Decision Maker',
            'DM Mobile',
            'Strength',
            'Grade',
            'RM Name',
            'RM Mobile',
            'Series Assigned',
            'LMS Status',
            'Last Login',
            'Mittlens',
            'Techlite',
            'Onboarded At',
        ];

        foreach ($headers as $col => $header) {
            $cell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1) . '1';
            $sheet->setCellValue($cell, $header);
            $sheet->getStyle($cell)->getFont()->setBold(true);
            $sheet->getStyle($cell)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FF4472C4');
            $sheet->getStyle($cell)->getFont()->getColor()->setARGB('FFFFFFFF');
        }

        // Data rows
        foreach ($schools as $i => $school) {
            $row   = $i + 2;
            $user  = $school->user;
            $uad   = $school->user_additional_details;
            $rmId  = $uad->assign_to ?? null;
            $rm    = $rmId ? User::find($rmId) : null;
            $addon = $user ? CrmSchoolAddon::where('user_id', $user->id)->where('series_name', 'think trail')->first() : null;

            $series = SchoolAssignedDigitalContent::where('school_id', $school->user_id)
                ->join('book_series', 'book_series.id', '=', 'school_assigned_digital_contents.series_id')
                ->distinct()->pluck('book_series.name')->implode(', ');
            $lastLogin = $user ? \App\Models\UserLoginLog::where('user_id', $user->id)->latest('login_at')->value('login_at') : null;

            $rowData = [
                $i + 1,
                $user->soid ?? '',
                $school->name ?? '',
                $user->email ?? '',
                $user->mobile_no ?? '',
                $uad->state ?? '',
                $uad->city ?? '',
                $uad->decision_maker ?? '',
                $uad->decision_maker_mobile_no ?? '',
                $uad->strength ?? '',
                $uad->grade ?? '',
                $rm->name ?? '',
                $rm->mobile_no ?? '',
                $series,
                ($user && $user->status == 1) ? 'Approved in LMS' : 'Not Approved',
                // then in rowData:
                $lastLogin ? \Carbon\Carbon::parse($lastLogin)->format('d-m-Y') : 'Never',
                $addon->mittleance ?? 0,
                $addon->techlite ?? 0,
                $school->created_at ? $school->created_at->format('d-m-Y') : '',
            ];

            foreach ($rowData as $col => $value) {
                $cellRef = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1) . $row;
                $sheet->setCellValue($cellRef, $value);
            }
        }

        // Auto width
        foreach (range(1, count($headers)) as $col) {
            $sheet->getColumnDimensionByColumn($col)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($filepath);

        return response()->download($filepath, $filename)->deleteFileAfterSend(true);
    }
    public function automationLog(Request $request)
    {
        $query = DB::table('crm_api_incoming_logs');

        // Search by SOID
        if ($request->filled('soid')) {
            $query->where('soid', 'like', '%' . $request->soid . '%');
        }

        // Pagination (default 10)
        $perPage = $request->get('per_page', 10);

        $logs = $query->orderBy('id', 'desc')->paginate($perPage);

        return view('admin.dashboard.automation-log', compact('logs'));
    }
}
