<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServicesModel extends Model
{
    protected $table = 'services';

    protected $fillable = [
        'name',
        'description',
    ];

    public function sessionServicesTooth(){
        
        return $this->hasMany(session_services_toothModel::class, 'service_id');
    }
      
    public function sessions(){

        return $this->belongsToMany(Clinic_SessionsModel::class, 'service_session_tooth')
                ->withPivot('tooth_number')
                ->withTimestamps();
    }
    
}
