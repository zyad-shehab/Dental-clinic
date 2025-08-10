<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class storehouseModel extends Model
{
    use HasFactory;
    
    protected $table = 'storehouses';

    protected $fillable = [
        'name',
        'location',
        'contact_number',
    ];
    
}
