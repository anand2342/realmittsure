<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\CourseChapter;
use App\Models\MediaFolder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Livewire\WithFileUploads;

class ChaptersBulkUpload extends Component
{
    use WithFileUploads;
    public $courseId;
    public $isShow = false;
    public $selectedCourse;
    public $childCategories = [];
    public $file;
    public $isLoading = false;  // Add this property to track the loader state
    public $uploadedData = [];
    public $selectedData = [];
    public $isModalOpen = false;
    public $headers = [];
    public $rowErrors = []; // Store errors for specific rows and columns

    public function downloadSampleFile()
    {

        $filePath = public_path("admin/sample-files/courses-chapter-sample.xlsx");
        if (file_exists($filePath)) {
            return response()->download($filePath);
            session()->flash('succes', 'File downloaded!');
        }
        session()->flash('error', 'File not found!');
    }

    public function uploadUsers()
    {
        $this->resetImportState();
        $this->isLoading = true;
        if ($this->file) {

            try {
                // Convert the Excel file into an array
                $data = Excel::toArray([], $this->file)[0];

                if (empty($data) || !isset($data[0])) {
                    session()->flash('errorMsg', ['The file is empty or has invalid formatting.']);
                    return;
                }


                // Extract headers dynamically and remove empty ones
                $this->headers = array_values(array_filter($data[0], function ($header) {
                    return !empty($header);
                }));

                // Process the remaining rows dynamically
                $this->uploadedData = array_filter(array_map(function ($row) {
                    if (empty(array_filter($row))) {
                        return null; // Skip empty rows
                    }

                    // Match row with header count
                    $row = array_pad($row, count($this->headers), null);
                    $row = array_slice($row, 0, count($this->headers));

                    // Convert Excel date fields dynamically
                    foreach ($this->headers as $index => $columnName) {
                        if ($this->isDateColumn($columnName) && isset($row[$index]) && is_numeric($row[$index])) {
                            $row[$index] = Date::excelToDateTimeObject($row[$index])->format('d/m/Y');
                        }
                    }

                    return array_combine($this->headers, $row);
                }, array_slice($data, 1)));

                // Remove null rows
                $this->uploadedData = array_values($this->uploadedData);

                // Display the modal with data
                $this->isModalOpen = true;
                $this->isLoading = false;
            } catch (\Exception $e) {
                session()->flash('errorMsg', ['Error processing the file. Please check the format and try again.']);
            }
        } else {
            session()->flash('errorMsg', ['No file uploaded.']);
        }
    }

    /**
     * Check if a column should be treated as a date
     */
    private function isDateColumn($columnName)
    {
        $dateColumns = ['Content Creation Date']; // Add expected date column names
        return in_array($columnName, $dateColumns);
    }

    public function convertToSnakeCase($string)
    {
        $string = preg_replace('/\s+/', '_', $string);
        $string = str_replace('*', '', $string);
        $string = strtolower($string);
        return $string;
    }

