<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SecretaryModel extends Model{
    
    protected $table = 'secretary';

    protected $fillable = [
        'name',
        'specialization',
        'address',
        'date_of_birth',
        'username',
        'password',
        'phone',
        
    ];
}
