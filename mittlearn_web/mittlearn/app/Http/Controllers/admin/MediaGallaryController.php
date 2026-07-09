<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AccessCode;
use App\Models\BookSeries;
use App\Models\Classes;
use App\Models\MediaFiles;
use App\Models\MediaFolder;
use App\Models\Role;
use App\Models\SchoolAssignedDigitalContent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class MediaGallaryController extends Controller
{
	public $data = [];

	public function folderList(Request $request)
	{
		$perPageRecords = Session::get('per_page_records', config('constants.PAGINATION.default'));

		$this->data['folderListing'] = MediaFolder::withCount('fileCount')->where('is_mittlearn_folder', 1)->where('folder_name', '!=', 'About Us Our Activities')->paginate($perPageRecords);
		$this->data['classCourses'] = AccessCode::with('class')
			->select('class_id')
			->where('school_id', Auth::id())
			->groupBy('class_id')
			->get();
		$this->data['classList'] = Classes::where('is_active', 1)->pluck('name', 'id')->toArray();
		$this->data['seriesList'] = BookSeries::where('is_active', 1)->pluck('name', 'id')->toArray();
		$this->data['roleList'] = Role::where('is_active', 1)->whereIn('role_slug', ['school_admin', 'school_teacher'])->pluck('role_name', 'role_slug')->toArray();

		return view('admin.mediaGallery.index', $this->data);
	}


	// public function mediaGalleryFolderView($id)
	// {
	// 	try {
	// 		$this->data['folder'] = MediaFolder::where('parent_id', Auth::id())->find($id);
	// 		$this->data['contentFolderView'] = MediaFiles::where('type', 'content_upload')->where('tbl_id', $id)->get();

	// 		return view('admin.mediaGallery.folder_files_view', $this->data);
	// 	} catch (\Exception $e) {
	// 		return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
	// 	}
	// }


	public function mediaGalleryFolderView($id)
	{
		try {
			$this->data['folder'] = MediaFolder::where('is_mittlearn_folder', 1)->find($id);
			$this->data['contentFolderView'] = MediaFiles::where('type', 'content_upload')->where('tbl_id', $id)->get();

			$folder = $this->data['folder'];
			// dd($folder);

			$this->data['assignedRoles'] = $folder->distribute_role_slug
				? explode(',', $folder->distribute_role_slug)
				: [];

			if ($folder->distribute_schools === 'all') {
				$this->data['assignedSchools'] = User::whereHas('userRole', function ($q) {
					$q->where('role_slug', 'school_admin');
				})->pluck('name', 'id')->toArray();
			} else {
				$schoolIds = $folder->distribute_schools
					? explode(',', $folder->distribute_schools)
					: [];

				$this->data['assignedSchools'] = User::whereIn('id', $schoolIds)
					->pluck('name', 'id')
					->toArray();
			}

			if ($folder->distribute_teachers === 'all') {
				$this->data['assignedTeachers'] = User::whereHas('userRole', function ($q) {
					$q->where('role_slug', 'school_teacher');
				})->pluck('name', 'id')->toArray();
			} else {
				$teacherIds = $folder->distribute_teachers
					? explode(',', $folder->distribute_teachers)
					: [];

				$this->data['assignedTeachers'] = User::whereIn('id', $teacherIds)
					->pluck('name', 'id')
					->toArray();
			}

			$seriesIds = $folder->distribute_series_ids
				? explode(',', $folder->distribute_series_ids)
				: [];

			$this->data['assignedSeries'] = BookSeries::whereIn('id', $seriesIds)
				->pluck('name', 'id')
				->toArray();

			return view('admin.mediaGallery.folder_files_view', $this->data);
		} catch (\Exception $e) {
			// dd($e);
			return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
		}
	}





	public function contentUpload(Request $request)
	{
		$this->data['folderListing'] = MediaFolder::withCount('fileCount')->get();
		$this->data['classCourses'] = AccessCode::with('class')
			->select('class_id')
			->where('school_id', Auth::id())
			->groupBy('class_id')
			->get();
		return view('schoolPortal.media_content.content_upload', $this->data);
	}
	public function createFolder(Request $request)
	{
		$request->validate([
			// "class_id" => "required",
			"folder_name" => "required",
		]);
		try {
			$folder = new MediaFolder;
			$folder->folder_name = $request->folder_name;
			$folder->class_id = $request->class_id;
			$folder->folder_color = $request->folder_color ?? '#dbf8ea';
			$folder->parent_id = Auth::id();
			$folder->folder_icon = 'frontend/images/folder-yellow.svg';
			$folder->is_mittlearn_folder = 1;
			$folder->save();
			if ($folder) {
				return redirect()->back()->with(['success' => config('constants.FLASH_REC_ADD_1')]);
			}
		} catch (\Exception $e) {
			return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
		}
		return view('schoolPortal.content_upload');
	}
	public function contentFolderView($id)
	{
		try {
			$this->data['folder'] = MediaFolder::find($id);
			$this->data['contentFolderView'] = MediaFiles::where('type', 'content_upload')->where('tbl_id', $id)->get();

			return view('schoolPortal.media_content.content_folder_view', $this->data);
		} catch (\Exception $e) {
			return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
		}
	}

	public function storeFile(Request $request)
	{
		$maxFileSize = config('constants.MAX_FILE_SIZE');

		$request->validate([
			'file' => "required|file|mimes:jpg,jpeg,png,bmp,gif,svg,doc,docx,xls,xlsx,pdf,mp3,wav,mp4,mov,avi|max:$maxFileSize",
		]);

		try {
			$file = $request->file('file');
			$extension = $file->getClientOriginalExtension();

			// Check if the file is an image before compressing
			$imageMimeTypes = ['jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'];
			if (in_array($extension, $imageMimeTypes)) {
				// Compress the image
				$compressedImagePath = storage_path('app/public/uploads/media-files/' . time() . '.' . $extension);
				compressImage($file, $compressedImagePath);

				$fileName = basename($compressedImagePath); // Extract file name after compression
				$path = 'uploads/media-files/' . $fileName;
			} else {
				// For non-image files, upload without compression
				$fileName = time() . '.' . $extension;
				$path = Storage::disk('public')->put('uploads/media-files/' . $fileName, file_get_contents($file));
			}

			if ($path) {
				$mediaFile = new MediaFiles();
				$mediaFile->tbl_id = $request->folder_id;
				$mediaFile->type = 'content_upload';
				$mediaFile->attachment_file = $fileName;
				$mediaFile->original_name = $file->getClientOriginalName();
				$mediaFile->file_extension = $extension;
				$mediaFile->file_size = $file->getSize();
				$mediaFile->mime_type = $file->getMimeType();
				$mediaFile->uploaded_by = Auth::id();
				$mediaFile->save();

				return redirect()->back()->with(['success' => config('constants.FLASH_REC_ADD_1')]);
			} else {
				return redirect()->back()->with(['error' => config('constants.FLASH_REC_ADD_0')]);
			}
		} catch (\Exception $e) {
			// dd($e);
			return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
		}
	}

	public function mediaGalleryDelete($id)
	{
		try {
			$folder = MediaFolder::find($id);

			if (!$folder) {
				return redirect()->back()->with('error', 'Folder not found');
			}

			$fileName = MediaFiles::where('tbl_id', $folder->id)->where('type', 'content_upload')->first();
			if ($fileName) {
				if (Storage::disk('public')->exists('uploads/media-files/' . $fileName->attachment_file)) {
					Storage::disk('public')->delete('uploads/media-files/' . $fileName->attachment_file);
				}
				$fileName->delete();
			}
			$folder->delete();
			return redirect()->back()->with(['success' => config('constants.FLASH_REC_DELETE_1')]);
		} catch (\Exception $e) {
			return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
		}
	}
	public function classContentFolder()
	{
		return view('schoolPortal.class_content_folder');
	}

	public function fileDelete($id)
	{
		try {
			$file = MediaFiles::where('id', $id)->where('type', 'content_upload')->first();

			if ($file) {
				if (Storage::disk('public')->exists('uploads/media-files/' . $file->attachment_file)) {
					Storage::disk('public')->delete('uploads/media-files/' . $file->attachment_file);
				}
				$file->delete();

				return redirect()->back()->with(['success' => config('constants.FLASH_REC_DELETE_1')]);
			} else {
				return redirect()->back()->with(['error' => config('constants.FLASH_REC_DELETE_0')]);
			}
		} catch (\Exception $e) {
			return redirect()->back()->with('error', config('constants.FLASH_TRY_CATCH'));
		}
	}

	// public function mediaGalleryDistribute(Request $request)
	// {
	// 	$request->validate([
	// 		'media_id' => 'required|exists:media_folders,id',
	// 		'series' => 'nullable|array',
	// 		'series.*' => 'exists:book_series,id',
	// 		'roles' => 'nullable|array',
	// 		'roles.*' => 'string', // assuming role slug
	// 	]);

	// 	$mediaId = $request->media_id;
	// 	$series = $request->input('series', []);
	// 	$roles = $request->input('roles', []);

	// 	$mediaDistribute = MediaFolder::find($mediaId);

	// 	if (!$mediaDistribute) {
	// 		return redirect()->back()->with('error', 'Media folder not found.');
	// 	}

	// 	try {
	// 		// Convert arrays to comma-separated strings
	// 		$mediaDistribute->distribute_series_ids = implode(',', $series);
	// 		$mediaDistribute->distribute_role_slug = implode(',', $roles);
	// 		$schoolList = $request->school_admin_list ?? [];
	// 		$teacherList = $request->school_teacher_list ?? [];
	// 		if (in_array('school_admin', $roles)) {
	// 			$mediaDistribute->distribute_schools =
	// 				empty($schoolList) ? "all" : implode(',', $schoolList);
	// 		} else {
	// 			$mediaDistribute->distribute_schools = null;
	// 		}

	// 		if (in_array('school_teacher', $roles)) {
	// 			$mediaDistribute->distribute_teachers =
	// 				empty($teacherList) ? "all" : implode(',', $teacherList);
	// 		} else {
	// 			$mediaDistribute->distribute_teachers = null;
	// 		}
	// 		$mediaDistribute->is_mittlearn_folder = 1;
	// 		$mediaDistribute->save();

	// 		return redirect()->back()->with('success', 'Media distributed successfully.');
	// 	} catch (\Exception $e) {
	// 		return redirect()->back()->with('error', 'Failed to distribute media: ' . $e->getMessage());
	// 	}
	// }

	public function mediaGalleryDistribute(Request $request)
	{
		$request->validate([
			'media_id' => 'required|exists:media_folders,id',
			'series'   => 'nullable|array',
			'series.*' => 'exists:book_series,id',
			'roles'    => 'nullable|array',
			'roles.*'  => 'string',
		]);

		$mediaId = $request->media_id;
		$newSeries = $request->input('series', []);
		$newRoles  = $request->input('roles', []);

		$mediaDistribute = MediaFolder::find($mediaId);

		if (!$mediaDistribute) {
			return redirect()->back()->with('error', 'Media folder not found.');
		}

		try {
			// ── Merge Series ──────────────────────────────────────────────────
			$existingSeries = $mediaDistribute->distribute_series_ids
				? explode(',', $mediaDistribute->distribute_series_ids)
				: [];
			$mergedSeries = array_unique(array_filter(array_merge($existingSeries, $newSeries)));
			$mediaDistribute->distribute_series_ids = implode(',', $mergedSeries);

			// ── Merge Roles ───────────────────────────────────────────────────
			$existingRoles = $mediaDistribute->distribute_role_slug
				? explode(',', $mediaDistribute->distribute_role_slug)
				: [];
			$mergedRoles = array_unique(array_filter(array_merge($existingRoles, $newRoles)));
			$mediaDistribute->distribute_role_slug = implode(',', $mergedRoles);

			// ── Merge Schools (school_admin) ──────────────────────────────────
			if (in_array('school_admin', $newRoles)) {
				$schoolInput = $request->school_admin_list ?? [];

				// "all" selected — extract every real ID sent (filter out the "all" sentinel)
				if (in_array('all', $schoolInput)) {
					$schoolInput = array_filter($schoolInput, fn($v) => $v !== 'all');
				}

				$existingSchools = $mediaDistribute->distribute_schools
					? explode(',', $mediaDistribute->distribute_schools)
					: [];

				$merged = array_unique(array_filter(array_merge($existingSchools, $schoolInput)));
				$mediaDistribute->distribute_schools = implode(',', $merged);
			}

			// ── Merge Teachers (school_teacher) ───────────────────────────────
			if (in_array('school_teacher', $newRoles)) {
				$teacherInput = $request->school_teacher_list ?? [];

				if (in_array('all', $teacherInput)) {
					$teacherInput = array_filter($teacherInput, fn($v) => $v !== 'all');
				}

				$existingTeachers = $mediaDistribute->distribute_teachers
					? explode(',', $mediaDistribute->distribute_teachers)
					: [];

				$merged = array_unique(array_filter(array_merge($existingTeachers, $teacherInput)));
				$mediaDistribute->distribute_teachers = implode(',', $merged);
			}

			$mediaDistribute->is_mittlearn_folder = 1;
			$mediaDistribute->save();

			return redirect()->back()->with('success', 'Media distributed successfully.');
		} catch (\Exception $e) {
			return redirect()->back()->with('error', 'Failed to distribute media: ' . $e->getMessage());
		}
	}

	public function getUserToAssignDeck(Request $request)
	{
		$role = $request->role;
		$seriesIds = $request->series ?? [];
		$response = ["data" => []];
		if ($role == "school_admin") {
			$schoolIds = SchoolAssignedDigitalContent::whereIn('series_id', $seriesIds)
				->pluck('school_id')
				->unique()
				->toArray();

			$admins = User::whereHas('userRole', function ($q) {
				$q->where('role_slug', 'school_admin');
			})->whereHas('userAdditionalDetail', function ($q) use ($schoolIds) {
				$q->whereIn('school_id', $schoolIds);
			})->get(['id', 'name']);

			$response["data"] = $admins;
		}

		if ($role == "school_teacher") {
			$schoolIds = SchoolAssignedDigitalContent::whereIn('series_id', $seriesIds)
				->pluck('school_id')
				->unique()
				->toArray();

			$teachers = User::whereHas('userRole', function ($q) {
				$q->where('role_slug', 'school_teacher');
			})->whereHas('userAdditionalDetail', function ($q) use ($schoolIds) {
				$q->whereIn('school_id', $schoolIds);
			})->get(['id', 'name']);

			$response["data"] = $teachers;
		}

		return response()->json([
			"status" => true,
			"data" => $response
		]);
	}


	public function removeAssignedRole($id, $role)
	{
		$folder = MediaFolder::findOrFail($id);
		$roles = explode(',', $folder->distribute_role_slug ?? '');
		$roles = array_filter($roles); // clean empty values
		$roles = array_diff($roles, [$role]);
		$folder->distribute_role_slug = implode(',', $roles);
		$folder->save();
		return back()->with('success', 'Role removed successfully.');
	}


	public function removeAssignedSchool($id, $schoolId)
	{
		$folder = MediaFolder::findOrFail($id);
		if ($folder->distribute_schools === "all") {
			return back()->with('error', 'Cannot remove school when ALL schools are assigned.');
		}
		$schools = explode(',', $folder->distribute_schools ?? '');
		$schools = array_filter($schools);
		$schools = array_diff($schools, [$schoolId]);
		$folder->distribute_schools = implode(',', $schools);
		$folder->save();
		return back()->with('success', 'School removed successfully.');
	}

	public function removeAssignedTeacher($id, $teacherId)
	{
		$folder = MediaFolder::findOrFail($id);
		if ($folder->distribute_teachers === "all") {
			return back()->with('error', 'Cannot remove teacher when ALL teachers are assigned.');
		}
		$teachers = explode(',', $folder->distribute_teachers ?? '');
		$teachers = array_filter($teachers);
		$teachers = array_diff($teachers, [$teacherId]);
		$folder->distribute_teachers = implode(',', $teachers);
		$folder->save();
		return back()->with('success', 'Teacher removed successfully.');
	}

	public function removeAssignedSeries($id, $seriesId)
	{
		$folder = MediaFolder::findOrFail($id);
		$series = explode(',', $folder->distribute_series_ids ?? '');
		$series = array_filter($series);
		$series = array_diff($series, [$seriesId]);
		$folder->distribute_series_ids = implode(',', $series);
		$folder->save();
		return back()->with('success', 'Series removed successfully.');
	}
}
