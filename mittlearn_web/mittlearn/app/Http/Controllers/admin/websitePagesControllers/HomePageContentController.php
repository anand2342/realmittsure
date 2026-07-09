<?php

namespace App\Http\Controllers\admin\websitePagesControllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\HomePageContent;
use App\Models\MediaFiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\AdditionalDataRow; // Assuming you have a model for the additional_data_rows table

class HomePageContentController extends Controller
{
    public $data = [];
    public function homeContentAdd(Request $request)
    {
        $this->data['firstBanner'] = HomePageContent::where('section_name', 'first_banner')->first();
        $this->data['firstBannerAddtional'] = AdditionalDataRow::where('type',  $this->data['firstBanner']->section_name)->get();
        $this->data['coreFeatureBanner'] = HomePageContent::where('section_name', 'feature_banner')->first();
        $this->data['coreAcademicFeatureAddtional'] = AdditionalDataRow::where('type', 'academic_feature_banner')->get();
        $this->data['coreNonAcademicFeatureAddtional'] = AdditionalDataRow::where('type', 'non_academic_feature_banner')->get();
        $this->data['instructorBanner'] = HomePageContent::where('section_name', 'instructor_banner')->first();
        $this->data['testimonialBanner'] = HomePageContent::where('section_name', 'testimonial_banner')->first();
        $this->data['categories'] = Category::where('status', 1)->where('parent_id', '')->pluck('name', 'id')->toArray();
        $this->data['nonAcademicCategory'] = Category::where('status', 1)->where('parent_id', 2)->pluck('name', 'id')->toArray();
        return view('admin.websitePages.home-content-page', $this->data);
    }



