<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clinic_SessionsModel extends Model{

    use HasFactory;

    protected $table = 'clinic_sessions';

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'session_date',
        'start_time',
        'end_time',
        'drug',
        'required_amount',
        'cash_payment',
        'card_payment',
        'total_amount',
        'name_of_bank_account',
        'remaining_amount',
        'xray_image',
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
    public function sessionServiceTooth(){
        return $this->hasMany(session_services_toothModel::class, 'clinic_session_id');
    }
    public function services()
    {
        return $this->belongsToMany(ServicesModel::class, 'session_service_tooth','clinic_session_id', 'service_id')
                    ->withPivot('tooth_number')
                    ->withTimestamps();
    }
    
}

    

