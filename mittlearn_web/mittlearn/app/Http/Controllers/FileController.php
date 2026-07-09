<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TrackUserVideoProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class FileController extends Controller
{
    public $data = [];
    public $res  = [];
    public static function sendOnSignedRoute($file)
    {
        return URL::signedRoute('stream.video', [
            'file' => $file,
        ], now()->addSeconds(3600)); // 1 hour
    }
    public function streamVideo(Request $request, $file)
    {
        if (! $request->hasValidSignature()) {
            return response()->view('errors.access-denied-403', [], 403);
        }
        // File path
        $path = "uploads/course_chapter_files/$file";
        if (! Storage::disk('public')->exists($path)) {
            abort(404, 'File not found');
        }
        $fileContent = Storage::disk('public')->get($path);

        return Response::make($fileContent, 200, [
            'Content-Type'        => Storage::disk('public')->mimeType($path),
            'Content-Disposition' => 'inline',
            'Cache-Control'       => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma'              => 'no-cache',
        ]);
    }

    public static function saveUserVideoDurationOnPageLoad($data)
    {
        foreach ($data as $chapter) {
            if (! empty($chapter['chapters'])) { // Ensure there are chapters
                foreach ($chapter['chapters'] as $video) {
                    if (! is_null($video['video_duration'])) { // Check if video_duration is not null
                        TrackUserVideoProgress::updateOrCreate(
                            [
                                'user_id'  => Auth::id(),
                                'video_id' => $video['id'],
                            ],
                            [
                                'course_id'      => $chapter['course_id'],
                                'chapter_id'     => $chapter['id'],
                                'video_duration' => $video['video_duration'],
                            ]
                        );
                    }
                }
            }
        }

        return true; // Return success status
    }

    public function saveUserVideoDuration(Request $request)
    {
        $request->validate([
            'video_id'       => 'required',
            'video_duration' => 'required',
        ]);

        TrackUserVideoProgress::updateOrCreate(
            [
                'user_id'  => Auth::id(),
                'video_id' => $request->video_id,
            ],
            [
                'course_id'      => $request->course_id,
                'chapter_id'     => $request->chapter_id,
                'video_duration' => $request->video_duration,
            ]
        );

        return response()->json(['message' => 'Video duration saved']);
    }

    public function saveUserVideoProgress(Request $request)
    {
        $request->validate([
            'video_id'         => 'required',
            'watched_duration' => 'required',
        ]);
        $progress = TrackUserVideoProgress::where('user_id', Auth::id())
            ->where('video_id', $request->video_id)
            ->first();
        if ($progress) {
            // If the video is already fully watched, do not update
            if ($progress->watched_duration >= $progress->video_duration) {
                return response()->json(['message' => 'Video already completed, progress not updated']);
            }

            // Only update if the new watched duration is greater than the last recorded duration
            if ($request->watched_duration > $progress->watched_duration) {
                $progress->update([
                    'watched_duration' => $request->watched_duration,
                ]);
            }
        } else {
            // First time watching
            TrackUserVideoProgress::create([
                'user_id'          => Auth::id(),
                'video_id'         => $request->video_id,
                'watched_duration' => $request->watched_duration,
                'video_duration'   => null,
            ]);
        }

        return response()->json(['message' => 'Progress updated']);
    }

}
