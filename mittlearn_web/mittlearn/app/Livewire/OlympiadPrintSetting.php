<?php

namespace App\Livewire;

use App\Models\Setting;
use Livewire\Component;

class OlympiadPrintSetting extends Component
{
    // Paper Settings
    public $settings;
    public $paper_size, $orientation;
    public $margin_top, $margin_bottom, $margin_left, $margin_right;

    // Block/Grid Settings
    public $blocks_per_row, $blocks_per_column, $blocks_width, $blocks_height, $block_padding, $block_border;

    // Text Settings
    public $font_family, $font_size, $text_align;

    public $showPreview = false;
    public $olympiadSetting = [
        'paper_size' => '',
        'orientation' => '',
        'margin_top' => '',
        'margin_bottom' => '',
        'margin_left' => '',
        'margin_right' => '',
        'blocks_per_row' => '',
        'blocks_per_column' => '',
        'blocks_width' => '',
        'blocks_height' => '',
        'block_padding' => '',
        'block_border' => false,
        'font_family' => '',
        'font_size' => '',
        'text_align' => '',
        'custom_width' => '',
        'custom_height' => '',
    ];
    public $custom_width;
    public $custom_height;

    public function mount()
    {
        $savedSettings = Setting::whereIn('field_name', array_keys($this->olympiadSetting))
            ->pluck('field_value', 'field_name')
            ->toArray();

        foreach ($savedSettings as $fieldName => $fieldValue) {
            if ($fieldName === 'block_border') {
                $this->olympiadSetting[$fieldName] = (bool) $fieldValue;
            } else {
                $this->olympiadSetting[$fieldName] = $fieldValue;
            }
        }

        // Sync olympiadSetting to individual fields
        $this->paper_size = $this->olympiadSetting['paper_size'];
        $this->orientation = $this->olympiadSetting['orientation'];
        $this->margin_top = $this->olympiadSetting['margin_top'];
        $this->margin_bottom = $this->olympiadSetting['margin_bottom'];
        $this->margin_left = $this->olympiadSetting['margin_left'];
        $this->margin_right = $this->olympiadSetting['margin_right'];
        $this->blocks_per_row = $this->olympiadSetting['blocks_per_row'];
        $this->blocks_per_column = $this->olympiadSetting['blocks_per_column'];
        $this->blocks_width = $this->olympiadSetting['blocks_width'];
        $this->blocks_height = $this->olympiadSetting['blocks_height'];
        $this->block_padding = $this->olympiadSetting['block_padding'];
        $this->block_border = $this->olympiadSetting['block_border'];
        $this->font_family = $this->olympiadSetting['font_family'];
        $this->font_size = $this->olympiadSetting['font_size'];
        $this->text_align = $this->olympiadSetting['text_align'];
        $this->custom_width = $this->olympiadSetting['custom_width'] ?? '';
        $this->custom_height = $this->olympiadSetting['custom_height'] ?? '';
    }
    public function handlePaperSizeChange()
    {
        if ($this->paper_size === 'custom') {
            $this->custom_width = 210;
            $this->custom_height = 297;
        }
    }
    public function previewSettings()
    {
        $this->validate([
            'paper_size' => 'required',
            'orientation' => 'required',
            'margin_top' => 'required|numeric',
            'margin_bottom' => 'required|numeric',
            'margin_left' => 'required|numeric',
            'margin_right' => 'required|numeric',

            'blocks_per_row' => 'required|numeric|min:1',
            'blocks_per_column' => 'required|numeric|min:1',
            'blocks_width' => 'required|numeric|min:1',
            'blocks_height' => 'required|numeric|min:1',
            'block_padding' => 'required|numeric|min:0',

            'font_family' => 'required',
            'font_size' => 'required|numeric|min:1',
            'text_align' => 'required',
        ]);
        if ($this->paper_size === 'Custom') {
            $this->validate([
                'custom_width' => 'required|numeric|min:1',
                'custom_height' => 'required|numeric|min:1',
            ]);
        }

        $this->dispatch('show-preview-modal');
    }
    public function saveSettings()
    {
        $validatedData = $this->validate([
            'paper_size' => 'required',
            'orientation' => 'required',
            'margin_top' => 'required|numeric',
            'margin_bottom' => 'required|numeric',
            'margin_left' => 'required|numeric',
            'margin_right' => 'required|numeric',
            'blocks_per_row' => 'required|numeric|min:1',
            'blocks_per_column' => 'required|numeric|min:1',
            'blocks_width' => 'required|numeric|min:1',
            'blocks_height' => 'required|numeric|min:1',
            'block_padding' => 'required|numeric|min:0',
            'block_border' => 'required|boolean',
            'font_family' => 'required',
            'font_size' => 'required|numeric|min:1',
            'text_align' => 'required',
        ]);
        if ($this->paper_size === 'Custom') {
            $validatedData['custom_width'] = $this->custom_width;
            $validatedData['custom_height'] = $this->custom_height;
        }

        try {
            foreach ($validatedData as $fieldName => $fieldValue) {
                Setting::updateOrInsert(
                    ['field_name' => $fieldName],
                    ['field_value' => $fieldValue]
                );
            }

            return redirect(request()->header('Referer'))->with('success', 'Settings saved successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to save settings. Please try again.');
        }
    }



    public function closePreview()
    {
        $this->showPreview = false;
    }



    public function render()
    {
        return view('livewire.olympiad-print-setting');
    }
}
