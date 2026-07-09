<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentDetails extends Model
{
    protected $fillable = ['user_id', 'school_id', 'city', 'state', 'parent_id', 'school_id', 'dob', 'doj', 'admission_no', 'roll_number', 'name', 'parent_name', 'class', 'section', 'option_a', 'option_b', 'd2c_user_school_name', 'school_pincode', 'school_state', 'school_district', 'school_address_1'];

    protected $table = "student_details";

    public function studentClass()
    {
        return $this->belongsTo(Classes::class, 'class');
    }
    public function schoolDetails()
    {
        return $this->hasOne(Schools::class, 'user_id', 'school_id');
    }
    public function className()
    {
        return $this->belongsTo(Classes::class, 'class');
    }
    public function studentState()
    {
        return $this->belongsTo(State::class, 'state');
    }
    public function studentCity()
    {
        return $this->belongsTo(City::class, 'city');
    }
}
