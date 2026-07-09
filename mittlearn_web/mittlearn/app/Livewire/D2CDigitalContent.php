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

class D2CDigitalContent extends Component
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

        $this->selectedClassIds = $this->classes->pluck('class_id')->toArray();

        // For each class, fetch courses linked to it via metadataValues
        foreach ($this->classes as $class) {
            $classId = (string) $class->class_id;
            $mediumId = $class->medium_id; // keep as-is, not casting to string

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
                            ->whereIn('field_value', [$mediumId, 0]); // fallback for generic content
                    });
                })
                ->pluck('course_name', 'id')
                ->toArray();
        }

        // dd($this->categoryCoursesPerClass);
        $this->loadExistingContent();
    }

    protected function loadExistingContent()
    {
        // Load data from both sources
        $subCategoryData = ModelsD2cDigitalContent::where('sub_category_id', $this->category_id)->get();
        $d2cContentData = ModelsD2cDigitalContent::where('d2c_content_id', $this->category_id)->get();
        $this->existingData = [];
        $this->d2cContentData = [];
        // Process sub_category_id data
        foreach ($subCategoryData as $item) {
            $classId = $item->class_id;
            $mediumId = $item->medium_id;

            // Only skip if class_id is null
            if (!$classId) continue;

            // Use "null" as string for key when medium is actually null
            $key = "{$classId}_" . ($mediumId !== null ? $mediumId : 'null');

            $this->existingData[$key] = [
                'class_id'      => $classId,
                'medium_id'     => $mediumId, // can be null
                'course_ids'    => is_string($item->course_id)
                    ? explode(',', $item->course_id)
                    : (array) $item->course_id,
                'qr_name'       => $item->qr_name,
                'qr_code_link'  => $item->qr_code_link,
            ];
        }


        // Process d2c_content_id data grouped by class_id
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

    // latest code for Live QRCODE Generate
    public function generateQrCode($key)
    {
        $this->generatingQrFor = $key;

        try {
            // Get the data from existing content using the key
            $data = $this->existingData[$key] ?? null;
            if (!$data) {
                $this->dispatch('notify', type: 'error', message: 'Invalid data key');
                return;
            }

            $classId = $data['class_id'] ?? null;
            $mediumId = $data['medium_id'] ?? null;

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

            // Generate dynamic link
            $baseUrl = config('app.url');
            $categorySlug = Str::slug(Str::of($category->name)->replaceMatches('/[^a-zA-Z]/', '')->substr(0, 4));
            $classSlug = base64_encode($class->name);

            // Corrected medium check - was using assignment (=) instead of comparison (==)
            $link = $medium
                ? "{$baseUrl}/{$categorySlug}/{$medium->name}/{$classSlug}"
                : "{$baseUrl}/{$categorySlug}/{$classSlug}";
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

            // Add class name (or any text)
            $fontSize = 6;
            $textLines = [
                $class->name,
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

            // Save to PNG
            ob_start();
            imagepng($qrImage, null, 9);
            $mergedImage = ob_get_clean();

            imagedestroy($qrImage);
            imagedestroy($logo);
            imagedestroy($resizedLogo);

            $filename = "qrcode-{$this->category_id}-{$mediumId}-{$classId}-" . now()->timestamp . '.png';
            Storage::disk('public')->put("qrcodes/{$filename}", $mergedImage);

            // Save in DB
            ModelsD2cDigitalContent::updateOrCreate(
                [
                    'category_id' => $this->parent_category_id,
                    'sub_category_id' => $this->category_id,
                    'medium_id' => $mediumId,
                    'class_id' => $classId
                ],
                [
                    'qr_name' => $filename,
                    'qr_code_link' => $link,
                    'updated_at' => now()
                ]
            );

            $this->dispatch('notify', type: 'success', message: 'QR Code generated successfully!');
        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Error: ' . $e->getMessage());
        } finally {
            $this->generatingQrFor = null;
            $this->loadExistingContent();
        }
    }

    public function generateQrCodeLocal($key)
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

            // Generate dynamic link
            $baseUrl = config('app.url');
            $categorySlug = Str::slug(Str::of($category->name)->replaceMatches('/[^a-zA-Z]/', '')->substr(0, 4));
            $classSlug = base64_encode($class->name);

            // Corrected medium check - was using assignment (=) instead of comparison (==)
            $link = $medium
                ? "{$baseUrl}/{$categorySlug}/{$medium->name}/{$classSlug}"
                : "{$baseUrl}/{$categorySlug}/{$classSlug}";

            $qrImage = QrCode::format('svg')
                ->size(200)
                ->generate($link);

            $filename = "qrcode-{$this->category_id}-{$classId}-" . ($medium ? "{$medium->id}-" : '') . now()->timestamp . '.svg';
            Storage::disk('public')->put("qrcodes/{$filename}", $qrImage);

            ModelsD2cDigitalContent::updateOrCreate(
                [
                    'category_id' => $this->parent_category_id,
                    'sub_category_id' => $this->category_id,
                    'class_id' => $classId,
                    'medium_id' => $mediumId
                ],
                [
                    'qr_name' => $filename,
                    'qr_code_link' => $link,
                    'updated_at' => now()
                ]
            );

            $this->dispatch('notify', type: 'success', message: 'QR Code generated successfully!');
        } finally {
            $this->generatingQrFor = null;
            $this->loadExistingContent();
        }
    }

    public function openAddCoursesModal($classId = null)
    {
        $this->currentClassId = $classId;

        // Load top-level parent categories
        $this->parentCategroy = Category::where('status', 1)->whereNull('parent_id')
            ->pluck('name', 'id')
            ->toArray();

        // Fetch all saved records matching criteria
        $this->d2cData = ModelsD2cDigitalContent::where('d2c_content_id', $this->category_id)
            ->when($classId, function ($query) use ($classId) {
                $query->where('class_id', $classId);
            }, function ($query) {
                $query->whereNull('class_id');
            })
            ->get();

        if ($this->d2cData->isNotEmpty()) {
            // You can now iterate $this->d2cData as a collection of ModelsD2cDigitalContent
            // For example, to fill multiple sets of data, or aggregate course IDs, etc.

            // Example: aggregate all course_ids across all records (explode and merge)
            $allCourseIds = [];
            foreach ($this->d2cData as $d2c) {
                $courseIds = is_string($d2c->course_id)
                    ? explode(',', $d2c->course_id)
                    : (array) $d2c->course_id;

                $allCourseIds = array_merge($allCourseIds, $courseIds);
            }

            $this->selectedCoursesId = array_unique($allCourseIds);

            // If you want to load subcategories and courses for the first record as default:
            $firstRecord = $this->d2cData->first();

            $this->parent_id = optional($firstRecord->category->parent)->id;
            $this->loadSubcategories($this->parent_id);

            $this->sub_category_id = $firstRecord->category_id;
            $this->loadCourses($this->sub_category_id);
        } else {
            // No saved data — reset all fields
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
        // Unique keys to find the record
        $where = [
            'd2c_content_id'  => $this->category_id,
            'category_id'     => $this->parent_id,
            'sub_category_id' => $this->sub_category_id,
            'class_id'        => $this->currentClassId,
        ];

        if (!empty($this->selectedModalCourses)) {
            // Save or update with courses
            ModelsD2cDigitalContent::updateOrCreate(
                $where,
                [
                    'course_id'  => implode(',', $this->selectedModalCourses),
                    'created_by' => Auth::id(),
                ]
            );
        } else {
            // Delete the row if exists and no courses selected
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
        return view('livewire.d2-c-digital-content');
    }
}
