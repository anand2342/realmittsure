<?php

namespace App\Livewire;

use App\Models\AccessCode;
use App\Models\AccessCodeEmbibe;
use App\Models\AccessCodePrefix;
use App\Models\AccessCodeOlympiad;
use App\Models\Board;
use App\Models\BookSeries;
use App\Models\BookSet;
use App\Models\Classes;
use App\Models\Medium;
use App\Models\SchoolClass;
use App\Models\Schools;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\Log;

class AccessCodeForm extends Component
{
    use WithFileUploads;

    public $isSaving           = false;
    public $tab                = 'embibe';
    public $uploadedData       = [];
    public $selectedData       = [];
    public $isModalOpen        = false;
    public $isPreviewModalOpen = false;
    public $isPreviewModalOpenOlympiad = false;
    public $file;
    // Form fields
    public $generationType = 'random';
    public $generationTypeOlympiad = 'custom';
    public $series_name;
    public $book_series_id;
    public $codeType        = 'digital_content';
    public $prefix          = '';
    public $code_length     = '';
    public $numbers_of_code = '';
    public $start_date;
    public $end_date;
    public $accessCodes = [];
    public $expirationDate;
    public $isLoading          = false;
    public $bookSeries         = [];
    public $schools            = [];
    public $schoolClasses      = [];
    public $boards             = [];
    public $mediums            = [];
    public $subjects           = [];
    public $bookSets           = [];
    public $prefixes           = [];
    public $headers            = [];
    public $rowErrors          = []; // Store errors for specific rows and columns
    public $showNewPrefixInput = false;
    public $newPrefix;
    public $selectedPrefix;
    public $school_id       = null;
    public $board_id        = null;
    public $medium_id       = null;
    public $class_id        = null;
    public $subject_id      = null;
    public $book_set_id     = null;
    public $subject_ids     = [];
    public $selectedSubject = [];
    public $selectedOption  = 'book_set';
    public $olmpiadBookSeries  = [];
    public $olympiadClasses  = [];
    public $olympiadSubjects  = [];
    public $code_generator  = null;
    public $expiration_date  = null;

