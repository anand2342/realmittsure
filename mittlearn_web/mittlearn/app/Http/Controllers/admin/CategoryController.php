<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CourseMetadataField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public $data = [];

    public function index()
    {
        $this->data['categories'] = Category::getCategories();
        // return $this->data;
        return view('admin.categories.index', $this->data);
    }

    public function addFields($id)
    {
        $this->data['categories'] = Category::find($id);

        $this->data['metaDataFields'] = CourseMetadataField::where('category_slug', 'academic')
            ->get()
            ->unique('field_label')
            ->values();

        $this->data['existingFields'] = CourseMetadataField::where('category_id', $id)->get();
        $this->data['customFields'] = CourseMetadataField::where('category_id', $id)->orderBy('sort_order', 'asc')->get();

        $this->data['existingTemplateFields'] = $this->data['existingFields']
            ->filter(fn($field) => $this->data['metaDataFields']->contains('field_label', $field->field_label));

        $this->data['existingCustomFields'] = $this->data['customFields']
            ->reject(fn($field) => $this->data['metaDataFields']->contains('field_label', $field->field_label));

        return view('admin.categories.add-field', $this->data);
    }
    public function addShow()
    {
        $this->data['data'] = Category::where('parent_id', null)->where('is_default', 1)->with('children')->pluck('name', 'id');
        return view('admin.categories.add', $this->data);
    }
    public function save(Request $request)
    {
        $sanitizedData = $request->all();
        $validator = Validator::make($sanitizedData, [
            'name' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'icon' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $slug = generateUniqueSlug($request->name, Category::class, 'slug');

        $subCategory = new Category;
        $subCategory->name = $request->name;
        $subCategory->slug = $slug;
        $subCategory->description = $request->description;

        if ($request->hasFile('featured_image')) {
            $filename = time() . '.' . $request->file('featured_image')->getClientOriginalExtension();
            $path = 'uploads/categories/featuredImage/' . $filename;
            Storage::disk('public')->put($path, file_get_contents($request->file('featured_image')));
            $subCategory->featured_image = $filename;
        }

        if ($request->hasFile('icon')) {
            $filename = time() .  '.' . $request->file('icon')->getClientOriginalExtension();
            $path = 'uploads/categories/icon/' . $filename;
            Storage::disk('public')->put($path, file_get_contents($request->file('icon')));
            $subCategory->icon = $filename;
        }

        $subCategory->status = $request->status;
        $subCategory->parent_id = null;
        $subCategory->is_default = 1;

        if ($subCategory->save()) {
            return redirect()->route('category.index')->with('success', 'Your Data has been saved');
        } else {
            return redirect()->route('sub-category.add')->with('error', 'Something went wrong.');
        }
    }

    public function edit($id)
    {
        $subCategory = Category::findOrFail($id);
        $this->data['subCategory'] = $subCategory;
        $this->data['categoryHierarchy'] = $subCategory->parent_id ? $this->getCategoryHierarchy($subCategory->parent_id) : '';
        return view('admin.categories.edit', $this->data);
    }

    public function getCategoryHierarchy($categoryId)
    {
        $category = Category::find($categoryId);
        if (!$category) {
            return '';
        }
        $hierarchy = [];
        $hierarchy[] = $category->name;
        while ($category->parent_id) {
            $category = Category::find($category->parent_id);
            if ($category) {
                array_unshift($hierarchy, $category->name);
            } else {
                break;
            }
        }
        return implode(' / ', $hierarchy);
    }
    public function update(Request $request, $id)
    {
        $subCategory = Category::where('id', $request->id)->first();


        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'icon' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'status' => 'required',
        ]);
        $subCategory->name = $request->name;
        $subCategory->status = $request->status;
        $subCategory->description = $request->description;

        if ($request->hasFile('featured_image')) {
            $filename = time() . '.' . $request->file('featured_image')->getClientOriginalExtension();
            $path = 'uploads/categories/featuredImage/' . $filename;
            Storage::disk('public')->put($path, file_get_contents($request->file('featured_image')));
            $subCategory->featured_image = $filename;
        }
        if ($request->hasFile('icon')) {
            $filename = time() .  '.' . $request->file('icon')->getClientOriginalExtension();
            $path = 'uploads/categories/icon/' . $filename;
            Storage::disk('public')->put($path, file_get_contents($request->file('icon')));
            $subCategory->icon = $filename;
        }
        $subCategory->save();
        return redirect()->route('category.index')->with('success', 'Subcategory updated successfully');
    }
    public function destroy($id)
    {
        $subcategory = Category::with('subcategories')->findOrFail($id);
        $this->deleteSubcategoryRecursively($subcategory);
        return back()->with('success', 'Subcategory and related subcategories deleted successfully');
    }

    private function deleteSubcategoryRecursively($category)
    {
        foreach ($category->subcategories as $sub) {
            $this->deleteSubcategoryRecursively($sub);
        }
        $category->delete();
    }


    public function storeFormField(Request $request)
    {

        $request->validate([
            'category_id' => 'required|exists:categories,id',
        ]);

        // Start transaction for data consistency
        DB::beginTransaction();

        try {
            // First, delete all existing fields for this category
            CourseMetadataField::where('category_id', $request->category_id)->delete();

            // Handle selected template fields
            if ($request->has('selected_fields')) {
                foreach ($request->selected_fields as $fieldId) {
                    $templateField = CourseMetadataField::find($fieldId);
                    if ($templateField) {
                        CourseMetadataField::create([
                            'category_id' => $request->category_id,
                            'category_slug' => Str::slug(optional(Category::find($request->category_id))->name),
                            'field_name' => $templateField->field_name,
                            'field_label' => $templateField->field_label,
                            'field_type' => $templateField->field_type,
                            'lookup_with' => $templateField->lookup_with,
                            'field_options' => $templateField->field_options,
                            'field_description' => $templateField->field_description,
                            'field_validation_rules' => $templateField->field_validation_rules,
                            'default_value' => $templateField->default_value,
                            'field_placeholder' => $templateField->field_placeholder ?? null,
                            'is_active' => $templateField->is_active ?? 1,
                            'is_required' => $templateField->is_required ?? 0,
                            'sort_order' => $request->sort_order[$fieldId] ?? 0,
                        ]);
                    }
                }
            }

            // Handle new custom fields
            if ($request->has('new_fields')) {
                foreach ($request->new_fields as $newField) {
                    // Only create if required fields are present
                    if (!empty($newField['field_name']) && !empty($newField['field_label'])) {
                        CourseMetadataField::create([
                            'category_id' => $request->category_id,
                            'category_slug' => Str::slug(optional(Category::find($request->category_id))->name),
                            'field_name' => $newField['field_name'],
                            'field_label' => $newField['field_label'],
                            'field_type' => $newField['field_type'] ?? 'text',
                            'field_placeholder' => $newField['field_placeholder'] ?? null,
                            'is_active' => $newField['is_active'] ?? 1,
                            'is_required' => 0,
                            // 'sort_order' => $newField['sort_order'],
                            'sort_order' => $newField['sort_order'] ?? '0',
                        ]);
                    }
                }
            }

            DB::commit();
            return back()->with('success', 'Fields saved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to save fields: ' . $e->getMessage());
        }
    }
}
