<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse_paymentsModel extends Model{
    use HasFactory;
    protected $table = 'warehouse_payments_models';

    protected $fillable = [
        'payment_date',
        'storehouses_id',
        'paid_cash',
        'paid_card',
        'name_of_bank_account',
        'total',
        'notes',
    ];

    public function storehouses()
    {
        return $this->belongsTo(storehouseModel::class, 'storehouses_id');
    }
}
