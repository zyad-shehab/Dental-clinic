<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoctorModel extends Model
{
    use SoftDeletes;
    protected $table = 'doctor';

    protected $fillable = [
        'name',
        'specialization',
        'address',
        'date_of_birth',
        'phone',
        'username',
        'password',
    ];

    public function appointments(){
        return $this->hasMany(AppointmentsModel::class, 'doctor_id');
    }

    public function clinicSessions(){
        return $this->hasMany(Clinic_SessionsModel::class,'doctor_id');
    }

  
}
