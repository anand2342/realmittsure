<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Course;
use App\Models\D2cDigitalContent as ModelsD2cDigitalContent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TalentSkillDigitalContent extends Component
{
    public $sub_category_id;
    public $category_id = 2; // Talent-Skills parent is always id=2

    // Each row: ['course_ids' => [], 'qr_name' => null, 'qr_code_link' => null, 'db_id' => null]
    public $rows = [];

    public $availableCourses = []; // [id => course_name] for the select box
    public $generatingQrFor = null;

    public function mount($sub_category_id)
    {
        $this->sub_category_id = $sub_category_id;
        $this->loadAvailableCourses();
        $this->loadExistingRows();
    }

    protected function loadAvailableCourses()
    {
        $this->availableCourses = Course::where('sub_category_id', $this->sub_category_id)
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->pluck('course_name', 'id')
            ->toArray();
    }

    protected function loadExistingRows()
    {
        $records = ModelsD2cDigitalContent::where('category_id', $this->category_id)
            ->where('sub_category_id', $this->sub_category_id)
            ->whereNull('class_id')
            ->whereNull('medium_id')
            ->get();

        if ($records->isEmpty()) {
            // Start with one empty row
            $this->rows = [
                ['course_ids' => [], 'qr_name' => null, 'qr_code_link' => null, 'db_id' => null]
            ];
        } else {
            $this->rows = $records->map(function ($record) {
                return [
                    'db_id'        => $record->id,
                    'course_ids'   => $record->course_id
                        ? array_map('strval', explode(',', $record->course_id))
                        : [],
                    'qr_name'      => $record->qr_name,
                    'qr_code_link' => $record->qr_code_link,
                ];
            })->toArray();
        }
    }

    public function addRow()
    {
        $this->rows[] = [
            'course_ids'   => [],
            'qr_name'      => null,
            'qr_code_link' => null,
            'db_id'        => null,
        ];
    }

    public function removeRow($index)
    {
        $row = $this->rows[$index] ?? null;

        if ($row && $row['db_id']) {
            ModelsD2cDigitalContent::find($row['db_id'])?->delete();
        }

        array_splice($this->rows, $index, 1);

        if (empty($this->rows)) {
            $this->rows = [
                ['course_ids' => [], 'qr_name' => null, 'qr_code_link' => null, 'db_id' => null]
            ];
        }
    }

    public function saveRow($index)
    {
        $row = $this->rows[$index] ?? null;

        if (!$row || empty($row['course_ids'])) {
            $this->dispatch('notify', type: 'error', message: 'Please select at least one course.');
            return;
        }

        $courseIds = implode(',', $row['course_ids']);

        $record = ModelsD2cDigitalContent::updateOrCreate(
            ['id' => $row['db_id'] ?? null],
            [
                'category_id'    => $this->category_id,
                'sub_category_id' => $this->sub_category_id,
                'class_id'       => null,
                'medium_id'      => null,
                'course_id'      => $courseIds,
                'created_by'     => Auth::id(),
            ]
        );

        $this->rows[$index]['db_id'] = $record->id;

        $this->dispatch('notify', type: 'success', message: 'Row saved successfully.');
        $this->dispatch('reinitSelect2');
    }

    public function generateQrCode($index)
    {
        $this->generatingQrFor = $index;

        try {
            $row = $this->rows[$index] ?? null;

            if (!$row || empty($row['course_ids'])) {
                $this->dispatch('notify', type: 'error', message: 'Please select and save courses before generating QR.');
                return;
            }

            // Save first if not saved
            if (!$row['db_id']) {
                $this->saveRow($index);
                $row = $this->rows[$index];
            }

            $subCategory = Category::find($this->sub_category_id);
            if (!$subCategory) {
                $this->dispatch('notify', type: 'error', message: 'Sub-category not found.');
                return;
            }

            $baseUrl = config('app.url');

            // Build URL: /tands/{base64(sub_category_id)}/{base64(course_ids)}
            $encodedSubCat  = base64_encode((string) $this->sub_category_id);
            $encodedCourses = base64_encode(implode(',', $row['course_ids']));
            $link = "{$baseUrl}/tands/{$encodedSubCat}/{$encodedCourses}";

            // Generate QR PNG with logo
            $qrCode  = QrCode::format('png')->size(500)->errorCorrection('H')->generate($link);
            $qrImage = imagecreatefromstring($qrCode);
            $logoPath = public_path('frontend/images/mittlearn-logo.png');

            if (file_exists($logoPath)) {
                $logo      = imagecreatefrompng($logoPath);
                $qrWidth   = imagesx($qrImage);
                $qrHeight  = imagesy($qrImage);
                $logoWidth  = imagesx($logo);
                $logoHeight = imagesy($logo);

                $newLogoWidth  = $qrWidth * 0.4;
                $newLogoHeight = $logoHeight * ($newLogoWidth / $logoWidth);

                $resizedLogo = imagecreatetruecolor($newLogoWidth, $newLogoHeight);
                imagealphablending($resizedLogo, false);
                imagesavealpha($resizedLogo, true);
                imagecopyresampled($resizedLogo, $logo, 0, 0, 0, 0, $newLogoWidth, $newLogoHeight, $logoWidth, $logoHeight);

                $padding = 6;
                $x = ($qrWidth - $newLogoWidth) / 2;
                $y = ($qrHeight - $newLogoHeight) / 2;

                $bgColor = imagecolorallocate($qrImage, 255, 255, 255);
                imagefilledrectangle($qrImage, $x - $padding, $y - $padding, $x + $newLogoWidth + $padding, $y + $newLogoHeight + $padding, $bgColor);
                imagecopy($qrImage, $resizedLogo, $x, $y, 0, 0, $newLogoWidth, $newLogoHeight);

                imagedestroy($logo);
                imagedestroy($resizedLogo);
            }

            ob_start();
            imagepng($qrImage, null, 9);
            $mergedImage = ob_get_clean();
            imagedestroy($qrImage);

            $filename = "qrcode-tands-{$this->sub_category_id}-{$index}-" . now()->timestamp . '.png';
            Storage::disk('public')->put("qrcodes/{$filename}", $mergedImage);

            // Update DB record
            ModelsD2cDigitalContent::where('id', $row['db_id'])->update([
                'qr_name'      => $filename,
                'qr_code_link' => $link,
                'updated_at'   => now(),
            ]);

            $this->rows[$index]['qr_name']      = $filename;
            $this->rows[$index]['qr_code_link']  = $link;

            $this->dispatch('notify', type: 'success', message: 'QR Code generated successfully!');
        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Error: ' . $e->getMessage());
        } finally {
            $this->generatingQrFor = null;
            $this->loadExistingRows();
        }
    }

    public function render()
    {
        return view('livewire.talent-skill-digital-content');
    }
}
