<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse_purchasesModel extends Model
{

    protected $fillable = [
        'storehouses_id',
        'purchase_date', 
        'total_amount', 
        'notes'];

    public function storehouse()
    {
        return $this->belongsTo(storehouseModel::class, 'storehouses_id');
    }

    public function items()
    {
        return $this->hasMany(Warehouse_purchases_itemsModel::class, 'warehouse_purchases_id');
    }


    protected static function boot()
{
    parent::boot();

    static::deleting(function ($purchase) {
        // حذف أصناف الفاتورة المرتبطة عند حذف الفاتورة
        $purchase->items()->delete();
    });
}

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::deleting(function ($purchase) {
    //         // عند حذف الفاتورة بنظام soft delete، احذف أصنافها soft delete أيضًا
    //         if ($purchase->isForceDeleting()) {
    //             // لو حذف نهائي (force delete)، احذف الأصناف نهائيًا
    //             $purchase->items()->forceDelete();
    //         } else {
    //             // حذف عادي (soft delete)
    //             $purchase->items()->delete();
    //         }
    //     });

    //     // لو تريد تفعيل الاستعادة (restore) مع الأصناف
    //     static::restoring(function ($purchase) {
    //         $purchase->items()->withTrashed()->restore();
    //     });
    // }
}


