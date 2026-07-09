<?php

namespace App\Http\Controllers\schoolPortal;

use App\Http\Controllers\Controller;
use TCPDF;
use App\Models\Board;
use App\Models\BookSeries;
use App\Models\Classes;
use App\Models\Course;
use App\Models\CourseChapter;
use App\Models\Medium;
use App\Models\QuestionBank;
use App\Models\SchoolAssignedClass;
use App\Models\SchoolAssignedDigitalContent;
use App\Models\Subject;
use App\Models\TestPaper;
use App\Models\TestPaperQuestion;
use App\Models\TestPaperResult;
use App\Models\TestParticipent;
use App\Models\User;
use App\Models\UserAdditionalDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Html as PhpWordHtml;
use Mpdf\Mpdf;

class SpTestPaperGenController extends Controller
{
    public $data = [];

    public function index(Request $request)
    {
        try {
            $role     = getUserRoles();
            $parentId = Auth::id();

            $query = TestPaper::with(['Class', 'Subject', 'testParticipent']);

            if ($role === 'school_teacher') {
                $parentId   = Auth::user()->userAdditionalDetail->school_id;
                $classIds   = getTeacherClasses(Auth::id(), $parentId);
                $subjectIds = getTeacherSubject(Auth::id(), $parentId);

                // Fixed: Properly group conditions
                $query->where(function ($q) use ($parentId, $classIds, $subjectIds) {
                    $q->where('school_id', $parentId)
                        ->whereIn('class_id', $classIds)
                        ->whereIn('subject_id', $subjectIds);
                })->orWhere('created_by', Auth::id());
            } else {
                $userBoard  = getUserBoard();
                $userMedium = getUserMedium();
                $schoolAssignedClasses = SchoolAssignedClass::where('school_id', $parentId)
                    ->pluck('class_id')
                    ->toArray();

                // Fixed: Simplified and properly grouped conditions
                $query->where(function ($q) use ($parentId, $userBoard, $userMedium, $schoolAssignedClasses) {
                    $q->where('school_id', $parentId)
                        ->orWhere(function ($q2) use ($userBoard, $userMedium, $schoolAssignedClasses) {
                            $q2->whereNull('school_id')
                                ->whereIn('board_id', [$userBoard, 0])
                                ->whereIn('medium_id', [$userMedium, 0])
                                ->whereIn('class_id', $schoolAssignedClasses);
                        })
                        ->orWhere('created_by', Auth::id());
                });
            }

            // Apply filters - these should work independently
            if ($request->filled('class')) {
                $query->where('class_id', $request->input('class'));
            }

            if ($request->filled('subject')) {
                $query->where('subject_id', $request->input('subject'));
            }

            $this->data['testPapers'] = $query->orderBy('created_at', 'DESC')
                ->paginate(config('constants.PAGINATION.default'));

            $this->data['classes']         = getUserSchoolClasses(Auth::id());
            $this->data['subjects']        = Subject::where('is_active', 1)->pluck('name', 'id');
            $this->data['testParticipent'] = TestParticipent::where('school_id', $parentId)
                ->pluck('test_id')
                ->toArray();
            $this->data['testResultId']    = TestPaperResult::where('school_id', $parentId)
                ->pluck('test_id')
                ->toArray();

            return view('schoolPortal.tpg.test-paper', $this->data);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'error' => config('constants.FLASH_TRY_CATCH'),
                'exception' => $e->getMessage(),
            ]);
        }
    }

    public function testPapersEdit($id)
    {
        try {
            $this->data['data'] = TestPaper::find($id);

            $this->data['class'] = Classes::where('is_active', '1')->pluck('name', 'id')->toArray();
            $this->data['subject'] = Subject::where('is_active', '1')->pluck('name', 'id')->toArray();
            $role     = getUserRoles();
            $parentId = Auth::id();
            if ($role === 'school_teacher') {
                $parentId   = Auth::user()->userAdditionalDetail->school_id;
            }
            $this->data['testParticipent'] = TestParticipent::where('school_id', $parentId)->pluck('test_id')->toArray();
            return view('schoolPortal.tpg.test-paper-add', $this->data);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'error' => config('constants.FLASH_TRY_CATCH'),
                'exception' => $e->getMessage(),
            ]);
        }
    }
    public function download($paperId, $user, $format)
    {
        if ($format === 'pdf') {
            return $this->generatePDF($paperId, $user);
        }

        if ($format === 'word') {
            return $this->generateWORD($paperId, $user);
        }

        abort(404, "Invalid format");
    }

    public function generatePDF($paperId, $user)
    {
        // Increase all PHP limits for large PDFs
        ini_set('pcre.backtrack_limit', '100000000');
        ini_set('pcre.recursion_limit', '100000000');
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', '600');
        ini_set('max_input_time', '600');
        ini_set('post_max_size', '1024M');
        ini_set('upload_max_filesize', '1024M');

        $role     = getUserRoles();
        $parentId = Auth::id();

        if ($role === 'school_teacher') {
            $parentId = Auth::user()->userAdditionalDetail->school_id;
        }

        $paper = TestPaper::with(['questions'])
            ->where('id', $paperId)
            // ->where('school_id', $parentId)
            ->firstOrFail();
            // dd($paper);

        if (!$paper) {
            return redirect()->back()->with([
                'error' => 'Test Paper not available to print',
            ]);
        }

        $totalMarks = $paper->questions->sum('marks');
        $className = Classes::where('id', $paper->class_id)->value('name');
        $subjectName = Subject::where('id', $paper->subject_id)->value('name');
        $schoolName = User::where('id', $parentId)->value('name');

        $groupedQuestions = $paper->questions->groupBy('question_type');

        $data = [
            'paper' => $paper,
            'questions' => $groupedQuestions,
            'totalMarks' => $totalMarks,
            'subjectName' => $subjectName,
            'schoolName' => $schoolName,
            'className' => $className,
            'date' => now()->format('F j, Y'),
            'durationInHours' => number_format($paper->duration / 60, 1),
            'userType' => $user
        ];

        // Render the view to HTML
        $html = view('schoolPortal.tpg.pdf-test-paper', $data)->render();

        // Create mPDF instance
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A3',  // Keep A3 as in your blade file
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 15,
            'margin_bottom' => 15,
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
        ]);

        $mpdf->WriteHTML($html);
        return $mpdf->Output('test-paper.pdf', 'I');
    }

    public function generateWORD($paperId, $user)
    {
        // Increase all PHP limits for large documents
        ini_set('pcre.backtrack_limit', '100000000');
        ini_set('pcre.recursion_limit', '100000000');
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', '600');
        ini_set('max_input_time', '600');
        ini_set('post_max_size', '1024M');
        ini_set('upload_max_filesize', '1024M');

        \PhpOffice\PhpWord\Settings::setTempDir(storage_path('app/phpword-temp'));

        $role = getUserRoles();
        $parentId = Auth::id();

        if ($role === 'school_teacher') {
            $parentId = Auth::user()->userAdditionalDetail->school_id;
        }

        $paper = TestPaper::with(['questions.options'])->where('id', $paperId)->firstOrFail();

        $totalMarks = $paper->questions->sum('marks');
        $className = Classes::where('id', $paper->class_id)->value('name');
        $subjectName = Subject::where('id', $paper->subject_id)->value('name');
        $schoolName = User::where('id', $parentId)->value('name');

        // Logo URL (public storage)
        $logoUrl = null;
        if (!empty($paper->logo) && Storage::disk('public')->exists($paper->logo)) {
            $logoUrl = asset('storage/' . str_replace('\\', '/', $paper->logo));
        }

        // CLEAN THE DATA BEFORE PASSING TO VIEW
        $cleanedQuestions = $this->cleanQuestionsData($paper->questions);

        // Group questions the same way as PDF
        $groupedQuestions = $cleanedQuestions->groupBy('question_type');

        $data = [
            'paper' => $paper,
            'questions' => $groupedQuestions,
            'totalMarks' => $totalMarks,
            'subjectName' => $subjectName,
            'schoolName' => $schoolName,
            'className' => $className,
            'date' => now()->format('F j, Y'),
            'durationInHours' => number_format($paper->duration / 60, 1),
            'userType' => $user,
            'logoUrl' => $logoUrl,
        ];

        // Render the HTML view
        $html = view('schoolPortal.tpg.word-test-paper', $data)->render();

        // Clean the HTML aggressively
        $html = $this->cleanForWord($html);
        $html = $this->clean_html_for_docx($html);

        // Final safety check - validate XML structure
        $html = $this->ensureValidXML($html);

        // Create PhpWord and section
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        // Convert HTML -> Word with error handling
        try {
            PhpWordHtml::addHtml($section, $html, false, false);
        } catch (\Exception $e) {
            \Log::error('PHPWord HTML conversion error: ' . $e->getMessage());
            \Log::error('Problematic HTML: ' . substr($html, 0, 1000)); // Log first 1000 chars

            // Try to save the problematic HTML for debugging
            Storage::disk('local')->put('word-errors/error-' . $paperId . '.html', $html);

            throw new \Exception('Error generating Word document. Please check question content for invalid HTML or special characters.');
        }

        // Save to temp file and send download
        $fileName = "test-paper-{$paperId}-{$user}.docx";
        $tempFile = tempnam(sys_get_temp_dir(), 'word');

        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

    /**
     * Clean questions data before rendering
     * This prevents malformed HTML from reaching PHPWord
     */
    private function cleanQuestionsData($questions)
    {
        foreach ($questions as $question) {
            // Clean question text
            $question->question = $this->sanitizeHTML($question->question ?? '');
            $question->answer_text = $this->sanitizeHTML($question->answer_text ?? '');

            // Clean additional data (for passages)
            if (!empty($question->additional_data)) {
                $data = json_decode($question->additional_data, true);
                if (is_array($data)) {
                    array_walk_recursive($data, function (&$value) {
                        if (is_string($value)) {
                            $value = $this->sanitizeHTML($value);
                        }
                    });
                    $question->additional_data = json_encode($data);
                }
            }

            // Clean options
            if ($question->options) {
                foreach ($question->options as $option) {
                    $option->option = $this->sanitizeHTML($option->option ?? '');
                    $option->option_text = $this->sanitizeHTML($option->option_text ?? '');
                    $option->left_text = $this->sanitizeHTML($option->left_text ?? '');
                    $option->right_text = $this->sanitizeHTML($option->right_text ?? '');
                }
            }
        }

        return $questions;
    }

    /**
     * Sanitize individual HTML strings
     * Removes dangerous attributes and fixes common issues
     */
    private function sanitizeHTML($html)
    {
        if (empty($html)) {
            return '';
        }

        // Decode HTML entities first
        $html = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Remove null bytes and other problematic characters
        $html = str_replace(["\0", "\x0B"], '', $html);

        // Fix smart quotes
        // $html = str_replace(['"', '"', ''', '''], ['"', '"', "'", "'"], $html);

        // Remove attributes with no values like: <img src=>
        $html = preg_replace('/\s+(\w+)=\s*>/i', '>', $html);

        // Remove attributes with unquoted values containing spaces
        $html = preg_replace('/(\w+)=([^\s\"\'>]+\s+[^\s\"\'>]+)/i', '', $html);

        // Remove style attributes (PHPWord doesn't use them anyway)
        $html = preg_replace('/\s*style\s*=\s*["\'][^"\']*["\']/i', '', $html);

        // Remove class attributes
        $html = preg_replace('/\s*class\s*=\s*["\'][^"\']*["\']/i', '', $html);

        // Remove id attributes
        $html = preg_replace('/\s*id\s*=\s*["\'][^"\']*["\']/i', '', $html);

        // Remove onclick and other event handlers
        $html = preg_replace('/\s*on\w+\s*=\s*["\'][^"\']*["\']/i', '', $html);

        // Fix self-closing tags
        $html = preg_replace('/<br\s*>/i', '<br/>', $html);
        $html = preg_replace('/<img([^>]*?)(?<!\/)>/i', '<img$1/>', $html);
        $html = preg_replace('/<hr\s*>/i', '<hr/>', $html);

        return $html;
    }

    /**
     * Clean HTML for Word document
     */
    private function cleanForWord($html)
    {
        // Remove DOCTYPE, html, head, body tags
        $html = preg_replace('/<!DOCTYPE[^>]+>/i', '', $html);
        $html = preg_replace('/<html[^>]*>/i', '', $html);
        $html = preg_replace('/<\/html>/i', '', $html);
        $html = preg_replace('/<head[^>]*>.*?<\/head>/is', '', $html);

        // Extract body content if exists
        if (preg_match('/<body[^>]*>(.*?)<\/body>/is', $html, $m)) {
            $html = $m[1];
        }

        // Remove style tags and their content
        $html = preg_replace('/<style[^>]*>.*?<\/style>/is', '', $html);

        // Remove script tags and their content
        $html = preg_replace('/<script[^>]*>.*?<\/script>/is', '', $html);

        // Remove HTML comments
        $html = preg_replace('/<!--.*?-->/s', '', $html);

        // Remove all inline styles
        $html = preg_replace('/\s*style\s*=\s*["\'][^"\']*["\']/i', '', $html);

        return $html;
    }

    /**
     * Clean HTML for DOCX - comprehensive cleanup
     */
    private function clean_html_for_docx($html)
    {
        // 1. Remove scripts and styles
        $html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html);
        $html = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $html);

        // 2. Remove problematic attributes (keep only safe ones)
        $html = preg_replace('/\s+(style|class|id|onclick|onload|onerror|data-\w+)\s*=\s*["\'][^"\']*["\']/i', '', $html);

        // 3. Fix self-closing tags - CRITICAL FIX
        // Fix <img> tags - must be self-closing
        $html = preg_replace_callback('/<img([^>]*?)(?<!\/)>/i', function ($matches) {
            $attrs = $matches[1];
            $attrs = trim($attrs);

            // Extract only src attribute if it exists
            if (preg_match('/src\s*=\s*["\']([^"\']+)["\']/i', $attrs, $srcMatch)) {
                return '<img src="' . $srcMatch[1] . '"/>';
            }

            return '<img' . ($attrs ? ' ' . $attrs : '') . '/>';
        }, $html);

        // Convert <br> to <br/>
        $html = preg_replace('/<br\s*\/?>/i', '<br/>', $html);

        // Fix <hr> tags
        $html = preg_replace('/<hr\s*\/?>/i', '<hr/>', $html);

        // 4. Fix malformed attributes
        // Remove attributes with no value: <tag attr=>
        $html = preg_replace('/(\w+)=\s*(["\'])?\s*\2(?=\s|>)/i', '', $html);

        // Remove attributes with spaces in unquoted values
        $html = preg_replace('/\s+(\w+)=([^\s"\'>]+\s[^\s"\'>]*)/i', '', $html);

        // 5. Escape ampersands that aren't part of entities
        $html = preg_replace('/&(?![a-zA-Z0-9#]+;)/', '&amp;', $html);

        // 6. Replace problematic entities with actual characters
        $entities = [
            '&nbsp;' => ' ',
            '&mdash;' => '—',
            '&ndash;' => '–',
            '&ldquo;' => '"',
            '&rdquo;' => '"',
            '&quot;' => '"',
            '&copy;' => '©',
            '&reg;' => '®',
            '&trade;' => '™',
        ];
        $html = str_replace(array_keys($entities), array_values($entities), $html);

        // 7. Remove empty paragraphs and divs
        $html = preg_replace('/<p[^>]*>\s*<\/p>/i', '', $html);
        $html = preg_replace('/<div[^>]*>\s*<\/div>/i', '', $html);

        // 8. Fix nested block elements
        $html = preg_replace('/<p([^>]*)><p([^>]*)>/i', '<p$1>', $html);
        $html = preg_replace('/<\/p><\/p>/i', '</p>', $html);

        // 9. Strip to allowed tags only
        $allowedTags = '<p><div><span><ul><ol><li><br><b><i><u><strong><em><table><tr><td><th><h1><h2><h3><h4><h5><h6><img><hr>';
        $html = strip_tags($html, $allowedTags);

        // 10. Ensure UTF-8 encoding
        $html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');

        // 11. Final cleanup - normalize whitespace
        $html = preg_replace('/\s+/', ' ', $html);
        $html = preg_replace('/>\s+</', '><', $html); // Remove spaces between tags

        // 12. Wrap in a container div
        $html = '<div>' . trim($html) . '</div>';

        return $html;
    }

    /**
     * Ensure valid XML structure as final safety check
     */
    private function ensureValidXML($html)
    {
        // Try to load as XML to catch any remaining issues
        libxml_use_internal_errors(true);

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $loaded = @$dom->loadHTML(
            '<?xml encoding="UTF-8">' . $html,
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING
        );

        if (!$loaded) {
            $errors = libxml_get_errors();
            libxml_clear_errors();

            // Log the errors
            foreach ($errors as $error) {
                \Log::warning('XML parsing warning: ' . $error->message);
            }

            // If loading failed, do aggressive cleanup
            $html = strip_tags($html, '<p><b><i><u><strong><br><table><tr><td><th><ul><ol><li>');
            $html = '<div>' . $html . '</div>';
        } else {
            // Get the cleaned HTML back
            $html = $dom->saveHTML();
            $html = preg_replace('/^<\?xml[^>]*>/', '', $html);
            $html = preg_replace('/<!DOCTYPE[^>]+>/i', '', $html);
        }

        libxml_use_internal_errors(false);

        return $html;
    }

    // made by ashmit
    // public function generatePDF($paperId, $user)
    // {
    //     $role     = getUserRoles();
    //     $parentId = Auth::id();

    //     if ($role === 'school_teacher') {
    //         $parentId = Auth::user()->userAdditionalDetail->school_id;
    //     }

    //     $paper = TestPaper::with(['questions'])
    //         ->where('id', $paperId)
    //         ->firstOrFail();

    //     $totalMarks  = $paper->questions->sum('marks');
    //     $className   = Classes::where('id', $paper->class_id)->value('name');
    //     $subjectName = Subject::where('id', $paper->subject_id)->value('name');
    //     $schoolName  = User::where('id', $parentId)->value('name');

    //     $groupedQuestions = $paper->questions->groupBy('question_type');

    //     $data = [
    //         'paper'        => $paper,
    //         'questions'    => $groupedQuestions,
    //         'totalMarks'   => $totalMarks,
    //         'subjectName'  => $subjectName,
    //         'schoolName'   => $schoolName,
    //         'className'    => $className,
    //         'date'         => now()->format('F j, Y'),
    //         'durationInHours' => number_format($paper->duration / 60, 1),
    //         'userType'     => $user
    //     ];

    //     // --- TCPDF Start ---
    //     $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

    //     $pdf->SetCreator($schoolName);
    //     $pdf->SetAuthor($schoolName);
    //     $pdf->SetTitle($paper->test_term);
    //     $pdf->SetMargins(15, 20, 15);
    //     $pdf->SetAutoPageBreak(true, 15);

    //     $pdf->AddPage();

    //     // Use the font you added with tcpdf_addfont
    //     $pdf->SetFont('notosansdevanagari', '', 12, '', false);

    //     // Render the HTML from Blade
    //     $html = view('schoolPortal.tpg.pdf-test-paper', $data)->render();
    //     $pdf->writeHTML($html, true, false, true, false, '');

    //     return response($pdf->Output('test-paper.pdf', 'S'))
    //         ->header('Content-Type', 'application/pdf');
    // }


    public function testPapersAdd(Request $request)
    {
        try {
            $role                   = getUserRoles();
            $parentId               = Auth::id();
            if ($role === 'school_teacher') {
                $parentId = Auth::user()->userAdditionalDetail->school_id;
            }
            $schoolBoard = getUserBoard();
            $schoolMedium = getUserMedium();

            $this->data['class'] = Classes::where('is_active', '1')->pluck('name', 'id')->toArray();
            $this->data['subject'] = Subject::where('is_active', '1')->pluck('name', 'id')->toArray();
            return view('schoolPortal.tpg.test-paper-add', $this->data);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'error' => config('constants.FLASH_TRY_CATCH'),
                'exception' => $e->getMessage(),
            ]);
        }
    }

    public function testPapersSave(Request $request)
    {
        // dd($request->all());
        $rules = [
            'paper_type'          => 'required|in:online,offline',
            'class_id'            => 'required|exists:classes,id',
            'subject_id'          => 'required|exists:subjects,id',
            'test_term'           => 'required|string',
            'title'               => 'required|string',
            'description'         => 'required|string',
            'question_order_type' => 'required|in:' . implode(',', array_keys(config('constants.QUESTION_ORDER_TYPE'))),
            'is_active'           => 'required|boolean',
        ];


        if ($request->paper_type === 'Online') {
            $rules = array_merge($rules, [
                'start_date_time'        => ['required', 'date', 'date_format:Y-m-d\TH:i'],
                'end_date_time'          => ['required', 'date', 'date_format:Y-m-d\TH:i', 'after:start_date_time'],
                'duration'               => 'required|numeric|min:1',
                'min_passing_percentage' => 'required|numeric|min:1|max:100',
            ]);
        }
        $testPaper = TestPaper::find($request->id);

        if (!$testPaper || !$testPaper->logo) {
            $rules['logo'] = 'nullable|file|mimes:jpg,jpeg,png|max:2048';
        } else {
            $rules['logo'] = 'nullable|file|mimes:jpg,jpeg,png|max:2048';
        }

        $validated = $request->validate($rules);

        try {
            $schoolBoard  = getUserBoard();
            $schoolMedium = getUserMedium();
            $role         = getUserRoles();
            $parentId     = Auth::id();

            if ($role === 'school_teacher') {
                $parentId = Auth::user()->userAdditionalDetail->school_id;
            }

            $data = $request->only([
                'paper_type',
                'class_id',
                'subject_id',
                'test_term',
                'title',
                'description',
                'question_order_type',
                'is_active',
                'start_date_time',
                'end_date_time',
                'duration',
                'min_passing_percentage',
            ]);

            if ($request->hasFile('logo')) {
                if ($testPaper && !empty($testPaper->logo) && Storage::disk('public')->exists($testPaper->logo)) {
                    Storage::disk('public')->delete($testPaper->logo);
                }

                $file     = $request->file('logo');
                $fileName = Str::uuid() . '_' . $file->getClientOriginalName();
                $filePath = 'uploads/test_papers/' . $fileName;

                Storage::disk('public')->put($filePath, file_get_contents($file));

                $data['logo'] = $filePath;
            }


            $data['board_id']   = $schoolBoard;
            $data['medium_id']  = $schoolMedium;
            $data['school_id']  = $parentId;
            $data['created_by'] = Auth::id();

            if ($request->paper_type === 'Offline') {
                unset(
                    $data['start_date_time'],
                    $data['end_date_time'],
                    $data['duration'],
                    $data['min_passing_percentage']
                );
            }

            $successMsg = $request->id > 0 ? config('constants.FLASH_REC_UPDATE_1') : config('constants.FLASH_REC_ADD_1');
            $errorMsg   = $request->id > 0 ? config('constants.FLASH_REC_UPDATE_0') : config('constants.FLASH_REC_ADD_0');

            $res = TestPaper::updateOrCreate(['id' => $request->id], $data);

            if ($res) {
                return redirect()->route('sp.test-paper.add-question', $res->id)->with(['success' => $successMsg]);
            }

            return redirect()->back()->with(['error' => $errorMsg]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'error'     => config('constants.FLASH_TRY_CATCH'),
                'exception' => $e->getMessage(),
            ]);
        }
    }




    public function testPaperView(Request $request, $id)
    {
        try {
            $this->data['testPaper'] = TestPaper::with('Subject', 'Class')->where('id', $id)->first();
            $this->data['questions'] = TestPaperQuestion::with(['Question', 'Question.options'])
                ->where('paper_id', $id)
                ->get();
            // dd($this->data['questions']);
            $this->data['totalMarks'] = $this->data['questions']->sum(function ($testPaperQuestion) {
                $question = $testPaperQuestion->Question;

                if (!$question) {
                    return 0;
                }

                $marks = (float) $question->marks;

                if ($question->is_approved === 1) {
                    return $marks;
                } else {
                    return -$marks;
                }
            });
            $this->data['testParticipent'] = TestParticipent::where('school_id', Auth::id())->pluck('test_id')->toArray();

            return view('schoolPortal.tpg.test-paper-view', $this->data);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'error' => config('constants.FLASH_TRY_CATCH'),
                'exception' => $e->getMessage(),
            ]);
        }
    }


    public function testPaperDelete($id)
    {
        $data = TestPaper::where('id', $id)->first();
        $data->delete();
        return redirect()->route('sp.test-papers')->with(['success' => config('constants.FLASH_REC_DELETE_1')]);
    }
    public function getChapters(Request $request)
    {
        $boardId = getUserBoard();
        $mediumId = getUserMedium();
        $seriesId = $request->input('series_id');
        $classId = $request->input('class_id');
        $subjectId = $request->input('subject_id');

        $courses = Course::query()
            // Class filter (always applied)
            ->whereHas('metadataValues', function ($query) use ($seriesId) {
                $query->where('field_name', 'series')->where('field_value', $seriesId);
            })
            ->whereHas('metadataValues', function ($query) use ($classId) {
                $query->where('field_name', 'class')->where('field_value', $classId);
            })

            // Subject filter (always applied)
            ->whereHas('metadataValues', function ($query) use ($subjectId) {
                $query->where('field_name', 'subject')->where('field_value', $subjectId);
            })

            ->get();

        $courseIds = $courses->pluck('id')->toArray();
        $chapters = CourseChapter::whereIn('course_id', $courseIds)->pluck('chapter_name', 'id')->toArray();

        return response()->json($chapters);
    }

    public function addQuestion($id)
    {
        try {
            $this->data['id'] = $id;
            return view('schoolPortal.tpg.test-paper-questions', $this->data);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'error' => config('constants.FLASH_TRY_CATCH'),
                'exception' => $e->getMessage(),
            ]);
        }
    }

    public function updateApproval(Request $request)
    {
        $question = QuestionBank::find($request->question_id);
        if ($question) {
            $question->is_approved = $request->is_approved;
            $question->save();

            return response()->json(['status' => 'success', 'message' => 'Approval status updated successfully.']);
        }
        return response()->json(['status' => 'error', 'message' => 'Question not found.']);
    }
    public function getStudents($classId)
    {
        $role     = getUserRoles();
        $parentId = Auth::id();
        if ($role === 'school_teacher') {
            $parentId = Auth::user()->userAdditionalDetail->school_id;
        }
        $student = User::with(['userAdditionalDetail', 'studentDetails', 'userAccessCode'])->where('status', 1)
            ->whereHas('studentDetails', function ($query) use ($parentId, $classId) {
                $query->where('parent_id', $parentId)
                    ->where('class', $classId);
            })->get();
        return response()->json($student);
    }
    public function getParticipants($testId, $classId)
    {
        $role     = getUserRoles();
        $parentId = Auth::id();
        if ($role === 'school_teacher') {
            $parentId = Auth::user()->userAdditionalDetail->school_id;
        }
        $participants = TestParticipent::where('school_id', $parentId)->where('test_id', $testId)
            ->where('class_id', $classId) // Filter by class_id
            ->where('created_by', Auth::id()) // Filter by class_id
            ->with('user', 'userAdditionalDetail') // Assuming there's a relation to User model
            ->get()
            ->map(function ($participant) {
                return [
                    'name' => $participant->user->name ?? 'N/A',
                    'admission_no' => $participant->userAdditionalDetail->admission_no ?? 'N/A',
                    'image' => $participant->user->image ?? 'null'
                ];
            });
        // dd($participants);

        return response()->json($participants);
    }
    public function assignTest(Request $request)
    {
        $role     = getUserRoles();
        $parentId = Auth::id();
        if ($role === 'school_teacher') {
            $parentId = Auth::user()->userAdditionalDetail->school_id;
        }
        // First, delete all old participants for this test & class
        TestParticipent::where('school_id', $parentId)->where('class_id', $request->class_id)->where('test_id', $request->test_id)->delete();

        if ($request->assign_type === 'individual') {
            foreach ($request->students as $studentId) {
                TestParticipent::create([
                    'school_id' => $parentId,
                    'user_id' => $studentId,
                    'class_id' => $request->class_id,
                    'test_id' => $request->test_id,
                    'created_by' => Auth::id(),
                    'status' => 1
                ]);
            }
        } else {
            $students = User::where('status', 1)
                ->whereHas('studentDetails', function ($query) use ($parentId, $request) {
                    $query->where('parent_id', $parentId)
                        ->where('class', $request->class_id);
                })->get();

            foreach ($students as $student) {
                TestParticipent::create([
                    'school_id' => $parentId,
                    'user_id' => $student->id,
                    'class_id' => $request->class_id,
                    'test_id' => $request->test_id,
                    'created_by' => Auth::id(),
                    'status' => 1
                ]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Students assigned successfully!']);
    }

    public function assignTestQuestions(Request $request)
    {
        try {
            // First, delete old questions
            TestPaperQuestion::where([
                'paper_id' => $request->test_id,
            ])->delete();

            if (isset($request->selected_questions)) {
                foreach ($request->selected_questions as $questionId) {
                    TestPaperQuestion::create([
                        'question_id' => $questionId,
                        'paper_id' => $request->test_id,
                    ]);
                }

                return redirect()->back()->with('success', 'Questions Assigned Successfully For This Test Paper.');
            } else {
                return redirect()->back()->with('success', 'All questions have been unassigned from this test paper.');
            }
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'An error occurred while assigning questions: ' . $e->getMessage());
        }
    }

    public function getClassesBySeries(Request $request)
    {
        $role     = getUserRoles();
        $parentId = Auth::id();
        $seriesId = $request->series_id;

        $bookSeries = BookSeries::find($seriesId);
        $classes = [];

        if ($bookSeries) {
            $classSubjects = json_decode($bookSeries->class_subjects, true);
            $classIds = array_column($classSubjects, 'class_id');

            if ($role === 'school_teacher') {
                // Get classes assigned to teacher (filtered)
                $allowedClassIds = getTeacherClasses(Auth::id(), Auth::user()->userAdditionalDetail->school_id);
                $filteredClassIds = array_intersect($classIds, $allowedClassIds);
                $classes = Classes::whereIn('id', $filteredClassIds)
                    ->where('is_active', 1)
                    ->pluck('name', 'id')
                    ->toArray();
            } else {
                // For other roles → No filtering
                $classes = Classes::whereIn('id', $classIds)
                    ->where('is_active', 1)
                    ->pluck('name', 'id')
                    ->toArray();
            }
        }

        return response()->json($classes);
    }

    public function getSubjectsByClass(Request $request)
    {
        $seriesId = $request->series_id;
        $classId  = $request->class_id;
        $role     = getUserRoles();
        $parentId = Auth::id();

        $bookSeries = BookSeries::find($seriesId);
        $subjects = [];

        if ($bookSeries) {
            $classSubjects = collect(json_decode($bookSeries->class_subjects, true));
            $selectedClassSubjects = $classSubjects->firstWhere('class_id', $classId);

            if ($selectedClassSubjects) {
                $subjectIds = $selectedClassSubjects['subject_ids'];

                if ($role === 'school_teacher') {
                    // Filter allowed subjects for this teacher
                    $allowedSubjectIds = getTeacherSubject(Auth::id(), Auth::user()->userAdditionalDetail->school_id);
                    $filteredSubjectIds = array_intersect($subjectIds, $allowedSubjectIds);
                    $subjects = Subject::whereIn('id', $filteredSubjectIds)
                        ->where('is_active', 1)
                        ->pluck('name', 'id')
                        ->toArray();
                } else {
                    // Other roles → no filtering
                    $subjects = Subject::whereIn('id', $subjectIds)
                        ->where('is_active', 1)
                        ->pluck('name', 'id')
                        ->toArray();
                }
            }
        }

        return response()->json($subjects);
    }

    public function generatePDFHindi($paperId, $user)
    {

        $html = view('schoolPortal.tpg.pdf-test-paper-hindi')->render();

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A3',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 15,
            'margin_bottom' => 15,
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
        ]);

        $mpdf->WriteHTML($html);
        return $mpdf->Output('test-paper.pdf', 'I');
    }
}
