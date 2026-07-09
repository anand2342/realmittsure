<?php

namespace App\Console\Commands;

use App\Services\DataSyncService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RemoveDuplicateFiles extends Command
{
    protected $signature   = 'remove:files';
    protected $description = 'Sync data from ERP to LMS and log to erp_sync_lms';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $duplicates = DB::table('media_files')
            ->select('attachment_file')
            ->groupBy('attachment_file')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('attachment_file');

        foreach ($duplicates as $filename) {
            $files = DB::table('media_files')
                ->where('attachment_file', $filename)
                ->orderBy('id')
                ->get();

            // Skip the first one, keep its name as-is
            $files->skip(1)->each(function ($fileRecord, $index) use ($filename) {
                $oldPath = 'uploads/course_chapter_files/' . $filename;

                if (Storage::disk('public')->exists($oldPath)) {
                    $extension = pathinfo($filename, PATHINFO_EXTENSION);
                    $baseName = pathinfo($filename, PATHINFO_FILENAME);
                    $newName = $baseName . '-' . Str::random(5) . '.' . $extension;
                    $newPath = 'uploads/course_chapter_files/' . $newName;

                    // Rename file in storage
                    Storage::disk('public')->move($oldPath, $newPath);

                    // Update DB
                    DB::table('media_files')
                        ->where('id', $fileRecord->id)
                        ->update(['attachment_file' => $newName]);
                }
            });
            $this->info('User synchronization completed.');
        }
    }
}
