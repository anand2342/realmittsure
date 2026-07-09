<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    use HasFactory;
    protected $table = "blog_categories"; 
    protected $fillable = ['category','parent_id','name', 'slug'];

    public function subcategories()
    {
        return $this->hasMany(BlogCategory::class, 'parent_id');
    }
    public function children()
    {
        return $this->hasMany(BlogCategory::class, 'parent_id')->with('children');
    }
    public static function getCategories($parentId = null)
    {
        return self::with('children')->where('parent_id', $parentId)->paginate(10);
    }
    public function countSubcategories()
    {
        return $this->children()->count();
    }


}
