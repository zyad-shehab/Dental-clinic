<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class expensesModel extends Model
{
     protected $table = 'expenses_models';

    protected $fillable = [
        'date',
        'pay_to',
        'paid_cash',
        'paid_card',
        'name_of_bank_account',
        'total',
        'note'
    ];
}
