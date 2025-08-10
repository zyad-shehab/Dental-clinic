<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse_purchases_itemsModel extends Model
{

    protected $fillable = [
        'warehouse_purchases_id',
        'item_name',
        'quantity',
        'price',
        'total',
        'notes',
        'end_date',
        'status',
    ];

    public function purchase()
    {
        return $this->belongsTo(Warehouse_purchasesModel::class, 'warehouse_purchases_id');
    }
    

}
