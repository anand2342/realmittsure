<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MediaFiles;

class ChapterFileSortOrderEditor extends Component
{
    public $file;
    public $sortOrder;
    public $video_view_type;
    public $file_name;
    public $isEditing = false;
    public $language;

    public function mount($file)
    {
        $this->file = $file;
        $this->sortOrder = $file->sort_order;
        $this->video_view_type = $file->video_view_type;
        $this->file_name = $file->file_name;
        $this->language = $file->language ?? ''; // fallback

    }

    public function updateSortOrder()
    {
        $sortOrder = $this->sortOrder;
        $fileName = $this->file_name;
        $videoViewType = $this->video_view_type;
        $language = $this->language;
        $fileId = $this->file->id;

        // Ensure sort_order is unique in the same chapter
        while (
            MediaFiles::where('id', $fileId)
            ->where('sort_order', $sortOrder)
            ->where('id', '!=', $this->file->id)
            ->exists()
        ) {
            $sortOrder++;
        }

        $this->file->update([
            'sort_order' => $sortOrder,
            'file_name' => $fileName,
            'video_view_type' => $videoViewType,
            'language' => $language,
        ]);

        $this->sortOrder = $sortOrder;
        $this->isEditing = false;

        return redirect()->to(request()->header('Referer'))->with('success', "Update successful! 🚀");
    }
    public function render()
    {
        return view('livewire.chapter-file-sort-order-editor');
    }
}
