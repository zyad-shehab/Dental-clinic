<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class lab_PaymentModel extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    protected $table = 'lab_payment_models';

    protected $fillable = [
        'payment_date',
        'laboratories_id',
        'paid_cash',
        'paid_card',
        'name_of_bank_account',
        'total',
        'notes',
    ];

    public function laboratories()
    {
        return $this->belongsTo(laboratoryModel::class, 'laboratories_id');
    }
}