    public function updatedSelectedOption($value)
    {
        // Reset values when the option changes
        if ($value === 'book_set') {
            $this->reset('subject_ids');
        } elseif ($value === 'subject') {
            $this->reset('book_set_id');
        }
    }
    public function mount()
    {
        $this->schools       = Schools::where('is_verified_by_admin', 1)->pluck('name', 'id');
        $this->boards        = Board::where('is_active', 1)->pluck('name', 'id')->toArray();
        $this->mediums       = Medium::where('is_active', 1)->pluck('name', 'id')->toArray();
        $this->schoolClasses = SchoolClass::where('is_active', 1)->pluck('name', 'id');
        $this->subjects      = Subject::where('is_active', 1)->pluck('name', 'id');
        $this->prefixes      = AccessCodePrefix::where('is_active', 1)->pluck('prefix', 'prefix');
        $this->series_name   = $this->series_name ?? 'Mittsure Digital Content';

        // for olmpiad
        $this->olmpiadBookSeries = BookSeries::where('is_active', 1)
            ->where('name', 'like', '%olympiad%')
            ->pluck('name', 'id')
            ->toArray();
        $olmBookSeries = BookSeries::where('is_active', 1)
            ->where('name', 'like', '%olympiad%')
            ->first();

        // Step 2: Extract class_subjects mapping
        if ($olmBookSeries) {
            $classSubjects = json_decode($olmBookSeries->class_subjects, true);

            $classIds = array_column($classSubjects, 'class_id'); // extract class IDs
            $subjectIds = array_unique(array_merge(...array_column($classSubjects, 'subject_ids')));
            $this->olympiadClasses = Classes::whereIn('id', $classIds)->where('is_active', 1)->pluck('name', 'id')->toArray();
            $this->olympiadSubjects = Subject::whereIn('id', $subjectIds)->where('is_active', 1)->pluck('name', 'id');
            // $this->classSubjectsMapping = $classSubjects;
        } else {
            $this->olympiadClasses = [];
            $this->olympiadSubjects = [];
        }
    }
    public function updatedTab($tab)
    {
        $this->resetForm();
        if ($tab === 'lumalearn') {
            $this->series_name    = 'Luma learn';
            $this->codeType       = 'lumalearn';
            $this->book_series_id = 3;
        } elseif ($tab === 'digitalContent') {
            $this->series_name = 'Mittsure Digital Content';
            $this->codeType    = 'digital_content'; // 1 is stand for all series
        } elseif ($tab === 'olympiad') {
            $this->series_name = 'Olympiad';
            $this->codeType    = 'olympiad';
            $this->book_series_id = 22;
            $this->end_date = $this->end_date ?? '2026-03-31';
        } else {
            $this->series_name = 'Embibe';
            $this->codeType    = 'embibe';
        }
    }
    public function updatedGenerationType()
    {
        if ($this->generationType === 'random') {
            // Clear custom generation fields when switching to random
            $this->prefix      = null;
            $this->code_length = null;
        }
        $this->resetForm();
    }
    public function handlePrefixChange($value)
    {
        $this->selectedPrefix = $value;

        // Show input box if "Add New" is selected
        if ($value === 'add_new') {
            $this->showNewPrefixInput = true;
            $this->prefix             = '';
        } else {
            $this->showNewPrefixInput = false;
        }
    }
    public function saveNewPrefix()
    {
        $this->validate([
            'prefix' => 'required|string|max:255|unique:access_code_prefixes,prefix',
        ]);
        // Save to database
        $prefix = AccessCodePrefix::create(['prefix' => $this->prefix]);
    }
    public function loadBookSet()
    {
        if ($this->board_id && $this->medium_id) {
            if ($this->board_id == 'all' && $this->medium_id == 'all') {
                $this->bookSeries = BookSeries::where('is_active', 1)->where('board_id', 0)
                    ->where('medium_id', 0)
                    ->pluck('name', 'id')
                    ->toArray();
            } else {
                $this->bookSeries = BookSeries::where('is_active', 1)->where('board_id', $this->board_id)
                    ->where('medium_id', $this->medium_id)
                    ->pluck('name', 'id')
                    ->toArray();
            }
        }

        if ($this->board_id && $this->medium_id && $this->class_id) {
            if ($this->board_id == 'all' && $this->medium_id == 'all') {
                $this->bookSets = BookSet::where('is_active', 1)->where('board_id', 0)
                    ->where('medium_id', 0)
                    ->where('class_id', $this->class_id)
                    ->pluck('name', 'id')
                    ->toArray();
            } else {
                $this->bookSets = BookSet::where('is_active', 1)->where('board_id', $this->board_id)
                    ->where('medium_id', $this->medium_id)
                    ->where('class_id', $this->class_id)
                    ->pluck('name', 'id')
                    ->toArray();
            }
        }
    }
    public function generatePreviewCodes()
    {
        if ($this->book_set_id) {
            $subjectIdsString  = BookSet::where('is_active', 1)->where('id', $this->book_set_id)->value('subject_id');
            $this->subject_ids = $subjectIdsString ? explode(',', $subjectIdsString) : [];
        } else {
            $this->subject_ids = $this->selectedSubject;
        }

        $this->validate([
            'book_series_id'  => 'required',
            'class_id'        => 'required',
            'numbers_of_code' => 'required',
            'start_date'      => 'required|date',
            'end_date'        => 'required|date|after:start_date',
            'generationType'  => 'required',
        ]);

        $this->accessCodes = [];

        // Calculate the random part length, including prefix length for custom generation
        if ($this->generationType === 'custom' && $this->code_length) {
            $randomPartLength = $this->code_length - strlen($this->prefix);
            if ($randomPartLength < 1) {
                session()->flash('error', 'Code length must be greater than the prefix length.');
                return;
            }
        } else {
            $randomPartLength = 8;
        }

        // Generate access codes
        for ($i = 1; $i <= $this->numbers_of_code; $i++) {
            $isUnique     = false;
            $randomString = '';

            while (! $isUnique) {
                // Generate code based on type
                if ($this->generationType === 'random') {
                    $randomString = strtoupper(Str::random($randomPartLength));
                } else {
                    $randomString = $this->prefix . strtoupper(Str::random($randomPartLength));
                }

                $isUnique = ! AccessCode::where('access_code', $randomString)->exists();
            }
            // Handle "All" cases for board_id and medium_id
            $boardIdToSave  = ($this->board_id === 0 || $this->board_id === 'all') ? '0' : $this->board_id;
            $mediumIdToSave = ($this->medium_id === 0 || $this->medium_id === 'all') ? '0' : $this->medium_id;

            // Store code along with other details
            $this->accessCodes[] = [
                'school_name'     => Schools::where('id', $this->school_id)->value('name'),
                'class_name'      => Classes::where('id', $this->class_id)->value('name'),

                'series_name'     => $this->series_name,    // Stand for access code type
                'series_id'       => $this->codeType,       // Stand for access code type
                'book_series_id'  => $this->book_series_id, // Stand for access code type
                'code'            => $randomString,
                'status'          => 'Generated',
                'generation_date' => $this->start_date,
                'expiration_date' => $this->end_date,
                'user_id'         => null,

                'school_id'       => $this->school_id,
                'board_id'        => $boardIdToSave,
                'medium_id'       => $mediumIdToSave,
                'class_id'        => $this->class_id,
                'book_set_id'     => $this->book_set_id ?? null,
                'subject_ids'     => $this->subject_ids,
            ];
        }
        $this->isPreviewModalOpen = true;
        // $this->dispatch('openModal');
    }

