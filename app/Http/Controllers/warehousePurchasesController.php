<?php

namespace App\Http\Controllers;

use App\Models\storehouseModel;
use App\Models\Warehouse_purchases_itemsModel;
use App\Models\Warehouse_purchasesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class warehousePurchasesController extends Controller
{
    

    public function index()
    {
        $purchases = Warehouse_purchasesModel::with(['storehouse', 'items'])->latest()->get();
        return view('Admin.warehouse_Purchases.index', compact('purchases'));
    }

    public function create()
    {
        $storehouses = storehouseModel::all();
        return view('Admin.warehouse_Purchases.create', compact('storehouses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'storehouses_id' => 'required|exists:storehouses,id',
            'purchase_date' => 'required|date',
            'items.*.item_name' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $total = 0;

            // حساب المجموع
            foreach ($request->items as $item) {
                $total += $item['quantity'] * $item['price'];
            }

            $purchase = Warehouse_purchasesModel::create([
                'storehouses_id' => $request->storehouses_id,
                'purchase_date' => $request->purchase_date,
                'total_amount' => $total,
                'notes' => $request->notes,
            ]);

            foreach ($request->items as $item) {
                Warehouse_purchases_itemsModel::create([
                    'warehouse_purchases_id' => $purchase->id,
                    'item_name' => $item['item_name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['quantity'] * $item['price'],
                    'notes' => $item['notes'] ?? null,
                    'end_date' => $item['end_date'] ?? null,
                ]);
            }

            DB::commit();
            return redirect()->route('warehousePurchases.index')->with('success', 'تمت إضافة الفاتورة بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء الحفظ: ' . $e->getMessage());
        }
    }

    public function show($id)
{

    $today = now();
    $alert_threshold = $today->copy()->addDays(7); // 7 أيام من الآن
    $purchase = Warehouse_purchasesModel::with(['storehouse', 'items'])->findOrFail($id);
    return view('Admin.warehouse_Purchases.show', compact('purchase', 'alert_threshold'));
}


    public function edit($id)
{
    $purchase = Warehouse_purchasesModel::with('items')->findOrFail($id);
    $storehouses = storehouseModel::all(); // تأكد من استيراد موديل المخازن أو تعديله حسب اسم الموديل عندك

    return view('admin.warehouse_purchases.edit', compact('purchase', 'storehouses'));
}


 public function update(Request $request, $id)
{
    $request->validate([
        'storehouses_id' => 'required|exists:storehouses,id',
        'purchase_date' => 'required|date',
        'notes' => 'nullable|string',
        'items' => 'required|array|min:1',
        'items.*.item_name' => 'required|string',
        'items.*.price' => 'required|numeric',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.end_date' => 'nullable|date',
        'items.*.notes' => 'nullable|string',
    ]);

    $purchase = Warehouse_purchasesModel::findOrFail($id);

    // تحديث بيانات الفاتورة الأساسية
    $purchase->update([
        'storehouses_id' => $request->storehouses_id,
        'purchase_date' => $request->purchase_date,
        'notes' => $request->notes,
    ]);

    // حذف الأصناف المحذوفة
    if ($request->filled('deleted_items')) {
        $deletedIds = json_decode($request->deleted_items, true);
        Warehouse_purchases_itemsModel::whereIn('id', $deletedIds)->delete();
    }

    // تحديث أو إضافة الأصناف
    foreach ($request->items as $item) {
        $total = $item['price'] * $item['quantity'];

        if (isset($item['id'])) {
            // تحديث صنف موجود
            $existingItem = Warehouse_purchases_itemsModel::find($item['id']);
            if ($existingItem) {
                $existingItem->update([
                    'item_name' => $item['item_name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'end_date' => $item['end_date'] ?? null,
                    'notes' => $item['notes'] ?? null,
                    'total' => $total,
                ]);
            }
        } else {
            // إضافة صنف جديد
            Warehouse_purchases_itemsModel::create([
                'warehouse_purchases_id' => $purchase->id,
                'item_name' => $item['item_name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'end_date' => $item['end_date'] ?? null,
                'notes' => $item['notes'] ?? null,
                'total' => $total,
            ]);
        }
    }

    // تحديث إجمالي الفاتورة
    $purchase->total_amount = $purchase->items()->sum('total');
    $purchase->save();

    return redirect()->route('warehousePurchases.index')->with('success', 'تم تعديل فاتورة المشتريات بنجاح');
}

public function destroy($id)

{
    $purchase = Warehouse_purchasesModel::findOrFail($id);
    $purchase->forcedelete();

    return redirect()->route('warehousePurchases.index')->with('success', 'تم حذف الفاتورة مع الأصناف بنجاح');
}









}


