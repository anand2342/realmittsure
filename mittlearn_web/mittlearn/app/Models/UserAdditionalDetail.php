<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Auth;

class UserAdditionalDetail extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = ['image', 'user_id', 'assign_to', 'school_id', 'lead', 'customer_type', 'parent_school_name', 'decision_maker', 'website', 'decision_maker_mobile_no', 'decision_maker_role', 'school_role', 'school_board', 'school_medium', 'series_id', 'strength', 'grade', 'school_affiliation_no', 'school_registration_no', 'incorporation_date', 'assign_distributor', 'gst_no', 'board_erp', 'address', 'landmark', 'bank_name', 'acc_holder_name', 'branch_name', 'acc_no', 'ifsc_code', 'last_name', 'gender', 'age', 'state', 'city', 'country', 'qualification', 'class_assigned', 'dob', 'experience', 'admission_no', 'role', 'assigned_classes', 'assigned_subjects', 'designation', 'about', 'facebook', 'instagram', 'linkedin', 'twitter', 'employee_id', 'distributor_id'];
    protected $table = 'user_additional_details';

    public function userAdditionalDetail()
    {
        return $this->hasOne(UserAdditionalDetail::class);
    }

    public function parentSchoolName()
    {
        return $this->hasOne(Schools::class, 'user_id', 'parent_school_name');
    }
    public function user()
    {
        return $this->belongsTo(User::class)->with('schoolDetails');
    }
    public function classes()
    {
        return $this->hasOne(Classes::class,  'id', 'class_assigned');
    }
    public function assignedClasses()
    {
        return $this->hasMany(Classes::class,  'id', 'assigned_classes');
    }
    public function assignedSubjects()
    {
        return $this->hasMany(Subject::class,  'id', 'assigned_subjects');
    }
    public function City()
    {
        return $this->belongsTo(City::class, 'city');
    }
    public function State()
    {
        return $this->belongsTo(State::class, 'state');
    }
    public function roleName()
    {
        return $this->hasOne(Role::class, 'role_slug', 'role');  // Foreign key is 'slug' in UserRole and 'role_slug' in Role
    }
    public function school()
    {
        return $this->hasOne(Schools::class, 'user_id', 'school_id');
    }
    public function board()
    {
        return $this->belongsTo(Board::class, 'board_id');
    }
    public function medium()
    {
        return $this->belongsTo(Medium::class, 'medium_id');
    }
    public function schoolBoard()
    {
        return $this->belongsTo(Board::class, 'school_board');
    }
    public function schoolMedium()
    {
        return $this->belongsTo(Medium::class, 'school_medium');
    }
    public function decisionMakerRole()
    {
        return $this->hasOne(Role::class, 'role_slug', 'decision_maker_role');
    }
}