    public function saveCodes()
    {
        $this->isPreviewModalOpen = false;

        if (empty($this->accessCodes)) {
            session()->flash('error', 'No access codes available to save.');
            return;
        }
        $this->isSaving = true; // Show the loader

        foreach ($this->accessCodes as $accessCode) {
            // dd($accessCode);
            $subjectIdsString = implode(',', $accessCode['subject_ids']);
            // dd($accessCode['series_id']);
            AccessCode::create([
                'book_series_id'  => $accessCode['book_series_id'],
                'type'            => $accessCode['series_id'],
                'school_id'       => null,
                'status'          => $accessCode['status'],
                'generated_by'    => auth()->id(),
                'start_date'      => $accessCode['generation_date'],
                'end_date'        => $accessCode['expiration_date'],
                'generation_type' => $this->generationType,
                'prefix_code'     => $this->prefix,
                'postfix_code'    => null,
                'access_code'     => $accessCode['code'],
                'school_id'       => $accessCode['school_id'],
                'board_id'        => $accessCode['board_id'],
                'medium_id'       => $accessCode['medium_id'],
                'class_id'        => $accessCode['class_id'],
                'book_set_id'     => $accessCode['book_set_id'],
                'subject_id'      => $subjectIdsString,
            ]);
        }

        $this->reset('accessCodes');
        $this->dispatch('codeSaved');
        $this->isSaving = false; // Hide the loader

        return redirect()->route('access.code.index')->with(['success' => config('constants.FLASH_REC_ADD_1')]);
    }
    public function generatePreviewOlympiadCodes()
    {
        $this->validate([
            'prefix'           => 'required',
            'class_id'         => 'required',
            'subject_id'       => 'required',
            'code_length'      => 'required|integer|min:1',
            'numbers_of_code'  => 'required|integer|min:1',
            'end_date'         => 'required|date|after:today',
            'code_generator'   => 'required',
        ]);

        $this->accessCodes = [];

        $this->generationTypeOlympiad = 'custom';

        if ($this->generationTypeOlympiad === 'custom' && $this->code_length) {
            $randomPartLength = $this->code_length - strlen($this->prefix);
            if ($randomPartLength < 1) {
                session()->flash('error', 'Code length must be greater than the prefix length.');
                return;
            }
        } else {
            $randomPartLength = 8;
        }

        $expirationDate = \Carbon\Carbon::parse($this->end_date)->toDateString();

        $generatedCodes = [];

        while (count($this->accessCodes) < $this->numbers_of_code) {
            if ($this->generationTypeOlympiad === 'random') {
                $randomString = strtoupper(Str::random($randomPartLength));
            } else {
                $randomString = $this->prefix . strtoupper(Str::random($randomPartLength));
            }

            // Strict uniqueness check
            $existsInOlympiad = AccessCodeOlympiad::where('access_code', $randomString)->exists();
            $existsInRegular  = AccessCode::where('access_code', $randomString)->exists();
            $alreadyGenerated = in_array($randomString, $generatedCodes);

            if (! $existsInOlympiad && ! $existsInRegular && ! $alreadyGenerated) {
                $generatedCodes[] = $randomString;

                $this->accessCodes[] = [
                    'class_name'      => Classes::where('id', $this->class_id)->value('name'),
                    'subject_name'    => Subject::where('id', $this->subject_id)->value('name'),
                    'series_name'     => $this->series_name,
                    'series_id'       => $this->codeType,
                    'book_series_id'  => $this->book_series_id,
                    'class_id'        => $this->class_id,
                    'subject_id'      => $this->subject_id,
                    'code'            => $randomString,
                    'status'          => 'Generated',
                    'generation_date' => today()->toDateString(),
                    'expiration_date' => $expirationDate,
                    'user_id'         => null,
                ];
            }
        }

        $this->isPreviewModalOpenOlympiad = true;
    }

