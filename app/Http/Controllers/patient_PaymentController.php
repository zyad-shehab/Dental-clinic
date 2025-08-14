<?php

namespace App\Http\Controllers;

use App\Models\Clinic_SessionsModel;
use App\Models\patient_PaymentModel;
use App\Models\PatientModel;
use Illuminate\Http\Request;

class patient_PaymentController extends Controller
{
          
    public function index(Request $request){
        try{
            $from = $request->query('from', date('Y-m-d'));
            $to = $request->query('to', date('Y-m-d'));

            // Validate the date range
            if (!$from || !$to) {
                return response()->json(['error' => 'Invalid date range'], 400);
            }
        $payments = patient_PaymentModel::with('patient')->whereBetween('payment_date', [$from, $to])->paginate(10);
        return view('Admin.patient_Payment.index', compact('payments'));
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء استرجاع الدفعات: ' . $e->getMessage());
        }
    }

    public function create(){
        try {
        $patients = PatientModel::all();
        return view('Admin.patient_Payment.create', compact('patients'));
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء جلب بيانات المرضى: ' . $e->getMessage());
        }
    }

    public function store(Request $request){

        // dd($request->all());
        $request->validate([
            'payment_date' => 'required|date',
            'patient_id' => 'required',
            'Paid_cash' => 'nullable|numeric',
            'Paid_card' => 'nullable|numeric',
            'name_of_bank_account' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $total = $request->Paid_cash + $request->Paid_card;
        try {
        patient_PaymentModel::create([
            'payment_date' => $request->payment_date,
            'patient_id' => $request->patient_id,
            'paid_cash' => $request->Paid_cash ?? 0,
            'paid_card' => $request->Paid_card ?? 0,
            'name_of_bank_account' => $request->name_of_bank_account,
            'total' => $total,
            'notes' => $request->notes,
        ]);
        //  return   dd($request->all());

        return redirect()->route('patientPayment.index')->with('success', 'تم إضافة الدفعة بنجاح.');
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إضافة الدفعة: ' . $e->getMessage());
        }
    }

    public function edit($id){
        try {
        $payment = patient_PaymentModel::findOrFail($id);
        $patients = PatientModel::all();

        return view('Admin.patient_Payment.edit', compact('payment', 'patients'));
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء جلب بيانات الدفعة: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id){

        $request->validate([
            'payment_date' => 'required|date',
            'patient_id' => 'required',
            'Paid_cash' => 'nullable|numeric',
            'Paid_card' => 'nullable|numeric',
            'name_of_bank_account' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $total = $request->Paid_cash + $request->Paid_card;

        try {
            $payment = patient_PaymentModel::findOrFail($id);

            $payment->update([
                'payment_date' => $request->payment_date,
                'patient_id' => $request->patient_id,
                'paid_cash' => $request->Paid_cash ?? 0,
                'paid_card' => $request->Paid_card ?? 0,
                'name_of_bank_account' => $request->name_of_bank_account,
                'total' => $total,
                'notes' => $request->notes,
            ]);

            return redirect()->route('patientPayment.index')->with('success', 'تم تعديل الدفعة بنجاح.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تعديل الدفعة: ' . $e->getMessage());
        }
    }

    public function destroy($id){
        try {
            $payment = patient_PaymentModel::findOrFail($id);
            $payment->delete(); // إذا تستخدم soft delete، سيتم عمل soft delete
            return redirect()->route('patientPayment.index')->with('success', 'تم حذف الدفعة بنجاح.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف الدفعة: ' . $e->getMessage());
        }
    }
}
