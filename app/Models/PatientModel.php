<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientModel extends Model
{
    use SoftDeletes;

    protected $table = 'patients';

    protected $fillable = [
        'name',
        'gender',
        'phone',
        'address',
        'date_of_birth',
        'chronic_diseases',
        'allergies',
        'clinic_source',
    ];

    public function appointments(){
        return $this->hasMany(AppointmentsModel::class, 'patient_id');
    }
    public function clinicSessions(){
        return $this->hasMany(Clinic_SessionsModel::class, 'patient_id');
    }
    public function payments(){
        return $this->hasMany(patient_PaymentModel::class, 'patient_id');
    }
    
}
