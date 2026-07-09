<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CmsPage extends Model
{
    use HasFactory;

    protected $table = 'cms_pages';
    protected $fillable = [
        'title',
        'name',
        'description',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'slug',
        'image',
    ];
}
