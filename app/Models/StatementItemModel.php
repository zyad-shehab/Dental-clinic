<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatementItemModel extends Model
{
    public $timestamps = false;
    protected $fillable = 
    ['date', 'amount', 'type', 'balance'];
    
    protected $table = null;

    // منع الاتصال الفعلي بقاعدة البيانات
    public function getConnectionName()
    {
        return null;
    }
}
