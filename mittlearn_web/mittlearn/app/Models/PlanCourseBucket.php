<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanCourseBucket extends Model
{
    use HasFactory;
    protected $fillable = [
        "series",
        "class",
        "subject",
        "discount_type",
        "discount_value",
        "is_active",
    ];
    public function bookSeries()
    {
        return $this->hasOne(BookSeries::class, 'id', 'series');
    }
}
