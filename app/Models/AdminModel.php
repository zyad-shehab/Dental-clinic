<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminModel extends Model
{
    use SoftDeletes;
    protected $table = 'admin';
    use HasFactory;
    protected $fillable = [
        'name',
        'username',
        'password',
    ];

    

    
}
