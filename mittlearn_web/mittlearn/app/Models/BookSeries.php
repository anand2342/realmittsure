<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookSeries extends Model
{
    protected $fillable = [ 'name', 'is_active','board_id','medium_id','short_code','class_subjects', 'slug','image','is_default'];
    protected $table = "book_series";

    public function board()
    {
        return $this->belongsTo(Board::class, 'board_id'); 
        // return $this->belongsTo(Board::class, 'board_id')->where('is_active', 1);
    }

    public function medium()
    {
        return $this->belongsTo(Medium::class, 'medium_id'); 
        // return $this->belongsTo(Medium::class, 'medium_id')->where('is_active', 1);
    }
}
