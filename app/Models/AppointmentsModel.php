<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppointmentsModel extends Model
{
    protected $table = 'appointments';

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'appointment_date',
        'start_time',
        'end_time',
        'status',
        'notes',
    ];

    
    public function patient()
    {
        return $this->belongsTo(PatientModel::class);
    }

    public function doctor()
    {
        return $this->belongsTo(DoctorModel::class);
    }
}
