<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class patient_PaymentModel extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $table = 'patient_payment_models';

    protected $fillable = [
        'payment_date',
        'patient_id',
        'paid_cash',
        'paid_card',
        'name_of_bank_account',
        'total',
        'notes',
    ];

    public function patient()
    {
        return $this->belongsTo(PatientModel::class, 'patient_id');
    }   
}
