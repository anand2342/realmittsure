<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name', 'description', 'featured_image', 'icon', 'parent_id', 'status'];

    // protected static function boot()
    // {
    //     parent::boot();
    //     static::saving(function ($category) {
    //         if (empty($category->slug)) {
    //             $category->slug = Str::slug($category->name, '-');
    //         }
    //     });
    // }

    public function subcategories()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->with('children');
    }

    public static function getCategories($parentId = null)
    {
        return self::with('children')->where('parent_id', $parentId)->orderBy('id', 'desc')->paginate(config('constants.PAGINATION.default'));
    }
    public static function getAllCategories($parentId = null)
    {
        return self::with(['children' => function ($query) {
            $query->where('status', 1);
        }])
            ->where('parent_id', $parentId)
            ->where('status', 1)
            ->get();
    }

    public function countSubcategories()
    {
        return $this->children()->count();
    }
}