    // public function generatePreviewOlympiadCodes()
    // {
    //     $this->validate([
    //         'prefix'           => 'required',
    //         'class_id'         => 'required',
    //         'subject_id'       => 'required',
    //         'code_length'      => 'required|integer|min:1',
    //         'numbers_of_code'  => 'required|integer|min:1',
    //         'end_date'         => 'required|date|after:today',
    //         'code_generator'   => 'required',
    //     ]);

    //     $this->accessCodes = [];

    //     // Fix assignment (was using === instead of =)
    //     $this->generationTypeOlympiad = 'custom';

    //     // Calculate the random part length, including prefix length for custom generation
    //     if ($this->generationTypeOlympiad === 'custom' && $this->code_length) {
    //         $randomPartLength = $this->code_length - strlen($this->prefix);
    //         if ($randomPartLength < 1) {
    //             session()->flash('error', 'Code length must be greater than the prefix length.');
    //             return;
    //         }
    //     } else {
    //         $randomPartLength = 8;
    //     }

    //     // Format end_date to store only the date (no time)
    //     $expirationDate = \Carbon\Carbon::parse($this->end_date)->toDateString(); // YYYY-MM-DD

    //     for ($i = 1; $i <= $this->numbers_of_code; $i++) {
    //         $isUnique = false;
    //         $randomString = '';

    //         while (! $isUnique) {
    //             if ($this->generationTypeOlympiad === 'random') {
    //                 $randomString = strtoupper(Str::random($randomPartLength));
    //             } else {
    //                 $randomString = $this->prefix . strtoupper(Str::random($randomPartLength));
    //             }

    //             $isUnique = ! AccessCodeOlympiad::where('access_code', $randomString)->exists();
    //         }

    //         $this->accessCodes[] = [
    //             'class_name'      => Classes::where('id', $this->class_id)->value('name'),
    //             'subject_name'      => Subject::where('id', $this->subject_id)->value('name'),

    //             'series_name'     => $this->series_name,
    //             'series_id'       => $this->codeType,
    //             'book_series_id'  => $this->book_series_id,
    //             'class_id'        => $this->class_id,
    //             'subject_id'     => $this->subject_id,
    //             'code'            => $randomString,
    //             'status'          => 'Generated',
    //             'generation_date' => today()->toDateString(), // Save only date
    //             'expiration_date' => $expirationDate,         // Clean date format
    //             'user_id'         => null,
    //         ];
    //     }

