<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogToCategories extends Model
{
    use HasFactory;
    protected $table = "blog_to_categories";

    protected $fillable = ['blog_id', 'category_id'];
    public $timestamps = false;
}
