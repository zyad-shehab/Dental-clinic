<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class laboratoryModel extends Model
{
    protected $table = 'laboratories';

    protected $fillable = [
        'name',
        'location',
        'contact_number',
    ];

    public function requests()
    {
        return $this->hasMany(laboratory_requestsModel::class, 'laboratory_id');
    }

    public function storehouses()
    {
        return $this->belongsToMany(storehouseModel::class, 'storehouse_laboratory', 'laboratory_id', 'storehouse_id');
    }
}