    //     $this->isPreviewModalOpenOlympiad = true;
    // }
    public function saveOlympiadCodes()
    {
        $this->isPreviewModalOpenOlympiad = false;

        if (empty($this->accessCodes)) {
            session()->flash('error', 'No access codes available to save.');
            return;
        }

        $this->isSaving = true;

        // Get last serial number from DB and increment
        $lastSerial = AccessCodeOlympiad::max('serial_number') ?? 0;
        $serial = $lastSerial + 1;

        foreach ($this->accessCodes as $accessCode) {
            try {
                AccessCodeOlympiad::create([
                    'serial_number'       => $serial++,
                    'book_series_id'      => $accessCode['book_series_id'],
                    'series_name'         => $accessCode['series_name'],
                    'class_id'            => $accessCode['class_id'],
                    'class_name'          => $accessCode['class_name'],
                    'subject_id'          => $accessCode['subject_id'],
                    'subject_name'        => $accessCode['subject_name'],
                    'access_code'         => $accessCode['code'],
                    'prefix'              => $this->prefix,
                    'code_length'         => $this->code_length,
                    'status'              => $accessCode['status'],
                    'generation_date'     => $accessCode['generation_date'],
                    'expiration_date'     => $accessCode['expiration_date'],
                    'code_generator_name' => $this->code_generator,
                    'created_by'          => auth()->id(),
                    'user_id'             => null,
                ]);
            } catch (\Illuminate\Database\QueryException $e) {
                if ($e->getCode() == 23000) {
                    Log::error("Duplicate access code blocked: {$accessCode['code']}");
                    continue;
                }
                throw $e;
            }
        }

        $this->reset('accessCodes');
        $this->dispatch('codeSavedOlympiad');
        $this->isSaving = false;

        return redirect()->route('access.code.olympiad.index')
            ->with(['success' => config('constants.FLASH_REC_ADD_1')]);
    }

