<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laboratory_purchases_itemsModel extends Model
{
    protected $table = 'laboratory_purchases_items_models';

    protected $fillable = [
        'laboratory_purchase_id',
        'item_name',
        'number_of_teeth', // رقم الأسنان
        'price',
        'quantity',
        'total',
        'notes',
    ];

    public function purchase()
    {
        return $this->belongsTo(Laboratory_purchasesModel::class, 'laboratory_purchase_id');
    }
}
