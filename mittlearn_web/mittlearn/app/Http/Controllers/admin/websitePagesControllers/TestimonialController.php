<?php

namespace App\Http\Controllers\admin\websitePagesControllers;

use App\Http\Controllers\Controller;
use App\Models\MediaFiles;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TestimonialController extends Controller
{
    public $data = [];
    public function testimonialContentindex()
    {
        $perPageRecords = Session::get('per_page_records', config('constants.PAGINATION.default'));

        $this->data['data'] = Testimonial::orderBy('id', 'desc')->paginate($perPageRecords);
        return view('admin.websitePages.testimonial.index', $this->data);
    }
    public function testimonialContentAdd(Request $request)
    {
        return view('admin.websitePages.testimonial.add');
    }

    public function testimonialContentSave(Request $request)
    {
        if ($request->id > 0) {
            $success = config('constants.FLASH_REC_UPDATE_1');
            $error = config('constants.FLASH_REC_UPDATE_0');
        } else {
            $success = config('constants.FLASH_REC_ADD_1');
            $error = config('constants.FLASH_REC_ADD_0');
        }
        $userId = Auth::user()->id;
        $request->validate([
            'comment' => 'required|string',
            'name' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif',
            'designation' => 'required|string',
        ]);
        $data = $request->except(['_token']);
        $filename = null;
        if ($request->hasFile('image')) {
            $existingImage = Testimonial::where('id', $request->id)->first();
            if ($existingImage && Storage::disk('public')->exists('uploads/testimonial-profile/' . $existingImage->image)) {
                Storage::disk('public')->delete('uploads/testimonial-profile/' . $existingImage->image);
            }
            $profileImage = $request->file('image');
            $extension = $profileImage->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            Storage::disk('public')->put('uploads/testimonial-profile/' . $filename, file_get_contents($profileImage));
            $data['image'] = $filename;
        }
        $homeContent = Testimonial::find($request->id);
        if ($homeContent) {
            $data = array_merge($homeContent->toArray(), $data);
        } else {
            $data = array_merge([], $data);
        }
        $homeContent = Testimonial::updateOrCreate(
            ['id' => $request->id],
            $data
        );
        if ($filename) {
            MediaFiles::updateOrCreate(
                [
                    'tbl_id' => $homeContent->id,
                    'type' => 'Testimonial',
                ],
                [
                    'attachment_file' => $filename,
                    'original_name' => $profileImage->getClientOriginalName(),
                    'file_extension' => $extension,
                    'file_size' => $profileImage->getSize(),
                    'mime_type' => $profileImage->getMimeType(),
                    'uploaded_by' => $userId,
                ]
            );
        }

        return redirect()->route('testimonial.index')->with(['success' => $success]);
    }

    public function testimonialContentEdit(Request $request, $id)
    {
        $data = Testimonial::find($id);
        if (! $data) {
            return redirect()->route('home.testimonial.index');
        }
        // return $data;
        $this->data['data'] = $data;
        return view('admin.websitePages.testimonial.add', $this->data);
    }
    public function testimonialDelete($id)
    {
        $data = Testimonial::where('id', $id)->first();
        $data->delete();
        return redirect()->route('testimonial.index')->with(['success' => config('constants.FLASH_REC_DELETE_1')]);
    }
}
