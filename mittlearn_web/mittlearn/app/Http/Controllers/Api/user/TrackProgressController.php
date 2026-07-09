<?php
namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\Api\BaseController;
use App\Models\TrackUserVideoProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TrackProgressController extends BaseController
{
    public $res = [];
    public function saveVideoDuration(Request $request)
    {
        try {
            $request->validate([
                'video_id'       => 'required|integer',
                'course_id'      => 'required|integer',
                'chapter_id'     => 'required|integer',
                'video_duration' => 'required|numeric',
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

            return $this->sendSuccess([], config('constants.API_MSG.REC_ADD_SUCCESS'));
        } catch (ValidationException $e) {
            return $this->sendError(config('constants.API_MSG.VALIDATION_ERROR'), $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 500);
        }
    }

    public function updateVideoProgress(Request $request)
    {
        try {
            $request->validate([
                'video_id'         => 'required|integer',
                'watched_duration' => 'required|numeric',
            ]);

            $progress = TrackUserVideoProgress::where('user_id', Auth::id())
                ->where('video_id', $request->video_id)
                ->first();

            if ($progress) {
                if ($progress->watched_duration >= $progress->video_duration) {
                    return $this->sendSuccess([], 'Video already completed, progress not updated');
                }

                if ($request->watched_duration > $progress->watched_duration) {
                    $progress->update([
                        'watched_duration' => $request->watched_duration,
                    ]);
                }
            } else {
                TrackUserVideoProgress::create([
                    'user_id'          => Auth::id(),
                    'video_id'         => $request->video_id,
                    'watched_duration' => $request->watched_duration,
                    'video_duration'   => null,
                ]);
            }

            return $this->sendSuccess([], config('constants.API_MSG.REC_ADD_SUCCESS'));
        } catch (ValidationException $e) {
            return $this->sendError(config('constants.API_MSG.VALIDATION_ERROR'), $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 500);
        }
    }

}
