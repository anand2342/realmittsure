<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use  LogsActivity, SoftDeletes, HasApiTokens, HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->logExcept(['platform', 'session_id', 'api_token', 'password', 'updated_at'])
            ->useLogName('user-activity');
    }
    protected $fillable = [
        'name',
        'email',
        'username',
        'mobile_no',
        'user_type',
        'access_code',
        'password',
        'image',
        'status',
        'can_login',
        'validate_string',
        'created_by',
        'is_email_verified',
        'is_mobile_verified',
        'is_verified',
        'is_from_erp',
        'erp_db_id',
        'erp_schid',
        'd2c_user_school_name',
        'source',
        'is_from_external',
        'category_id',
        'soid',
        'school_id',
        'boid',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions');
    }

    public function userRole()
    {
        return $this->hasOne(UserRole::class, 'user_id')->with('role');
    }

    public function subscriptions()
    {
        return $this->hasMany(SubscriptionPurchase::class, 'user_id');
    }

    public function userAdditionalDetail()
    {
        return $this->hasOne(UserAdditionalDetail::class, 'user_id')->with(['classes', 'roleName', 'decisionMakerRole', 'board', 'schoolBoard', 'schoolMedium', 'parentSchoolName']);
    }
    public function userRoles()
    {
        return $this->hasMany(UserRole::class);
    }
    public function studentDetails()
    {
        return $this->hasOne(StudentDetails::class, 'user_id')->with('schoolDetails', 'className', 'studentState', 'studentCity');
    }

    public function schoolDetails()
    {
        return $this->hasOne(Schools::class, 'user_id');
    }
    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
    public function userClass()
    {
        return $this->hasOne(UserClass::class, 'user_id')->with('category');
    }
    public function accessCodes()
    {
        return $this->hasMany(AccessCode::class, 'user_id');
    }
    public function userAccessCode()
    {
        return $this->hasOne(AccessCode::class, 'user_id');
    }
    public function role()
    {
        return $this->hasOne(UserRole::class, 'user_id');
    }

    // Relationship with user_additional_details table
    public function additionalDetails()
    {
        return $this->hasOne(UserAdditionalDetail::class, 'user_id', 'id')->with('school');
    }

    public function loginLogs()
    {
        return $this->hasMany(UserLoginLog::class, 'user_id');
    }
    public function crmAddons()
    {
        return $this->hasMany(CrmSchoolAddon::class, 'user_id');
    }
    // Relationship with schools table via user_additional_details
    public function school()
    {
        return $this->hasOneThrough(
            Schools::class,
            UserAdditionalDetail::class,
            'user_id',  // Foreign key on user_additional_details table
            'id',       // Foreign key on schools table
            'id',       // Local key on users table
            'school_id' // Local key on user_additional_details table
        );
    }
    protected $appends = ['profile_image_url'];

    public function getProfileImageUrlAttribute()
    {
        if ($this->image && Storage::disk('public')->exists('uploads/user/profile_image/' . $this->image)) {
            return 'https://mittlearn.com/storage/uploads/user/profile_image/' . $this->image;
        }
        return 'https://mittlearn.com/images/default-profile.png'; // Provide a default image URL if no image exists
    }
}
