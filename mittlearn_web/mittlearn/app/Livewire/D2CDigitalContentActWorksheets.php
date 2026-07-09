<?php

namespace App\Livewire;

use App\Models\BookSeries;
use App\Models\Category;
use App\Models\Classes;
use App\Models\Course;
use App\Models\D2cDigitalContent as ModelsD2cDigitalContent;
use App\Models\Medium;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\On;

class D2CDigitalContentActWorksheets extends Component
{
    public $generatingQrFor = null;
    public $selectedClassIds = [];
    public $classes;
    public $categoryCourses = [];
    public $d2cContentData = [];
    public $existingData = [];
    public $category_id;
    public $allClasses = [];
    public $medium = [];
    public $d2cData;
    public $subjects = [];
    public $rows = [];
    public $parentCategroy = [];
    public $selectedMediumIds = [];
    public $parentCategoryId;
    public $sub_category_id;
    public $coursesIds;
    // Modal properties
    public $showModal = false;
    public $currentClassId;
    public $parent_category_id;
    public $parent_id;
    public $subCategory = [];
    public $subCategoryCourse = [];
    public $selectedModalCourses = [];
    public $selectedCoursesId = [];
    public $categoryCoursesPerClass = [];

    public function mount($id)
    {
        $this->category_id = $id;
        $this->parent_category_id = Category::where('status', 1)->whereNotNull('parent_id')
            ->where('id', $this->category_id)
            ->value('parent_id');

        $this->loadInitialData();
    }

    protected function loadInitialData()
    {
        $this->allClasses = Classes::where('is_active', 1)
            ->orderBy('created_at', 'asc')
            ->pluck('name', 'id')
            ->toArray();

        $this->medium = Medium::where('is_active', 1)->where('id', '!=', 0)
            ->orderBy('created_at', 'asc')
            ->pluck('name', 'id')
            ->toArray();

        $this->selectedMediumIds = ModelsD2cDigitalContent::where('sub_category_id', $this->category_id)
            ->whereNotNull('medium_id')
            ->pluck('medium_id')
            ->toArray();

        $this->classes = ModelsD2cDigitalContent::with('className')
            ->where('sub_category_id', $this->category_id)
            ->whereNotNull('class_id')
            ->get();

        $this->selectedClassIds = $this->classes->pluck('class_id')->unique()->toArray();

        // For each class, fetch courses linked to it via metadataValues
        foreach ($this->classes as $class) {
            $classId = (string) $class->class_id;
            $mediumId = $class->medium_id;

            $key = "{$classId}_" . ($mediumId !== null ? $mediumId : 'null');

            $this->categoryCoursesPerClass[$key] = Course::with('metadataValues')
                ->where('category_id', 1)
                ->where('sub_category_id', $this->category_id)
                ->whereHas('metadataValues', function ($query) use ($classId) {
                    $query->where('field_name', 'class')
                        ->where('field_value', $classId);
                })
                ->when(!is_null($mediumId), function ($query) use ($mediumId) {
                    $query->whereHas('metadataValues', function ($q) use ($mediumId) {
                        $q->where('field_name', 'medium')
                            ->whereIn('field_value', [$mediumId, 0]);
                    });
                })
                ->pluck('course_name', 'id')
                ->toArray();
        }

        $this->loadExistingContent();
    }

    protected function loadExistingContent()
    {
        // Load data grouped by class_id, medium_id, and sn
        $subCategoryData = ModelsD2cDigitalContent::where('sub_category_id', $this->category_id)
            ->orderBy('class_id')
            ->orderBy('medium_id')
            ->orderBy('sn')
            ->get();

        $d2cContentData = ModelsD2cDigitalContent::where('d2c_content_id', $this->category_id)->get();

        $this->existingData = [];
        $this->d2cContentData = [];

        // Process sub_category_id data with serial numbers
        foreach ($subCategoryData as $item) {
            $classId = $item->class_id;
            $mediumId = $item->medium_id;
            $sn = $item->sn ?? 1;

            if (!$classId) continue;

            // Key now includes serial number
            $key = "{$classId}_" . ($mediumId !== null ? $mediumId : 'null') . "_{$sn}";

            $this->existingData[$key] = [
                'class_id'      => $classId,
                'medium_id'     => $mediumId,
                'sn'            => $sn,
                'course_ids'    => is_string($item->course_id)
                    ? explode(',', $item->course_id)
                    : (array) $item->course_id,
                'qr_name'       => $item->qr_name,
                'qr_code_link'  => $item->qr_code_link,
            ];
        }

        // Process d2c_content_id data
        $this->d2cContentData = $d2cContentData->groupBy('class_id')->map(function ($items) {
            return $items->map(function ($item) {
                return [
                    'course_ids' => is_string($item->course_id)
                        ? explode(',', $item->course_id)
                        : (array) $item->course_id,
                    'qr_name' => $item->qr_name,
                    'qr_code_link' => $item->qr_code_link,
                    'category_name' => optional($item->category)->name,
                ];
            });
        });
    }

