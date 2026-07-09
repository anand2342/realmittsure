<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseMetadataValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'field_id',
        'field_name',
        'field_value',
    ];

    protected $with = ['boardInfo', 'classInfo', 'subjectInfo', 'bookSeriesInfo', 'categoryInfo'];
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }
    public function boardInfo()
    {
        return $this->belongsTo(Board::class, 'field_value', 'id');
    }

    public function classInfo()
    {
        return $this->belongsTo(Classes::class, 'field_value', 'id');
    }

    public function subjectInfo()
    {
        return $this->belongsTo(Subject::class, 'field_value', 'id');
    }

    public function bookSeriesInfo()
    {
        return $this->belongsTo(BookSeries::class, 'field_value', 'id');
    }
    public function categoryInfo()
    {
        return $this->belongsTo(Category::class, 'field_value', 'id');
    }
}
