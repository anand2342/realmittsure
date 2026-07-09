<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AdditionalDataRow;
use App\Models\Category;
use App\Models\CmsAboutUs;
use App\Models\CmsPage;
use App\Models\Faq;
use App\Models\MediaFolder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CmsPageController extends Controller
{
    public $data = [];
    public function index()
    {
        $this->data['getData'] = CmsPage::paginate(config('constants.PAGINATION.default'));
        return view('admin.cms.cmsPage.index', $this->data);
    }

    public function add()
    {
        return view('admin.cms.cmsPage.add_edit');
    }

    public function save(Request $request)
    {
        if ($request->id > 0) {
            $success = config('constants.FLASH_REC_UPDATE_1');
            $error = config('constants.FLASH_REC_UPDATE_0');
        } else {
            $success = config('constants.FLASH_REC_ADD_1');
            $error = config('constants.FLASH_REC_ADD_0');
        }
        $data = $request->except('_token');
        $rules = [
            'title' => "required|max:255|regex:/\A(?!.*[:;]-\))[ -~]+\z/|unique:cms_pages,title,{$request->id}",
            'name' => 'required|regex:/\A(?!.*[:;]-\))[ -~]+\z/|max:255',
            'description' => 'required',
            'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:2048',
        ];
        $validator = Validator::make($data, $rules, [
            'description.regex' => 'The description format is invalid.',
            'name.regex' => 'The name format is invalid.',
        ]);
        // dd($validator);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $data['slug'] = Str::slug($data['title'], '-');
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $folderPath = 'uploads/cms_pages/';
            Storage::disk('public')->put($folderPath . $fileName, file_get_contents($file));
            $data['image'] = $fileName;
        }
        $cms = CmsPage::updateOrCreate(
            ['id' => $request->id],
            $data
        );

        return redirect()->back()->with($cms ? ['success' => $success] : ['error' => $error]);
    }

    public function edit($id)
    {
        $this->data['cms'] = CmsPage::findOrFail($id);
        return view('admin.cms.cmsPage.add_edit', $this->data);
    }

    public function delete($id)
    {
        $cms = CmsPage::findOrFail($id);
        dd($cms);
        $cms->delete();
        return redirect()->route('cms-faq.index')->with('success', config('constants.FLASH_REC_DELETE_1'));
    }

    public function faqIndex()
    {
        $perPageRecords = Session::get('per_page_records', config('constants.PAGINATION.default'));

        $this->data['getData'] = Faq::paginate($perPageRecords);
        return view('admin.cms.cmsFaq.index', $this->data);
    }

    public function faqAdd()
    {
        return view('admin.cms.cmsFaq.add_edit');
    }

    public function faqSave(Request $request)
    {
        if ($request->id > 0) {
            $success = config('constants.FLASH_REC_UPDATE_1');
            $error = config('constants.FLASH_REC_UPDATE_0');
        } else {
            $success = config('constants.FLASH_REC_ADD_1');
            $error = config('constants.FLASH_REC_ADD_0');
        }
        $request->validate([
            'sort_order' => 'required|numeric',
            'question' => 'required|string|max:255',
            'answer' => 'required|string|max:1000',
            'is_active' => 'required',
        ]);
        $cms = Faq::updateOrCreate(
            ['id' => $request->id],
            $request->except('_token')
        );

        return redirect()->back()->with($cms ? ['success' => $success] : ['error' => $error]);
    }

    public function faqEdit($id)
    {
        $this->data['cms'] = Faq::findOrFail($id);
        return view('admin.cms.cmsFaq.add_edit', $this->data);
    }
    public function faqDelete($id)
    {
        $cms = Faq::findOrFail($id);
        $cms->delete();
        return redirect()->route('cms-faq.index')->with('success', config('constants.FLASH_REC_DELETE_1'));
    }
    public function aboutUsIndex()
    {
        $this->data['categories'] = Category::where('status', 1)->where('parent_id', '2')->pluck('name', 'id');
        $this->data['leaders'] = User::whereHas('userRoles', function ($query) {
            $query->where('role_slug', 'leaders');
        })->pluck('name', 'id');
        $this->data['data'] = CmsAboutUs::first();

        $this->data['glance'] = json_decode($this->data['data']->at_glance, true);
        // return $this->data['glance'];
        $this->data['mittsure_section'] = json_decode($this->data['data']->mittsure_section, true);
        $this->data['leadership'] = json_decode($this->data['data']->leadership, true);

        $this->data['programs'] = AdditionalDataRow::where('type', 'our_program')->get();
        // return $this->data['programs'];
         $this->data['activitiesGallaryImages'] = MediaFolder::with('mediaFolderFiles')->where('parent_id', 612)->where('folder_name', 'About Us Our Activities')->first();

        return view('admin.cms.about-us.index', $this->data);
    }

    public function aboutUsSave(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'title' => 'required|string',
            'banner_description' => 'required|string',
            'mittsure_description' => 'required|string',
            'button' => 'required|string',
            'mittsure_section_description' => 'required|string',
            'versatile_activities_description' => 'required|string',
            'versatile_activities' => 'required|string',
            'user_id_primary' => 'required',
            'user_id_secondary' => 'required',
            'user_id_third' => 'required',
            'our_leadership_description' => 'required',
            'vision_description' => 'required|string',
            'about_vision' => 'required|string',
            'program_description' => 'required|string',
            'type' => 'required|string',

        ]);
        $saveImage = function ($file, $path) {
            if ($file) {
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . preg_replace('/\s+/', '_', $originalName);
                $fullPath = 'uploads/about-us/' . $path . '/' . $fileName;
                Storage::disk('public')->put($fullPath, file_get_contents($file));
                return $fullPath;
            }
            return null;
        };

        // Retrieve the existing record
        $cmsAboutUs = CmsAboutUs::find(1);
        $atGlanceImg = json_decode($cmsAboutUs->at_glance, true);
        $mittsureSectionImg = json_decode($cmsAboutUs->mittsure_section, true);
        // At a Glance Section
        $atGlance = [
            'mittsure_at_glance_description' => $request->mittsure_description ?? $cmsAboutUs->at_glance['mittsure_at_glance_description'] ?? null,
            'glance_image' => $request->file('glance_image')
                ? $saveImage($request->file('glance_image'), 'glance_image')
                : ($cmsAboutUs->at_glance['glance_image'] ?? $atGlanceImg['glance_image']),
            'button' => $request->button ?? $cmsAboutUs->at_glance['button'] ?? null,
        ];

        // Mittsure Section
        $mittsureSection = [
            'mittsure_section_description' => $request->mittsure_section_description ?? $cmsAboutUs->mittsure_section['mittsure_section_description'] ?? null,
            'mittsure_section_image' => $request->file('mittsure_section_image')
                ? $saveImage($request->file('mittsure_section_image'), 'mittsure_section')
                : ($cmsAboutUs->mittsure_section['mittsure_section_image'] ?? $mittsureSectionImg['mittsure_section_image']),
        ];

        // Leadership Section
        $leadership = [
            'our_leadership_description' => $request->our_leadership_description ?? $cmsAboutUs->leadership['our_leadership_description'] ?? null,
            'primary' => $request->user_id_primary ?? $cmsAboutUs->leadership['primary'] ?? null,
            'secondary' => $request->user_id_secondary ?? $cmsAboutUs->leadership['secondary'] ?? null,
            'third' => $request->user_id_third ?? $cmsAboutUs->leadership['third'] ?? null,
            'fourth' => $request->user_id_fourth ?? $cmsAboutUs->leadership['fourth'] ?? null,
            'fifth' => $request->user_id_fifth ?? $cmsAboutUs->leadership['fifth'] ?? null,
            'sixth' => $request->user_id_sixth ?? $cmsAboutUs->leadership['sixth'] ?? null,
        ];

        foreach ($request->row as $index => $row) {
            if ($request->type === 'our_program') {
                $iconId = $row['id'];
                $iconTitle = $row['title'];
                $iconDescription = $row['description'];
                $iconUrlRedirection = $row['url_redirection'];

                $iconImage = isset($row['image']) ? $row['image'] : null;

                $imagePath = null;
                if ($iconImage) {
                    $filename = time() . "_program_{$index}." . $iconImage->getClientOriginalExtension();
                    $imagePath = Storage::disk('public')->put('uploads/cms-about-us/our-program/' . $filename, file_get_contents($iconImage));
                }

                $existingRow = AdditionalDataRow::where('type', 'our_program')
                    ->where('id', $iconId)
                    ->first();
                if ($existingRow) {
                    if ($imagePath) {
                        $existingRow->image = $filename; // Save new image path if provided
                    }
                    $existingRow->description = $iconDescription ?? $existingRow->description;
                    $existingRow->url_redirection = $iconUrlRedirection ?? $existingRow->url_redirection;
                    $existingRow->title = $iconTitle; // Update the title if necessary
                    $existingRow->save();
                } else {
                    AdditionalDataRow::create([
                        'type' => 'our_program',
                        'title' => $iconTitle,
                        'description' => $iconDescription ?? null,
                        'url_redirection' => $iconUrlRedirection ?? null,
                        'image' => $filename ?? null, // Save the image if it's provided
                    ]);
                }
            }
        }

        // Programs Section
        // $programs = [];
        // for ($i = 1; $i <= 3; $i++) {
        //     $existingProgram = $cmsAboutUs->programs[$i - 1] ?? [];
        //     $programs[] = [
        //         'title' => $request->input("program_{$i}_title", $existingProgram['title'] ?? null),
        //         'description' => $request->input("program_{$i}_description", $existingProgram['description'] ?? null),
        //         'image' => $request->file("program_{$i}_banner_image")
        //             ? $saveImage($request->file("program_{$i}_banner_image"), 'programs')
        //             : ($existingProgram['image'] ?? null),
        //     ];
        // }

        // Vision Image
        $visionImage = $request->file('vision_image')
            ? $saveImage($request->file('vision_image'), 'vision')
            : ($cmsAboutUs->vision_image ?? null);

        // Banner Image
        $bannerImage = $request->file('banner_image')
            ? $saveImage($request->file('banner_image'), 'banner')
            : ($cmsAboutUs->banner_image ?? null);

        // Save or Update
        CmsAboutUs::updateOrCreate(
            ['id' => 1],
            [
                'title' => $request->title ?? $cmsAboutUs->title,
                'banner_description' => $request->banner_description ?? $cmsAboutUs->banner_description,
                'versatile_activities_description' => $request->versatile_activities_description ?? $cmsAboutUs->versatile_activities_description,
                'versatile_activities' => $request->versatile_activities ?? $cmsAboutUs->versatile_activities,
                'category_id' => isset($request->category_id) && is_array($request->category_id)
                    ? implode(',', $request->category_id)
                    : ($cmsAboutUs->category_id ?? null),
                'mittsure_section' => json_encode($mittsureSection),
                'at_glance' => json_encode($atGlance),
                'leadership' => json_encode($leadership),
                'vision_description' => $request->vision_description ?? $cmsAboutUs->vision_description,
                'vision_image' => $visionImage,
                'about_vision' => $request->about_vision ?? $cmsAboutUs->about_vision,
                'program_description' => $request->program_description ?? $cmsAboutUs->program_description,
                // 'programs' => json_encode($programs),
                'banner_image' => $bannerImage,
                'created_at' => $cmsAboutUs->created_at ?? now(),
                'updated_at' => now(),
            ]
        );

        return redirect()->back()->with('success', config('constants.FLASH_REC_UPDATE_1'));
    }

    public function ourOfferingsAdd(Request $request)
    {
        // $this->data['firstBannerAddtional'] = AdditionalDataRow::where('type',  $this->data['firstBanner']->section_name)->get();
        $this->data['ourOfferingsAddtional'] = AdditionalDataRow::where('type', 'our_offerings')->orderBy('sort_order', 'asc')->get();
        return view('admin.websitePages.ourOfferings.our-offerings-page', $this->data);
    }

    public function ourOfferingsSave(Request $request)
    {
        // dd($request->all());
        if ($request->section_name_1 === 'our_offerings') {

            foreach ($request->rows as $index => $row) {
                $linkAndDesc = json_encode([
                    'redirection_link' => $row['redirection_link'] ?? null,
                    'ourOfferings_desc' => $row['ourOfferings_desc'] ?? null
                ]);
                $id = $row['id'] ?? null;
                $title = $row['our_offerings_title'] ?? null;
                // $redirectionLink = $row['redirection_link'] ?? null;
                $image = $row['our_offerings_image'] ?? null;

                $filename = null;
                if ($image) {
                    $filename = time() . "_feature_{$index}." . $image->getClientOriginalExtension();
                    Storage::disk('public')->put(
                        'uploads/website-pages/our-offerings/' . $filename,
                        file_get_contents($image)
                    );
                }
                $ourOfferings = AdditionalDataRow::where('type', 'our_offerings')->where('id', $id)->first();
                // Update or create the row in AdditionalDataRow
                AdditionalDataRow::updateOrCreate(
                    ['type' => 'our_offerings', 'id' => $id],
                    [
                        'title' => $title,
                        'description' => $linkAndDesc,
                        'sort_order' => $row['our_offerings_sort_order'] ?? null,
                        'image' => $filename ?? $ourOfferings->image, // New image or keep existing

                    ]
                );
            }
            return back()->with(['success' => config('constants.FLASH_REC_ADD_1')]);
        }
    }
}
