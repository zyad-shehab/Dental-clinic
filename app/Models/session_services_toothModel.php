<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class session_services_toothModel extends Model
{
    protected $table = 'session_service_tooth';

    protected $fillable = [
        'clinic_session_id',
        'service_id',
        'tooth_number',
    ];
    public function clinicSession()
    {
        return $this->belongsTo(Clinic_SessionsModel::class, 'clinic_session_id');
    }
    public function service()
    {
        return $this->belongsTo(ServicesModel::class, 'service_id');
    }

}
