<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogToCategories;
use App\Models\MediaFiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public $data = [];

    public function blogCreate()
    {
        $this->data['categories'] = BlogCategory::where('parent_id', null)->pluck('name', 'id');
        return view('admin.blog.create', $this->data);
    }

    public function getBlogSubcategories($categoryId)
    {
        $subcategories = BlogCategory::where('parent_id', $categoryId)->pluck('name', 'id');
        return response()->json($subcategories);
    }

    public function blogShow(Request $request)
    {
        // $data = Blog::paginate(10);
        $perPageRecords = Session::get('per_page_records', config('constants.PAGINATION.default'));

        $data = Blog::orderBy('updated_at', 'DESC')->paginate($perPageRecords);
        return view('admin.blog.index', ['datas' => $data]);
    }
    public function blogEdit($id)
    {
        $this->data['blog'] = Blog::find($id);
        $assignedCategories = BlogToCategories::where('blog_id', $id)->pluck('category_id')->toArray();
        $mainCategory = BlogCategory::whereIn('id', $assignedCategories)
            ->where('parent_id', null)
            ->first();
        $subcategory = BlogCategory::whereIn('id', $assignedCategories)
            ->whereNotNull('parent_id')
            ->first();
        $this->data['categories'] = BlogCategory::where('parent_id', null)->pluck('name', 'id'); // Main categories
        $this->data['sub_categories'] = $mainCategory ? BlogCategory::where('parent_id', $mainCategory->id)->pluck('name', 'id') : []; // Subcategories
        $this->data['category_id'] = $mainCategory ? $mainCategory->id : null;
        $this->data['subcategory_id'] = $subcategory ? $subcategory->id : null;
        $this->data['featured_image'] = MediaFiles::where('type', 'blog')->where('tbl_id', $id)->first();
        return view('admin.blog.create', $this->data);
    }

    public function blogSave(Request $request)
    {
        $request->validate([
            'categories' => 'required|string|max:255',
            'featured_image' => $request->has('id') ? 'nullable|mimes:png,pdf,svg,jpeg,jpg' : 'required|mimes:png,pdf,svg,jpeg,jpg',
            'status' => 'required|in:draft,published,archived',
            'title' => 'required|string|max:255',
        ]);

        // Validate subcategory only if the main category has children
        $sub_categories = BlogCategory::where('parent_id', $request->categories)->first();
        if ($sub_categories) {
            $request->validate([
                'subcategories' => 'required',
            ]);
        }

        $isUpdate = $request->id > 0;
        $success = $isUpdate ? config('constants.FLASH_REC_UPDATE_1') : config('constants.FLASH_REC_ADD_1');
        $error = $isUpdate ? config('constants.FLASH_REC_UPDATE_0') : config('constants.FLASH_REC_ADD_0');

        $blog = MediaFiles::where('tbl_id', $request->id)->where('type', 'blog')->first();

        if ($request->hasFile('featured_image')) {
            $blogImage = $request->file('featured_image');
            $original_name = $blogImage->getClientOriginalName();
            $extension = $blogImage->getClientOriginalExtension();

            $featured_image = time() . '.' . $extension;
            $fileSize = $blogImage->getSize();
            $mimeType = $blogImage->getMimeType();

            Storage::disk('public')->put('uploads/blog/' . $featured_image, file_get_contents($blogImage));
        } else {
            $featured_image = $blog->attachment_file ?? null;
            $original_name = $blog->original_name ?? null;
            $extension = $blog->file_extension ?? null;
            $fileSize = $blog->file_size ?? null;
            $mimeType = $blog->mime_type ?? null;
        }

        // Ensure unique slug
        $slug = generateUniqueSlug($request->title, Blog::class, 'slug', $request->id);

        // Prepare data
        $data = $request->except(['_token', 'featured_image']);
        $data['author_id'] = Auth::id();
        $data['slug'] = $slug;
        $data['featured_image'] = $featured_image;

        // --- 🕒 Set published_at only when status is "published"
        if ($request->status === 'published') {
            $data['published_at'] = now();
        } elseif ($isUpdate && $request->status === 'draft') {
            // Keep previously published_at if republished later
            $existingBlog = Blog::find($request->id);
            $data['published_at'] = $existingBlog->published_at;
        }

        // Save blog
        $res = Blog::updateOrCreate(['id' => $request->id], $data);

        // Update or create media file
        MediaFiles::updateOrCreate(
            ['tbl_id' => $res->id, 'type' => 'blog'],
            [
                'attachment_file' => $featured_image,
                'original_name' => $original_name,
                'file_extension' => $extension,
                'file_size' => $fileSize,
                'mime_type' => $mimeType,
                'uploaded_by' => Auth::id(),
            ]
        );

        // Update categories
        BlogToCategories::where('blog_id', $res->id)->delete();
        BlogToCategories::create(['blog_id' => $res->id, 'category_id' => $request->categories]);
        if ($request->filled('subcategories')) {
            BlogToCategories::create(['blog_id' => $res->id, 'category_id' => $request->subcategories]);
        }

        return $res
            ? redirect()->route('blog.index')->with(['success' => $success])
            : redirect()->back()->with(['error' => $error]);
    }

    public function blogDelete($id)
    {

        $data = Blog::where('id', $id)->first();
        $data->delete();

        return redirect()->back()->with('success', config('constants.FLASH_REC_DELETE_1'));
    }
    public function blogCategoryShow(Request $request)
    {
        $this->data['datas'] = BlogCategory::where('parent_id', null)->paginate(10);
        return view('admin.blog.blog_category_index', $this->data);
    }
    public function showSubcategories(Request $request)
    {
        if ($request->ajax()) {
            $category_id = $request->input('category_id');
            $subCategories = BlogCategory::where('parent_id', $category_id)->get();
            return response()->json($subCategories);
        }
    }

    public function blogCategorySave(Request $request)
    {
        $request->validate([
            'category' => 'required|string',
        ]);
        $name = $request->category;
        $request->merge([
            'name' => $name,
            'slug' => Str::slug($name, '-'),
        ]);

        $category = BlogCategory::updateOrCreate(['id' => $request->id], $request->except(['_token', 'category', 'sub_category']));

        if (isset($request->sub_category_names) && isset($request->sub_category_ids)) {
            foreach ($request->sub_category_ids as $index => $subCatId) {
                $subCatName = $request->sub_category_names[$index]; // Get the corresponding name
                BlogCategory::updateOrCreate(
                    ['id' => $subCatId], // Match by ID
                    ['name' => $subCatName, 'slug' => Str::slug($subCatName, '-'), 'parent_id' => $category->id] // Update fields
                );
            }
        }
        return redirect()->route('blog.category.index');
    }

    public function blogCategoryEdit(Request $request, $id)
    {
        $this->data['category'] = BlogCategory::find($id);
        $this->data['subCategory'] = BlogCategory::where('parent_id', $id)->get(['id', 'name']);
        return view('admin.blog.blog_category_create', $this->data);
    }

    public function blogCategoryDelete($id)
    {
        $data = BlogCategory::where('id', $id)->first();
        $data->delete();
        return redirect()->route('blog.category.index');
    }

    public function blogSubCategoryCreate($id)
    {
        $category = BlogCategory::where('id', $id)->first();
        $sub_category = BlogCategory::where('parent_id', $id)->get();
        return view('admin.blog.blog_sub_category_create', [
            'category' => $category,
            'sub_category' => $sub_category,
        ]);
    }

    public function blogSubCategoryStore(Request $request)
    {
        $category_name = $request->category_name;
        $category = BlogCategory::where('name', $category_name)->first();
        $sub_categories = $request->input('sub_category');
        foreach ($sub_categories as $sub_category) {
            if ($sub_category != null) {
                BlogCategory::create(['parent_id' => $category->id, 'name' => $sub_category]);
            }
        }
        return redirect()->route('blog.category.index');
    }
}