    public function homeContentSave(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'heading' => 'required|string|max:255',
            'instructor_title' => 'nullable|string|max:50',
            'instructor_description' => 'nullable|string',
            'heading_1' => 'nullable|string|max:255',
            'sub_heading_1' => 'nullable|string|max:255',
        ]);
        if ($request->section_name_1 === 'first_banner') {
            // Save or update the first banner section
            $homeContentFirstBanner = HomePageContent::updateOrCreate(
                ['id' => 1],
                [
                    'section_name' => $request->section_name_1,
                    'heading' => $request->heading,
                ]
            );

            // Process each row from the request
            // dd($request->rows);
            foreach ($request->rows as $index => $row) {
                $id = $row['id'] ?? null;
                $groupId = $row['group_id'] ?? null;
                $title = $row['group_academic_title'] ?? null;
                $redirectionLink = $row['redirection_link'] ?? null;
                $image = $row['group_academic_image'] ?? null;

                $filename = null;
                if ($image instanceof \Illuminate\Http\UploadedFile && $image->isValid()) {
                    $filename = time() . "_feature_{$index}." . $image->getClientOriginalExtension();
                    Storage::disk('public')->put(
                        'uploads/website-pages/academic/' . $filename,
                        file_get_contents($image)
                    );
                } elseif ($image) {
                    // If image exists but is not a valid UploadedFile
                    return back()->with('error', "Image in row " . ($index + 1) . " is not valid or failed to upload.");
                }
                $homeAdditional = AdditionalDataRow::where('type', 'first_banner')->where('id', $id)->first();
                // Update or create the row in AdditionalDataRow
                AdditionalDataRow::updateOrCreate(
                    ['type' => 'first_banner', 'id' => $id],
                    [
                        'model_id' => $groupId,
                        'title' => $title,
                        'description' => $redirectionLink,
                        'image' => $filename ?? $homeAdditional->image, // New image or keep existing
                    ]
                );
            }
        }



        if ($request->section_name_2 === 'feature_banner') {
            // Find or create the HomePageContent entry for the feature banner section
            $homeContentFeatureBanner = HomePageContent::find(2) ?? new HomePageContent(['id' => 2]);
            $homeContentFeatureBanner->section_name = $request->section_name_2;
            $homeContentFeatureBanner->core_title = $request->core_title;
            $homeContentFeatureBanner->core_heading = $request->core_heading;
            $homeContentFeatureBanner->save();


            foreach ($request->rows_1 as $index => $row) {
                if ($request->type_1 === 'academic_feature_banner') {
                    $iconId = $row['id'];
                    $iconTitle = $row['icon_title'];
                    $iconDescription = $row['icon_description'];

                    $iconImage = isset($row['icon_image']) ? $row['icon_image'] : null;

                    $imagePath = null;
                    if ($iconImage) {
                        $filename = time() . "_feature_{$index}." . $iconImage->getClientOriginalExtension();
                        $imagePath = Storage::disk('public')->put('uploads/website-pages/core_icon_image/' . $filename, file_get_contents($iconImage));
                    }

                    $existingRow = AdditionalDataRow::where('type', 'academic_feature_banner')
                        ->where('id', $iconId)
                        ->first();
                    if ($existingRow) {
                        if ($imagePath) {
                            $existingRow->image = $filename; // Save new image path if provided
                        }
                        $existingRow->description = $iconDescription ?? $existingRow->description;
                        $existingRow->title = $iconTitle; // Update the title if necessary
                        $existingRow->save();
                    } else {
                        AdditionalDataRow::create([
                            'type' => 'academic_feature_banner',
                            'title' => $iconTitle,
                            'description' => $iconDescription ?? null,
                            'image' => $filename ?? null, // Save the image if it's provided
                        ]);
                    }
                }
            }

            foreach ($request->rows_2 as $index => $row) {
                if ($request->type_2 === 'non_academic_feature_banner') {
                    $iconId = $row['id'];
                    $iconTitle = $row['icon_title'];
                    $iconDescription = $row['icon_description'];

                    $iconImage = isset($row['icon_image']) ? $row['icon_image'] : null;

                    $imagePath = null;
                    if ($iconImage) {
                        $filename = time() . "_feature_{$index}." . $iconImage->getClientOriginalExtension();
                        $imagePath = Storage::disk('public')->put('uploads/website-pages/non_academic_core_icon_image/' . $filename, file_get_contents($iconImage));
                    }

                    $existingRow = AdditionalDataRow::where('type', 'non_academic_feature_banner')
                        ->where('id', $iconId)
                        ->first();

                    if ($existingRow) {
                        if ($imagePath) {
                            $existingRow->image = $filename; // Save new image path if provided
                        }
                        $existingRow->description = $iconDescription ?? $existingRow->description;
                        $existingRow->title = $iconTitle; // Update the title if necessary
                        $existingRow->save();
                    } else {
                        AdditionalDataRow::create([
                            'type' => 'non_academic_feature_banner',
                            'title' => $iconTitle,
                            'description' => $iconDescription ?? null,
                            'image' => $filename ?? null, // Save the image if it's provided
                        ]);
                    }
                }
            }



            if ($request->section_name_3 === 'instructor_banner') {
                $homeContentInstructorBanner = HomePageContent::find(3) ?? new HomePageContent(['id' => 3]);
                $homeContentInstructorBanner->section_name = $request->section_name_3;
                $homeContentInstructorBanner->instructor_title = $request->instructor_title;
                $homeContentInstructorBanner->instructor_description = $request->instructor_description;
                $homeContentInstructorBanner->save();
            }

            if ($request->section_name_4 === 'testimonial_banner') {
                $homeContentTestimonialBanner = HomePageContent::find(4) ?? new HomePageContent(['id' => 4]);
                $homeContentTestimonialBanner->section_name = $request->section_name_4;
                $homeContentTestimonialBanner->heading_1 = $request->heading_1;
                $homeContentTestimonialBanner->sub_heading_1 = $request->sub_heading_1;
                $homeContentTestimonialBanner->save();
            }


            return back()->with(['success' => config('constants.FLASH_REC_ADD_1')]);
        }
    }
}
