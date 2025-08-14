<?php

namespace App\Http\Controllers;

use App\Models\Laboratory_purchases_itemsModel;
use App\Models\Laboratory_purchasesModel;
use App\Models\laboratoryModel;
use App\Models\PatientModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class laboratory_purchasesController extends Controller
{

    public function index(Request $request){
        try{
            $from = $request->query('from', date('Y-m-d'));
            $to = $request->query('to', date('Y-m-d'));

            // Validate the date range
            if (!$from || !$to) {
                return response()->json(['error' => 'Invalid date range'], 400);
            }
        $purchases = Laboratory_purchasesModel::with(['laboratory', 'patient', 'items'])->whereBetween('purchase_date', [$from, $to]);

        if ($request->filled('search')) {
            $search = trim($request->search);

            $purchases->where(function ($query) use ($search) {
                $query->whereHas('patient', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhereHas('laboratory', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            });
        }

        $purchases = $purchases->latest()->paginate(10);

        return view('Admin.laboratory_purchases.index', compact('purchases'));
        
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء جلب بيانات مشتريات المعامل.');
        }
    }


    public function create(){

        $laboratories = laboratoryModel::all();
        $patients = PatientModel::all();
        return view('Admin.laboratory_purchases.create', compact('laboratories', 'patients'));
    }

    public function store(Request $request){
        $validated = $request->validate([
            'laboratory_id' => 'required|exists:laboratories,id',
            'patient_id' => 'required|exists:patients,id',
            'purchase_date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.number_of_teeth' => 'nullable|string',
            'items.*.notes' => 'nullable|string',
        ]);
        try {
        DB::transaction(function () use ($validated) {
            $total = 0;
            foreach ($validated['items'] as $item) {
                $total += $item['price'] * $item['quantity'];
            }

            $purchase = Laboratory_purchasesModel::create([
                'laboratory_id' => $validated['laboratory_id'],
                'patient_id' => $validated['patient_id'],
                'purchase_date' => $validated['purchase_date'],
                'total_amount' => $total,
                'notes' => $validated['notes'],
            ]);

            foreach ($validated['items'] as $item) {
                $purchase->items()->create([
                    'item_name' => $item['item_name'],
                    'number_of_teeth' => $item['number_of_teeth'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'total' => $item['price'] * $item['quantity'],
                    'notes' => $item['notes'],
                ]);
            }
        });

        return redirect()->route('laboratoryPurchases.index')->with('success', 'تمت إضافة الفاتورة بنجاح.');
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إضافة الفاتورة: ' . $e->getMessage());
        }
    }

    public function show($id){
        try {
        $purchase = Laboratory_purchasesModel::with(['laboratory', 'patient', 'items'])->findOrFail($id);
        return view('Admin.laboratory_purchases.show', compact('purchase'));
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء جلب بيانات الفاتورة: ' . $e->getMessage());
        }
    }

    // public function print($id){

    //     $purchase = Laboratory_purchasesModel::with(['laboratory', 'patient', 'items'])->findOrFail($id);

    //     $pdf = Pdf::loadView('laboratory_purchases.pdf', compact('purchase'));

    //     return $pdf->stream('فاتورة_مشتريات_' . $purchase->id . '.pdf');
    // }

    public function edit($id){
        try {
            $purchase = Laboratory_purchasesModel::with('items')->findOrFail($id);
            $patients = PatientModel::all();
            $laboratories = laboratoryModel::all();

            return view('admin.Laboratory_Purchases.edit', compact('purchase', 'patients', 'laboratories'));
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء جلب بيانات الفاتورة: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id){
        $request->validate([
            'laboratory_id' => 'required|exists:laboratories,id',
            'patient_id' => 'required|exists:patients,id',
            'purchase_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.number_of_teeth' => 'nullable|string',
            'items.*.notes' => 'nullable|string',
        ]);
        try {
        $purchase = Laboratory_purchasesModel::findOrFail($id);

        $purchase->update([
            'laboratory_id' => $request->laboratory_id,
            'patient_id' => $request->patient_id,
            'purchase_date' => $request->purchase_date,
            'notes' => $request->notes,
        ]);

        // حذف الأصناف المطلوبة
        if ($request->filled('deleted_items')) {
            $deletedIds = json_decode($request->deleted_items, true);
            Laboratory_purchases_itemsModel::whereIn('id', $deletedIds)->delete();
        }

        $total = 0;

        foreach ($request->items as $item) {
            $itemTotal = $item['price'] * $item['quantity'];
            $total += $itemTotal;

            if (isset($item['id'])) {
                // تحديث صنف موجود
                Laboratory_purchases_itemsModel::where('id', $item['id'])->update([
                    'item_name' => $item['item_name'],
                    'number_of_teeth' => $item['number_of_teeth'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'total' => $itemTotal,
                    'notes' => $item['notes'],
                ]);
            } else {
                // إضافة صنف جديد
                Laboratory_purchases_itemsModel::create([
                    'laboratory_purchase_id' => $purchase->id,
                    'item_name' => $item['item_name'],
                    'number_of_teeth' => $item['number_of_teeth'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'total' => $itemTotal,
                    'notes' => $item['notes'],
                ]);
            }
        }

        // تحديث المبلغ الإجمالي بعد تعديل الأصناف
        $purchase->update([
            'total_amount' => $total,
        ]);

        return redirect()->route('laboratoryPurchases.index')->with('success', 'تم تعديل الفاتورة بنجاح');
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تعديل الفاتورة: ' . $e->getMessage());
        }
    }

    public function destroy($id){
        try {
        $purchase = Laboratory_purchasesModel::with('items')->findOrFail($id);

        // حذف الأصناف المرتبطة أولاً
        foreach ($purchase->items as $item) {
            $item->delete();
        }

        // حذف الفاتورة نفسها
        $purchase->delete();

        return redirect()->route('laboratoryPurchases.index')->with('success', 'تم حذف الفاتورة بنجاح');
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف الفاتورة: ' . $e->getMessage());
        }
    }

}
