<?php

namespace App\Models;

use App\Http\Controllers\LaboratoryRequestsController;
use Illuminate\Database\Eloquent\Model;

class laboratory_requests_itemsModel extends Model
{
    protected $table = 'laboratory_request_items';
    protected $fillable = [
        'laboratory_request_id',
        'category',
        'quantity',
        'tooth_number',
    ];

    public function request()
{
    return $this->belongsTo(laboratory_requestsModel::class, 'laboratory_request_id');
}

}
