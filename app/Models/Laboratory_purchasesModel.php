<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laboratory_purchasesModel extends Model
{
    protected $table = 'laboratory_purchases_models';

    protected $fillable = [
        'laboratory_id',
        'patient_id',
        'purchase_date',
        'total_amount',
        'notes',
    ];

    public function laboratory()
    {
        return $this->belongsTo(laboratoryModel::class, 'laboratory_id', 'id');
    }

    public function patient()
    {
        return $this->belongsTo(PatientModel::class, 'patient_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(Laboratory_purchases_itemsModel::class, 'laboratory_purchase_id');
    }
}
