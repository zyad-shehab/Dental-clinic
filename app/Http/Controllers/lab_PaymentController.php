<?php

namespace App\Http\Controllers;

use App\Models\lab_PaymentModel;
use App\Models\laboratoryModel;
use Illuminate\Http\Request;

class lab_PaymentController extends Controller
{
      
    public function index()
    {
        $payments = lab_PaymentModel::with('laboratories')->latest()->get();
        return view('Admin.lab_payments.index', compact('payments'));
    }

    public function create()
    {
        $laboratories = laboratoryModel::all();
        return view('Admin.lab_Payments.create', compact('laboratories'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'payment_date' => 'required|date',
            'laboratories_id' => 'required|exists:laboratories,id',
            'Paid_cash' => 'nullable|numeric',
            'Paid_card' => 'nullable|numeric',
            'name_of_bank_account' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $total = $request->Paid_cash + $request->Paid_card;
        try {
        lab_PaymentModel::create([
            'payment_date' => $request->payment_date,
            'laboratories_id' => $request->laboratories_id,
            'paid_cash' => $request->Paid_cash ?? 0,
            'paid_card' => $request->Paid_card ?? 0,
            'name_of_bank_account' => $request->name_of_bank_account,
            'total' => $total,
            'notes' => $request->notes,
        ]);
    //  return   dd($request->all());

        return redirect()->route('labpayment.index')->with('success', 'تم إضافة الدفعة بنجاح.');
    }
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إضافة الدفعة: ' . $e->getMessage());
        }
    }
    public function edit($id)
{
    $payment = lab_PaymentModel::findOrFail($id);
    $laboratories = laboratoryModel::all();

    return view('Admin.lab_payments.edit', compact('payment', 'laboratories'));
}
public function update(Request $request, $id)
{
    $request->validate([
        'payment_date' => 'required|date',
        'laboratories_id' => 'required|exists:laboratories,id',
        'Paid_cash' => 'nullable|numeric',
        'Paid_card' => 'nullable|numeric',
        'name_of_bank_account' => 'nullable|string',
        'notes' => 'nullable|string',
    ]);

    $total = $request->Paid_cash + $request->Paid_card;

    try {
        $payment = lab_PaymentModel::findOrFail($id);

        $payment->update([
            'payment_date' => $request->payment_date,
            'laboratories_id' => $request->laboratories_id,
            'paid_cash' => $request->Paid_cash ?? 0,
            'paid_card' => $request->Paid_card ?? 0,
            'name_of_bank_account' => $request->name_of_bank_account,
            'total' => $total,
            'notes' => $request->notes,
        ]);

        return redirect()->route('labpayment.index')->with('success', 'تم تعديل الدفعة بنجاح.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'حدث خطأ أثناء تعديل الدفعة: ' . $e->getMessage());
    }
}
public function destroy($id)
{
    try {
        $payment = lab_PaymentModel::findOrFail($id);
        $payment->delete(); // إذا تستخدم soft delete، سيتم عمل soft delete
        return redirect()->route('labpayment.index')->with('success', 'تم حذف الدفعة بنجاح.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'حدث خطأ أثناء حذف الدفعة: ' . $e->getMessage());
    }
}


}
     



