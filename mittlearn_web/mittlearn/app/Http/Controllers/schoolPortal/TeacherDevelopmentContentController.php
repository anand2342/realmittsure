<?php

namespace App\Http\Controllers\schoolPortal;

use App\Http\Controllers\Controller;
use App\Models\TeacherDevelopmentContent;
use App\Models\TeacherDevelopmentVideo;
use Illuminate\Support\Facades\Auth;

class TeacherDevelopmentContentController extends Controller
{
    // =========================================================
    // INDEX — Show content folders assigned to this school
    // =========================================================
    public function index()
    {
        $role     = getUserRoles(); // 'school_admin' or 'school_teacher'
        $schoolId = $this->resolveSchoolId($role);

        $contents = TeacherDevelopmentContent::where('is_active', 1)
            ->where(function ($q) use ($schoolId) {
                // Either marked for ALL schools, OR specifically assigned to this school
                $q->where('is_for_all_schools', 1)
                    ->orWhereHas('schools', fn($q2) => $q2->where('schools.user_id', $schoolId));
            })
            ->withCount('videos')
            ->latest()
            ->get();

        return view('schoolPortal.teacherDevelopment.index', compact('contents'));
    }

    // =========================================================
    // VIEW VIDEOS — Videos inside a content (with access check)
    // =========================================================
    public function viewVideos($id)
    {
        $role     = getUserRoles();
        $schoolId = $this->resolveSchoolId($role);

        // Returns 404 automatically if this school has no access
        $content = TeacherDevelopmentContent::where('id', $id)
            ->where('is_active', 1)
            ->firstOrFail();

        $videos = TeacherDevelopmentVideo::where('content_id', $content->id)
            ->whereNotNull('video_file')
            ->orderBy('order', 'asc')   // ← only change needed
            ->get();

        return view('schoolPortal.teacherDevelopment.videos', compact('content', 'videos'));
    }

    // =========================================================
    // PRIVATE — Get school ID regardless of role
    // =========================================================
    private function resolveSchoolId(string $role): int
    {
        if ($role === 'school_teacher') {
            return Auth::user()->userAdditionalDetail->school_id;
        }

        // school_admin pattern matches your existing MediaContentController
        return Auth::id();
    }
}
