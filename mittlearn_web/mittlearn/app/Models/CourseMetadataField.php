<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class CourseMetadataField extends Model
{
    use HasFactory;

    protected $table = 'course_metadata_fields';

    protected $fillable = [
        'category_id',
        'category_slug',
        'field_name',
        'field_label',
        'field_type',
        'field_options',
        'is_required',
        'sort_order',
        'lookup_with',
        'field_placeholder',
        'is_active',
    ];
}


