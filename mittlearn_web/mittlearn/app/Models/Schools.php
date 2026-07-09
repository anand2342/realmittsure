<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schools extends Model
{
    protected $fillable = [
        'name',
        'user_id',
        'unique_id',
        'school_type',
        'school_role',
        'contact_email',
        'contact_phone',
        'academic_session_id',
        'batch_id',
        'address',
        'city',
        'state',
        'postal_code',
        'contact_number',
        'email',
        'is_verified_by_admin',
        'is_varified_by',
        'is_from_crm',
        'removal_remark',
        'removal_remark'
    ];

    protected $table = "schools";

    use SoftDeletes;
    protected $dates = ['deleted_at'];
    public function userSchool()
    {
        return $this->hasOne(User::class, 'id', 'is_varified_by');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function user_additional_details()
    {
        return $this->belongsTo(UserAdditionalDetail::class, 'user_id', 'user_id');
    }
    public function accessCodeGet()
    {
        return $this->hasMany(AccessCode::class, 'school_id', 'id')->where('is_active', 1)->with(['class', 'board', 'medium']);
    }
    public function accessCodes()
    {
        return $this->hasMany(AccessCodeEmbibe::class, 'user_id', 'id');
    }
    public function assignedDigitalContents()
    {
        return $this->hasMany(SchoolAssignedDigitalContent::class, 'school_id', 'user_id');
    }
}