    public function resetForm()
    {
        $this->prefix          = null; // Clear prefix
        $this->code_length     = null; // Clear code length
        $this->school_id       = null; // Reset school selection
        $this->board_id        = null; // Reset board selection
        $this->medium_id       = null; // Reset medium selection
        $this->class_id        = null; // Reset class selection
        $this->numbers_of_code = null; // Reset number of codes
        $this->start_date      = null; // Clear start date
        $this->end_date        = null; // Clear expiration date
        $this->accessCodes     = [];   // Clear preview codes
    }
    public function uploadEmbibeAccessCodeMittlense()
    {
        $this->isLoading = true;
        if ($this->file) {
            try {
                $data = Excel::toArray([], $this->file)[0];

                $this->headers = array_filter($data[0], function ($header) {
                    return ! empty($header);
                });

                $this->uploadedData = array_map(function ($row) {
                    if (empty(array_filter($row))) {
                        return null; // Skip completely empty rows
                    }

                    $row = array_pad($row, count($this->headers), null); // Pad if row has fewer values
                    $row = array_slice($row, 0, count($this->headers));  // Trim if row has extra values

                    // Convert Excel date fields dynamically
                    foreach ($this->headers as $index => $columnName) {
                        if ($this->isDateColumn($columnName) && isset($row[$index]) && is_numeric($row[$index])) {
                            $row[$index] = Date::excelToDateTimeObject($row[$index])->format('d/m/Y');
                        }
                    }
                    // Combine headers with row values
                    return array_combine($this->headers, $row);
                }, array_slice($data, 1)); // Exclude the first row (headers)

                // Remove null rows
                $this->uploadedData = array_filter($this->uploadedData);

                // Display the modal with data
                $this->isModalOpen = true;
                $this->isLoading   = false;
            } catch (\Exception $e) {
                session()->flash('errorMsg', ['Error processing the file. Please check the format and try again.']);
            }
        } else {
            session()->flash('errorMsg', ['No file uploaded.']);
        }
    }
    public function uploadEmbibeAccessCodeTeachlite()
    {
        $this->isLoading = true;
        if ($this->file) {
            try {
                $data = Excel::toArray([], $this->file)[0];

                $this->headers = array_filter($data[0], function ($header) {
                    return ! empty($header);
                });

                $this->uploadedData = array_map(function ($row) {
                    if (empty(array_filter($row))) {
                        return null; // Skip completely empty rows
                    }

                    $row = array_pad($row, count($this->headers), null); // Pad if row has fewer values
                    $row = array_slice($row, 0, count($this->headers));  // Trim if row has extra values

                    // Convert Excel date fields dynamically
                    foreach ($this->headers as $index => $columnName) {
                        if ($this->isDateColumn($columnName) && isset($row[$index]) && is_numeric($row[$index])) {
                            $row[$index] = Date::excelToDateTimeObject($row[$index])->format('d/m/Y');
                        }
                    }
                    // Combine headers with row values
                    return array_combine($this->headers, $row);
                }, array_slice($data, 1)); // Exclude the first row (headers)

                // Remove null rows
                $this->uploadedData = array_filter($this->uploadedData);

                // Display the modal with data
                $this->isModalOpen = true;
                $this->isLoading   = false;
            } catch (\Exception $e) {

                session()->flash('errorMsg', ['Error processing the file. Please check the format and try again.']);
            }
        } else {
            session()->flash('errorMsg', ['No file uploaded.']);
        }
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
        if (! empty($this->selectedData)) {
            $this->rowErrors = []; // Reset row errors
            $successCount    = 0;

            foreach ($this->uploadedData as $rowKey => $row) {
                if (! in_array($rowKey, $this->selectedData)) {
                    continue;
                }

                // Convert row keys to snake case
                $convertedRow = [];
                foreach ($row as $key => $value) {
                    $newKey                = $this->convertToSnakeCase($key);
                    $convertedRow[$newKey] = $value;
                }
                try {
                    $this->saveAccessCode($convertedRow, $rowKey);
                    $successCount++;
                } catch (\Exception $e) {
                    // dd($e);
                    // Collect validation errors
                    $this->rowErrors[$rowKey] = array_merge($this->rowErrors[$rowKey] ?? [], $this->parseValidationErrors($e->getMessage()));
                }
            }

            if ($successCount > 0) {
                session()->flash('successMsg', "{$successCount} row(s) successfully saved!");
            }

            if (! empty($this->rowErrors)) {

                session()->flash('errorMsg', 'Some rows have errors. Please review.');
            }
        } else {
            session()->flash('errorMsg', 'No data selected for processing.');
        }
    }
    private function isDateColumn($columnName)
    {
        $dateColumns = ['activation_date']; // Add expected date column names
        return in_array($columnName, $dateColumns);
    }
    private function saveAccessCode(array $data, string $rowKey)
    {
        // dd($data);
        if (isset($data['license_key'])) {
            $this->saveAccessCodeMittlense($data, $rowKey);
        } else {
            $exists = AccessCodeEmbibe::where('licence_key', $data['licence_key'])
                // ->orWhere('embibe_id', $data['id'])
                ->exists();

            if (! $exists) {
                $accessCode = AccessCodeEmbibe::create([
                    'embibe_id'            => $data['id'] ?? null,
                    'licence_key'          => $data['licence_key'] ?? null,
                    'ip'                   => $data['ip'] ?? null,
                    'device_id'            => $data['device_id'] ?? null,
                    'activation_date'      => $data['activation_date'] ?? null,
                    // 'activation_date'      => Carbon::createFromFormat('d-m-Y', $data['activation_date'])->format('Y-m-d') ?? null,
                    'activation_updatedAt' => $data['activation_updatedat'] ?? null,
                    'org_id'               => $data['org_id'] ?? null,
                    'activation_limit'     => $data['activation_limit'] ?? 1,
                    'licence_expiry'       => $data['licence_expiry'] ?? null,
                    'content_bundle'       => $data['content_bundle'] ?? null,
                    'content_bundle_id'    => $data['content_bundle_id'] ?? null,
                    'notes'                => $data['notes'] ?? null,
                    'config'               => isset($data['config']) ? json_encode($data['config']) : null,
                    'requestBy'            => $data['requestby'] ?? null,
                    'requestTeam'          => $data['requestteam'] ?? null,
                    'requestPersonName'    => $data['requestpersonname'] ?? null,
                    'customerName'         => $data['customername'] ?? null,
                    'platform'             => $data['platform'] ?? null,
                    'board'                => $data['board'] ?? null,
                    'grades'               => $data['grades'] ?? null,
                    'resolution'           => $data['resolution'] ?? null,
                    'license_createdAt'    => $data['license_createdat'] ?? now(),
                    'license_updatedAt'    => $data['license_updatedat'] ?? now(),
                    'type'                 => $data['type'] ?? null,
                    'created_by'           => auth()->id(),
                ]);
            } else {

                $errorData = ['licence_key' => ['Duplicate entry: This access code licence_key already exists.']];
                // $errorData = ['id' => ['Duplicate entry: This access code id already exists.']];
                throw new \Exception(json_encode([
                    'row'    => $rowKey,
                    'errors' => $errorData,
                ]));
            }

            if (! $accessCode) {
                throw new \Exception("Error saving user data for row with key: {$rowKey}.");
            }

            $this->isModalOpen = false;
            return "Access code data successfully saved for row: {$rowKey}";
        }
    }
    private function saveAccessCodeMittlense($data, $rowKey)
    {
        $exists = AccessCodeEmbibe::where('licence_key', $data['license_key'])
            // ->orWhere('embibe_id', $data['id'])
            ->exists();
        if (! $exists) {
            $accessCode = AccessCodeEmbibe::create([
                'embibe_id'            => $data['id'] ?? null,
                'licence_key'          => $data['license_key'] ?? null,
                'org_id'               => $data['school_name'] ?? null,
                'board'                => $data['goal'] ?? null,
                'exam'                 => $data['exam'] ?? null,
                'status'               => $data['license_status'] ?? null,
                'activation_date'      => $data['license_creation_date'] ?? null,
                // 'activation_date'      => Carbon::createFromFormat('d-m-Y', $data['activation_date'])->format('Y-m-d') ?? null,
                'activation_updatedAt' => $data['license_activation_date'] ?? null,
                'licence_expiry' => $data['license_expiry_date'] ?? null,
                'type'                 => 'mittlense',
                'created_by'           => auth()->id(),
            ]);
        } else {

            $errorData = ['license_key' => ['Duplicate entry: This access code licence_key already exists.']];
            // $errorData = ['id' => ['Duplicate entry: This access code id already exists.']];
            throw new \Exception(json_encode([
                'row'    => $rowKey,
                'errors' => $errorData,
            ]));
        }

        if (! $accessCode) {
            throw new \Exception("Error saving user data for row with key: {$rowKey}.");
        }

        $this->isModalOpen = false;
        return "Access code data successfully saved for row: {$rowKey}";
    }
    // private function saveAccessCodeQDsDev(array $data, string $rowKey)
    // {
    //     // Adjust the validation rules based on your data structure
    //     $validationRules = [
    //         'school_name' => 'required|string|max:255',
    //         'board_name' => 'required|string|max:255',
    //         'medium_name' => 'required|string|max:255',
    //         'class_name' => 'required|string|max:255',
    //         'access_code' => 'required|string|max:255|unique:access_codes,access_code,' . ($data['access_code'] ?? 'null'),
    //         'start_date' => 'required|date',
    //         'end_date' => 'required|date|after_or_equal:start_date',
    //     ];

