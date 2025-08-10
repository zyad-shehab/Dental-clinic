<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class laboratory_requestsModel extends Model
{
    use HasFactory;
    protected $table = 'laboratory_requests';
    protected $fillable = [
        'patient_id',
        'laboratory_id',
        'request_date',
        'status',
        'notes',
    ];

    public function patient(){

        return $this->belongsTo(PatientModel::class,   'patient_id', 'id'  );
    }

   public function laboratory(){

    return $this->belongsTo(laboratoryModel::class, 'laboratory_id', 'id');
    
    }
    public function items()
{
    return $this->hasMany(laboratory_requests_itemsModel::class, 'laboratory_request_id');
}
 protected static function boot()
    {
        parent::boot();

        static::deleting(function ($request) {
            $request->items()->delete();
        });
    }
}