    public function processSelectedData()
    {
        if (empty($this->selectedData)) {
            session()->flash('errorMsg', 'No data selected for processing.');
            return;
        }

        $this->rowErrors = []; // Reset row errors
        $successCount = 0;

        // Start a database transaction for the entire batch
        DB::beginTransaction();

        try {
            // First pass: Validate all selected rows
            foreach ($this->uploadedData as $rowKey => $row) {
                if (!in_array($rowKey, $this->selectedData)) {
                    continue;
                }

                $convertedRow = $this->convertRowKeys($row);
                $this->validateTalentAndSkills($convertedRow, $rowKey);
            }

            // Second pass: Save all rows (only reaches here if all validation passed)
            foreach ($this->uploadedData as $rowKey => $row) {
                if (!in_array($rowKey, $this->selectedData)) {
                    continue;
                }

                $convertedRow = $this->convertRowKeys($row);
                $this->saveTalentAndSkills($convertedRow, $rowKey);
                $successCount++;
            }

            DB::commit();

            if ($successCount > 0) {
                // $this->isModalOpen = false;
                // // session()->flash('successMsg', "{$successCount} row(s) successfully saved!");
                // $this->file = null;
                return redirect()->route('course.add.chapter', $this->courseId)->with('success', "{$successCount} row(s) successfully saved!"); // or ->to('/your-url')

            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->handleImportError($e);
        }
    }
    /**
     * Save data based on the role type.
     */
    private function convertRowKeys(array $row): array
    {
        $converted = [];
        foreach ($row as $key => $value) {
            $converted[$this->convertToSnakeCase($key)] = $value;
        }
        return $converted;
    }
    private function handleImportError(\Exception $e)
    {
        // dd($e);

        $errorData = json_decode($e->getMessage(), true);

        if (json_last_error() === JSON_ERROR_NONE && isset($errorData['row'])) {
            $this->rowErrors[$errorData['row']] = $errorData['errors'];
            session()->flash('errorMsg', 'Some rows have errors. No data was saved. Please review.');
        } else {
            session()->flash('errorMsg', 'Error during import: ' . $e->getMessage());
        }
    }

    private function validateTalentAndSkills(array $data, string $rowKey)
    {

        $validator = Validator::make($data, [
            'sort_order' => 'required|integer',
            'chapter_title' => 'required|max:255',
            'chapter_description' => 'required',
            'content_creation_date' => 'required|date_format:d/m/Y',
        ], [
            'content_creation_date.date_format' => 'The content creation date must be in the format dd/mm/yyyy.',
        ]);

        if ($validator->fails()) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => $validator->errors()->toArray()
            ]));
        }

        // Validate folder exists if provided
        if (!empty($data['folder_name_for_additional_document'])) {
            $folderId = MediaFolder::where('folder_name', $data['folder_name_for_additional_document'])
                ->value('id');

            if (!$folderId) {
                throw new \Exception(json_encode([
                    'row' => $rowKey,
                    'errors' => ['folder_name_for_additional_document' => ['Folder not found']]
                ]));
            }
        }

        // Validate course exists
        if (!$this->courseId || !Course::where('id', $this->courseId)->exists()) {
            throw new \Exception(json_encode([
                'row' => $rowKey,
                'errors' => ['course_id' => ['Invalid course selected']]
            ]));
        }
    }

    private function saveTalentAndSkills(array $data, string $rowKey)
    {
        $chapterTitle = preg_replace('/\s+/', ' ',  $data['chapter_title']);

        $chapterData = [
            'course_id' => $this->courseId,
            'chapter_name' => $chapterTitle,
            'chapter_description' => $data['chapter_description'],
            'sort_order' => $data['sort_order'],
            'topic_covered' => $data['topic_covered'] ?? null,
            'content_creation_date' => Carbon::createFromFormat('d/m/Y', $data['content_creation_date'])->format('Y-m-d'),
            'created_by' => Auth::id(),
            'created_date' => now(),
            'is_approved' => 0,
        ];

        // Add folder if provided
        if (!empty($data['folder_name_for_additional_document'])) {
            $chapterData['supporting_folder_id'] = MediaFolder::where('folder_name', $data['folder_name_for_additional_document'])
                ->value('id');
        }

        // Create chapter
        $courseChapter = CourseChapter::create($chapterData);

        if (!$courseChapter) {
            throw new \Exception("Error saving course chapter for row with key: {$rowKey}.");
        }

        return "Talent and skills data successfully saved for row: {$rowKey}";
    }
    public function closeModal()
    {
        $this->isLoading = false;
    }
    private function resetImportState()
    {
        $this->rowErrors = [];
        $this->uploadedData = [];
        $this->selectedData = [];
        $this->headers = [];
        $this->isModalOpen = false;
    }
    public function render()
    {
        return view('livewire.chapters-bulk-upload');
    }
}
