<?php

namespace App\Http\Controllers;

use App\Models\Warehouse_purchases_itemsModel;
use Illuminate\Http\Request;

class WarehouseStockController extends Controller
{


    public function index(Request $request)
{
    $status = $request->get('status');
    $search = $request->get('search');

    $query = Warehouse_purchases_itemsModel::query();

    if ($status && in_array($status, ['Available', 'finished'])) {
        $query->where('status', $status);
    }
    if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('item_name', 'like', "%{$search}%");
    }

    $items = $query->orderBy('end_date')->paginate(10);

    $today = now();
    $alert_threshold = $today->copy()->addDays(7); // 7 أيام من الآن

    return view('Admin.clinic_warehouse.index', compact('items', 'status', 'alert_threshold'));
}


    public function toggleStatus($id)
    {
        $item = Warehouse_purchases_itemsModel::findOrFail($id);
        $item->status = $item->status === 'Available' ? 'finished' : 'Available';
        $item->save();

        return redirect()->route('clinic_warehouse.index')->with('success', 'تم تغيير حالة الصنف بنجاح.');
    }
}


