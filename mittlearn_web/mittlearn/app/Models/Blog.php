<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'slug', 'body', 'author_id', 'featured_image', 'status', 'meta_title', 'meta_keywords', 'meta_description', 'published_at'];
    protected $table = "blogs";
    public function views()
    {
        return $this->hasMany(BlogView::class);
    }
    public function categories()
    {
        return $this->belongsToMany(BlogCategory::class, 'blog_to_categories', 'blog_id', 'category_id');
    }
    public function blogsMedia()
    {
        return $this->hasOne(MediaFiles::class, 'tbl_id', 'id')->where('type', 'blog');
    }
}
