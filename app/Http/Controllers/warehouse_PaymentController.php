<?php

namespace App\Http\Controllers;

use App\Models\storehouseModel;
use App\Models\Warehouse_paymentsModel;
use App\Models\Warehouse_purchasesModel;
use Illuminate\Http\Request;

class warehouse_PaymentController extends Controller{
    
    public function index()
    {
        $payments = Warehouse_paymentsModel::with('storehouses')->latest()->get();
        return view('Admin.warehouse_Payments.index', compact('payments'));
    }

    public function create()
    {
        $storehouse = storehouseModel::all();
        return view('Admin.warehouse_Payments.create', compact('storehouse'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'payment_date' => 'required|date',
            'storehouses_id' => 'required|exists:storehouses,id',
            'Paid_cash' => 'nullable|numeric',
            'Paid_card' => 'nullable|numeric',
            'name_of_bank_account' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $total = $request->Paid_cash + $request->Paid_card;
        try {
        Warehouse_paymentsModel::create([
            'payment_date' => $request->payment_date,
            'storehouses_id' => $request->storehouses_id,
            'paid_cash' => $request->Paid_cash ?? 0,
            'paid_card' => $request->Paid_card ?? 0,
            'name_of_bank_account' => $request->name_of_bank_account,
            'total' => $total,
            'notes' => $request->notes,
        ]);
    //  return   dd($request->all());

        return redirect()->route('warehousepayment.index')->with('success', 'تم إضافة الدفعة بنجاح.');
    }
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إضافة الدفعة: ' . $e->getMessage());
        }
    }
    public function edit($id)
{
    $payment = Warehouse_paymentsModel::findOrFail($id);
    $storehouse = storehouseModel::all();

    return view('Admin.warehouse_Payments.edit', compact('payment', 'storehouse'));
}
public function update(Request $request, $id)
{
    $request->validate([
        'payment_date' => 'required|date',
        'storehouses_id' => 'required|exists:storehouses,id',
        'Paid_cash' => 'nullable|numeric',
        'Paid_card' => 'nullable|numeric',
        'name_of_bank_account' => 'nullable|string',
        'notes' => 'nullable|string',
    ]);

    $total = $request->Paid_cash + $request->Paid_card;

    try {
        $payment = Warehouse_paymentsModel::findOrFail($id);

        $payment->update([
            'payment_date' => $request->payment_date,
            'storehouses_id' => $request->storehouses_id,
            'paid_cash' => $request->Paid_cash ?? 0,
            'paid_card' => $request->Paid_card ?? 0,
            'name_of_bank_account' => $request->name_of_bank_account,
            'total' => $total,
            'notes' => $request->notes,
        ]);

        return redirect()->route('warehousepayment.index')->with('success', 'تم تعديل الدفعة بنجاح.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'حدث خطأ أثناء تعديل الدفعة: ' . $e->getMessage());
    }
}
public function destroy($id)
{
    try {
        $payment = Warehouse_paymentsModel::findOrFail($id);
        $payment->delete(); // إذا تستخدم soft delete، سيتم عمل soft delete
        return redirect()->route('warehousepayment.index')->with('success', 'تم حذف الدفعة بنجاح.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'حدث خطأ أثناء حذف الدفعة: ' . $e->getMessage());
    }
}


}
     

