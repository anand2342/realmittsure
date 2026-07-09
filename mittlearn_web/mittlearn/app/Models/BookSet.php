<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookSet extends Model
{
    protected $fillable = ['name','sku_code', 'subject_id', 'board_id', 'medium_id', 'series_id', 'class_id', 'is_active'];
    protected $table = "book_sets";
    public function series()
    {
        return $this->belongsTo(BookSeries::class, 'series_id'); 
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id'); 
    }
}