    public function addMoreQR($classId, $mediumId)
    {
        try {
            // Handle null medium_id properly
            $mediumQuery = $mediumId === 'null' ? null : $mediumId;

            // Find the highest serial number for this class and medium combination
            $query = ModelsD2cDigitalContent::where('sub_category_id', $this->category_id)
                ->where('class_id', $classId);

            // Handle medium_id null check
            if (is_null($mediumQuery)) {
                $query->whereNull('medium_id');
            } else {
                $query->where('medium_id', $mediumQuery);
            }

            $maxSn = $query->max('sn') ?? 0;
            $newSn = $maxSn + 1;

            // Check if this combination already exists
            $existingQuery = ModelsD2cDigitalContent::where('category_id', $this->parent_category_id)
                ->where('sub_category_id', $this->category_id)
                ->where('class_id', $classId)
                ->where('sn', $newSn);

            if (is_null($mediumQuery)) {
                $existingQuery->whereNull('medium_id');
            } else {
                $existingQuery->where('medium_id', $mediumQuery);
            }

            if ($existingQuery->exists()) {
                $this->dispatch('notify', type: 'warning', message: 'This QR entry already exists!');
                return;
            }

            // Create new record with incremented SN
            ModelsD2cDigitalContent::create([
                'category_id' => $this->parent_category_id,
                'sub_category_id' => $this->category_id,
                'medium_id' => $mediumQuery,
                'class_id' => $classId,
                'sn' => $newSn,
                'course_id' => null,
                'created_by' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->dispatch('notify', type: 'success', message: 'New QR entry added successfully!');
            $this->loadInitialData();
        } catch (\Exception $e) {
            \Log::error('Error in addMoreQR: ' . $e->getMessage());
            $this->dispatch('notify', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    public function deleteQR($key)
    {
        try {
            $data = $this->existingData[$key] ?? null;
            if (!$data) {
                $this->dispatch('notify', type: 'error', message: 'Invalid data key');
                return;
            }

            // Delete the specific record with proper null handling
            $query = ModelsD2cDigitalContent::where('sub_category_id', $this->category_id)
                ->where('class_id', $data['class_id'])
                ->where('sn', $data['sn']);

            // Handle medium_id null check
            if (is_null($data['medium_id'])) {
                $query->whereNull('medium_id');
            } else {
                $query->where('medium_id', $data['medium_id']);
            }

            $query->delete();

            // Delete QR code file if exists
            if (!empty($data['qr_name'])) {
                Storage::disk('public')->delete('qrcodes/' . $data['qr_name']);
            }

            $this->dispatch('notify', type: 'success', message: 'QR entry deleted successfully!');
            $this->loadInitialData();
        } catch (\Exception $e) {
            \Log::error('Error in deleteQR: ' . $e->getMessage());
            $this->dispatch('notify', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    public function generateQrCode($key)
    {
        $this->generatingQrFor = $key;

        try {
            $data = $this->existingData[$key] ?? null;
            if (!$data) {
                $this->dispatch('notify', type: 'error', message: 'Invalid data key');
                return;
            }

            $classId = $data['class_id'] ?? null;
            $mediumId = $data['medium_id'] ?? null;
            $sn = $data['sn'] ?? 1;

            if (!$classId) {
                $this->dispatch('notify', type: 'error', message: 'Missing class ID');
                return;
            }

            $category = Category::where('status', 1)->where('parent_id', 1)
                ->where('id', $this->category_id)
                ->first();
            $class = Classes::find($classId);
            $medium = $mediumId ? Medium::find($mediumId) : null;

            if (!$category || !$class) {
                $this->dispatch('notify', type: 'error', message: 'Category or Class not found');
                return;
            }

            // Generate dynamic link WITH serial number
            $baseUrl = config('app.url');
            $categorySlug = Str::slug(Str::of($category->name)->replaceMatches('/[^a-zA-Z]/', '')->substr(0, 4));

            // Use URL-safe base64 encoding (replace / with _ and + with -)
            $classSlug = strtr(base64_encode($class->name), '+/', '-_');
            // Remove padding = signs
            $classSlug = rtrim($classSlug, '=');

            // // Include SN in the URL
            // $link = $medium
            //     ? "{$baseUrl}/{$categorySlug}/{$medium->name}/{$classSlug}/{$sn}"
            //     : "{$baseUrl}/{$categorySlug}/{$classSlug}/{$sn}";
            $link = $medium
                ? "{$baseUrl}/{$categorySlug}/{$medium->name}/{$classSlug}?msn={$sn}"
                : "{$baseUrl}/{$categorySlug}/{$classSlug}?msn={$sn}";
            // Generate QR PNG with logo and text
            $qrCode = QrCode::format('png')
                ->size(500)
                ->errorCorrection('H')
                ->generate($link);

            $qrImage = imagecreatefromstring($qrCode);
            $logoPath = public_path('frontend/images/mittlearn-logo.png');

            if (!file_exists($logoPath)) {
                throw new \Exception("Logo file not found");
            }

            $logo = imagecreatefrompng($logoPath);

            $qrWidth = imagesx($qrImage);
            $qrHeight = imagesy($qrImage);
            $logoWidth = imagesx($logo);
            $logoHeight = imagesy($logo);

            $newLogoWidth = $qrWidth * 0.4;
            $newLogoHeight = $logoHeight * ($newLogoWidth / $logoWidth);

            $resizedLogo = imagecreatetruecolor($newLogoWidth, $newLogoHeight);
            imagealphablending($resizedLogo, false);
            imagesavealpha($resizedLogo, true);

            imagecopyresampled($resizedLogo, $logo, 0, 0, 0, 0, $newLogoWidth, $newLogoHeight, $logoWidth, $logoHeight);

            $sharpenMatrix = [
                [-1, -1, -1],
                [-1, 16, -1],
                [-1, -1, -1]
            ];
            imageconvolution($resizedLogo, $sharpenMatrix, 8, 0);

            $padding = 6;
            $x = ($qrWidth - $newLogoWidth) / 2;
            $y = ($qrHeight - $newLogoHeight) / 2 - 25;

            $bgColor = imagecolorallocate($qrImage, 255, 255, 255);
            imagefilledrectangle(
                $qrImage,
                $x - $padding,
                $y - $padding,
                $x + $newLogoWidth + $padding,
                $y + $newLogoHeight + $padding + 30,
                $bgColor
            );

            imagecopy($qrImage, $resizedLogo, $x, $y, 0, 0, $newLogoWidth, $newLogoHeight);

            // Add class name and SN indicator
            $fontSize = 6;
            $textLines = [
                $class->name . ($sn > 1 ? " (Set {$sn})" : ''),
            ];
            $lineSpacing = 13;
            $startY = $y + $newLogoHeight + 4;
            $textColor = imagecolorallocate($qrImage, 0, 0, 0);

            foreach ($textLines as $index => $line) {
                $textWidth = imagefontwidth($fontSize) * strlen($line);
                $textX = ($qrWidth - $textWidth) / 2;
                $textY = $startY + ($index * $lineSpacing);
                imagestring($qrImage, $fontSize, $textX, $textY, $line, $textColor);
            }

            ob_start();
            imagepng($qrImage, null, 9);
            $mergedImage = ob_get_clean();

            imagedestroy($qrImage);
            imagedestroy($logo);
            imagedestroy($resizedLogo);

            $filename = "qrcode-{$this->category_id}-{$mediumId}-{$classId}-{$sn}-" . now()->timestamp . '.png';
            Storage::disk('public')->put("qrcodes/{$filename}", $mergedImage);

            // Save in DB with SN - using proper null handling
            $updateQuery = [
                'category_id' => $this->parent_category_id,
                'sub_category_id' => $this->category_id,
                'class_id' => $classId,
                'sn' => $sn
            ];

            // Handle medium_id null properly in the where clause
            if (is_null($mediumId)) {
                // For updateOrCreate, we need to specify the where conditions differently for null
                $record = ModelsD2cDigitalContent::where('category_id', $this->parent_category_id)
                    ->where('sub_category_id', $this->category_id)
                    ->where('class_id', $classId)
                    ->where('sn', $sn)
                    ->whereNull('medium_id')
                    ->first();

                if ($record) {
                    $record->update([
                        'qr_name' => $filename,
                        'qr_code_link' => $link,
                        'updated_at' => now()
                    ]);
                } else {
                    ModelsD2cDigitalContent::create([
                        'category_id' => $this->parent_category_id,
                        'sub_category_id' => $this->category_id,
                        'medium_id' => null,
                        'class_id' => $classId,
                        'sn' => $sn,
                        'qr_name' => $filename,
                        'qr_code_link' => $link,
                        'created_by' => Auth::id(),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            } else {
                $updateQuery['medium_id'] = $mediumId;
                ModelsD2cDigitalContent::updateOrCreate(
                    $updateQuery,
                    [
                        'qr_name' => $filename,
                        'qr_code_link' => $link,
                        'created_by' => Auth::id(),
                        'updated_at' => now()
                    ]
                );
            }

            $this->dispatch('notify', type: 'success', message: 'QR Code generated successfully!');
        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Error: ' . $e->getMessage());
        } finally {
            $this->generatingQrFor = null;
            $this->loadExistingContent();
        }
    }

    public function openAddCoursesModal($classId = null)
    {
        $this->currentClassId = $classId;

        $this->parentCategroy = Category::where('status', 1)->whereNull('parent_id')
            ->pluck('name', 'id')
            ->toArray();

        $this->d2cData = ModelsD2cDigitalContent::where('d2c_content_id', $this->category_id)
            ->when($classId, function ($query) use ($classId) {
                $query->where('class_id', $classId);
            }, function ($query) {
                $query->whereNull('class_id');
            })
            ->get();

        if ($this->d2cData->isNotEmpty()) {
            $allCourseIds = [];
            foreach ($this->d2cData as $d2c) {
                $courseIds = is_string($d2c->course_id)
                    ? explode(',', $d2c->course_id)
                    : (array) $d2c->course_id;

                $allCourseIds = array_merge($allCourseIds, $courseIds);
            }

            $this->selectedCoursesId = array_unique($allCourseIds);

            $firstRecord = $this->d2cData->first();

            $this->parent_id = optional($firstRecord->category->parent)->id;
            $this->loadSubcategories($this->parent_id);

            $this->sub_category_id = $firstRecord->category_id;
            $this->loadCourses($this->sub_category_id);
        } else {
            $this->reset([
                'parent_id',
                'sub_category_id',
                'subCategory',
                'subCategoryCourse',
                'selectedModalCourses',
            ]);
        }

        $this->showModal = true;
    }

    public function loadSubcategories($parentId)
    {
        $this->parentCategoryId = $parentId;
        $this->subCategory = Category::where('status', 1)->where('parent_id', $parentId)
            ->pluck('name', 'id')
            ->toArray();
        $this->reset(['subCategoryCourse', 'selectedModalCourses']);
    }

    public function loadCourses($categoryId)
    {
        if ($this->parentCategoryId != 2) {
            $this->subCategoryCourse = Course::with(['metadataValues' => function ($query) {
                $query->where('field_name', 'class')
                    ->where('field_value', $this->currentClassId);
            }])
                ->where('category_id', $this->parent_id)
                ->where('sub_category_id', $categoryId)
                ->whereHas('metadataValues', function ($query) {
                    $query->where('field_name', 'class')
                        ->where('field_value', $this->currentClassId);
                })
                ->pluck('course_name', 'id')
                ->toArray();
        } else {
            $this->subCategoryCourse = Course::where('category_id', $this->parent_id)
                ->where('sub_category_id', $categoryId)
                ->pluck('course_name', 'id')
                ->toArray();
        }
        $this->dispatch('coursesUpdated');
    }

    #[On('updateCourses')]
    public function updateSelectedCourses($selected)
    {
        $this->selectedModalCourses = $selected;
    }

    public function addCourses()
    {
        $where = [
            'd2c_content_id'  => $this->category_id,
            'category_id'     => $this->parent_id,
            'sub_category_id' => $this->sub_category_id,
            'class_id'        => $this->currentClassId,
        ];

        if (!empty($this->selectedModalCourses)) {
            ModelsD2cDigitalContent::updateOrCreate(
                $where,
                [
                    'course_id'  => implode(',', $this->selectedModalCourses),
                    'created_by' => Auth::id(),
                ]
            );
        } else {
            ModelsD2cDigitalContent::where($where)->delete();
        }

        return redirect()->to(request()->header('Referer'));
    }

    public function resetForm()
    {
        $this->loadInitialData();
    }

    public function render()
    {
        return view('livewire.d2-c-digital-content-act-worksheets');
    }
}
