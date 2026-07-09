<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Course extends Model
{
    use LogsActivity, HasFactory, SoftDeletes;
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->useLogName('course-activity');
    }
    protected $fillable = [
        'category_id',
        'sub_category_id',
        'course_name',
        'price',
        'slug',
        'price_type',
        'discount_type',
        'discount_value',
        'discount_value',
        'ext_file',
        'is_required',
        'is_active',
        'is_merged',
    ];
    protected static function booted()
    {
        static::addGlobalScope('active', function (Builder $builder) {
            $restrictedRoles = ['school_admin', 'school_teacher', 'school_student', 'b2c_student'];
            $userRole = getUserRoles();
            if (Auth::check() && in_array($userRole, $restrictedRoles)) {
                $builder->where('is_active', 1);
            }
        });
    }

    public function metadata()
    {
        return $this->hasMany(CourseMetadataValue::class, 'course_id', 'id');
    }
    public function getCategoryCourse()
    {
        return $this->hasOne(Category::class, 'id', 'sub_category_id');
    }
    public function getSubCategory()
    {
        return $this->hasOne(Category::class, 'id', 'sub_category_id');
    }

    public function metadataValues()
    {
        return $this->hasMany(CourseMetadataValue::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function subCategory()
    {
        return $this->belongsTo(Category::class, 'sub_category_id', 'id');
    }

    // public function getSlugAttribute()
    // {
    //     return strtolower(str_replace(' ', '-', $this->course_name));
    // }
    public function cartItems()
    {
        return $this->hasMany(Cart::class, 'course_id');
    }
    public function wishlistItems()
    {
        return $this->hasMany(Wishlist::class, 'course_id');
    }
    public function totalChapters()
    {
        return $this->hasMany(CourseChapter::class, 'course_id', 'id');
    }
    public function subscriptionPurchases()
    {
        return $this->hasMany(SubscriptionPurchase::class);
    }
}