    //     $validator = Validator::make($data, $validationRules);

    //     if ($validator->fails()) {
    //         throw new \Exception(json_encode($validator->errors()->toArray()));
    //     }
    //     $school = Schools::where('name', $data['school_name'])->where('is_verified_by_admin', 1)->first();
    //     $board = Board::where('name', $data['board_name'])->where('is_active', 1)->first();
    //     $class = Classes::where('name', $data['class_name'])->where('is_active', 1)->first();
    //     $medium = Medium::where('name', $data['medium_name'])->where('is_active', 1)->first();
    //     $accessCode = AccessCode::create([
    //         'school_id' => $school->id,
    //         'board_id' => $board->id,
    //         'medium_id' => $medium->id,
    //         'generated_by' => Auth::id(),
    //         'class_id' => $class->id,
    //         'access_code' => $data['access_code'],
    //         'start_date' => Carbon::createFromFormat('d-m-Y', $data['start_date'])->format('Y-m-d'), // Format date as needed
    //         'end_date' => Carbon::createFromFormat('d-m-Y', $data['end_date'])->format('Y-m-d'), // Convert from d/m/Y to Y-m-d
    //     ]);
    //     if (!$accessCode) {
    //         throw new \Exception("Error saving user data for row with key: {$rowKey}.");
    //     }

    //     $this->isModalOpen = false;
    //     return "Access code data successfully saved for row: {$rowKey}";
    // }

    private function parseValidationErrors($errorMessage)
    {
        $errors = json_decode($errorMessage, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [];
        }
        $parsedErrors = [];

        if (isset($errors['errors']) && is_array($errors['errors'])) {
            foreach ($errors['errors'] as $header => $errorList) {
                if (! empty($errorList)) {
                    $parsedErrors[$header] = $errorList;
                }
            }
        }
        return ! empty($parsedErrors) ? $parsedErrors : $errors;
    }
    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->isLoading   = false;
    }
    public function render()
    {
        return view('livewire.access-code-form');
    }
}
