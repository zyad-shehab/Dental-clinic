<?php

namespace App\Http\Controllers;

use App\Models\AppointmentsModel;
use App\Models\Clinic_SessionsModel;
use App\Models\patient_PaymentModel;
use App\Models\PatientModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;


class PatientController extends Controller{
   
    public function index(Request $request){
      try {  
        $patients=PatientModel::query();

        if ($request->filled('search')) {
        $search = trim($request->search);
        $patients->where('name', 'like', "%{$search}%");
        }

        $patients = $patients->latest()->paginate(10);

        return view('Admin.Patient.index',compact('patients'));
      }
      catch (\Exception $e) {
        Log::error('Error fetching patients: ' . $e->getMessage(), [
            'exception' => $e,
        ]);
        return redirect()
            ->back()
            ->with('error', 'حدث خطأ أثناء جلب بيانات المرضى.');
      }
    }
    
    public function create(){
        return view('Admin.Patient.create');
    }

    public function store(Request $request){
        $validated=$request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'phone' => 'nullable|unique:patients,phone|regex:/^\d{10}$/',
            'address' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date|before_or_equal:today',
            'chronic_diseases'=>'nullable|string',
            'allergies'=>'nullable|string',
            'clinic_source'=>'nullable|string',
        ],[
            'name.required' => 'الرجاء إدخال اسم المريض.',
            'gender.required'=>'الرجاء ادخال جنس المريض',
            'phone.unique' => 'رقم الهاتف مستخدم من قبل، الرجاء اختيار رقم آخر.',
            'date_of_birth.date' => 'صيغة تاريخ الميلاد غير صحيحة.',
            'date_of_birth.before_or_equal' => 'تاريخ الميلاد لا يمكن أن يكون في المستقبل.',
            'phone.digits' => 'رقم الهاتف يجب أن يكون مكونًا من 10 أرقام بالضبط.',

        ]);
      try {
        $patient=PatientModel::query()->create([
            'name' => $validated['name'],
            'gender' => $validated['gender'],
            'address' => $validated['address'],
            'phone' => $validated['phone'],
            'date_of_birth' => $validated['date_of_birth'],
            'chronic_diseases' => $validated['chronic_diseases'],
            'allergies' => $validated['allergies'],
            'clinic_source' => $validated['clinic_source'] ?? null
        ]);
        return redirect()
                ->route('admin.patients.index')
                ->with('success', 'تم إضافة المريض بنجاح');
      } 
      catch (\Exception $e) {
        Log::error('Error creating patient: ' . $e->getMessage(), [
            'exception' => $e,
        ]);
        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'حدث خطأ أثناء إضافة المريض: ' . $e->getMessage());
      }
    } 
    
    public function edit($id){
      try {  
        $patient = PatientModel::query()->findOrFail($id);
        return view('Admin.Patient.edit',compact('patient'));
      }
      catch (\Exception $e) {
        Log::error('Error fetching patient for edit: ' . $e->getMessage(), [
            'exception' => $e,
        ]);
        return redirect()
            ->back()
            ->with('error', 'حدث خطأ أثناء تحميل بيانات المريض: ' . $e->getMessage());
      }
    }

    public function show(Request $request,$id){
      try {
        $patient = PatientModel::findOrFail($id);
        $sessions=Clinic_SessionsModel::with('services')->where('patient_id', $patient->id)->paginate(5);
        $payments = patient_PaymentModel::where('patient_id', $patient->id)->paginate(5);

        $sortBy = $request->get('sort_by', 'appointment_date');
        $sortOrder = $request->get('order', 'asc');

        $appointments =AppointmentsModel::with('doctor')
        ->where('patient_id', $patient->id)
        ->orderBy($sortBy, $sortOrder)
        ->paginate(5)
        ->appends(request()->query()); // عدد المواعيد لكل صفحة

        return view('Admin.Patient.show', compact('patient','sessions','appointments','payments'));
      } 
      catch (\Exception $e) {
        Log::error('Error fetching patient details: ' . $e->getMessage(), [
            'exception' => $e,
        ]);
        return redirect()
            ->back()
            ->with('error', 'حدث خطأ أثناء تحميل بيانات المريض: ' . $e->getMessage());
      }
    }

    public function update(Request $request, $id){
        $patient = PatientModel::findOrFail($id);

        $validated=$request->validate([
                'name' => 'required|string|max:255',
                'gender' => 'required|in:male,female',
                'address'=> 'required|string|max:255',
                'phone' => [
                'nullable',
                'digits:10',
                    Rule::unique('patients', 'phone')->ignore($patient->id),],            'address' => 'nullable|string|max:255',
                'date_of_birth' => 'nullable|date|before_or_equal:today',
                'chronic_diseases'=>'nullable|string',
                'allergies'=>'nullable|string',
                'clinic_source'=>'nullable|string',
            ],[
                'name.required' => 'الرجاء إدخال اسم المريض.',
                'gender.required'=>'الرجاء ادخال جنس المريض',
                'phone.numeric' => 'رقم الهاتف يجب أن يكون أرقامًا فقط.',
                'phone.unique' => 'رقم الهاتف مستخدم من قبل، الرجاء اختيار رقم آخر.',
                'date_of_birth.date' => 'صيغة تاريخ الميلاد غير صحيحة.',
                'date_of_birth.before_or_equal' => 'تاريخ الميلاد لا يمكن أن يكون في المستقبل.',
                'phone.digits' => 'رقم الهاتف يجب أن يكون مكونًا من 10 أرقام بالضبط.',
            ]);
        
      try {
        $patient->name = $validated['name'];
        $patient->gender = $validated['gender'] ?? 'male';
        $patient->phone = $validated['phone'] ?? null;
        $patient->address = $validated['address'] ?? null;
        $patient->date_of_birth = $validated['date_of_birth'] ?? null;
        $patient->chronic_diseases = $validated['chronic_diseases'] ?? null;
        $patient->allergies = $validated['allergies'] ?? null;
        $patient->clinic_source = $validated['clinic_source'] ?? null;
        

        $patient->save();

        return redirect()->route('admin.patients.index')->with('success', 'تم تعديل بيانات المريض بنجاح');
      } 
      catch (\Exception $e) {
            Log::error('Error updating patient: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تعديل بيانات المريض: ' . $e->getMessage());
        }
    }
    
    public function destroy($id){
        try {

        PatientModel::query()->find($id)->delete();
        
        return redirect()
                ->route('admin.patients.index')
                ->with('success', 'تم حذف المريض بنجاح');

        }catch (\Exception $e) {
            Log::error('Error deleting doctor: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء حذف المريض : ' );
        }  
    }

public function patientStatement($patient_id){

  $patient = PatientModel::findOrFail($patient_id);

  $sessionItems = Clinic_SessionsModel::where('patient_id', $patient_id)
    ->select('session_date as date', 'remaining_amount as amount')
    ->get()
    ->map(function ($item) {
        return (object)[
            'date' => $item->date,
            'amount' => $item->amount,
            'type' => 'جلسة',
        ];
    });

  $paymentItems = patient_PaymentModel::where('patient_id', $patient_id)
    ->select('payment_date as date', 'total as amount')
    ->get()
    ->map(function ($item) {
        return (object)[
            'date' => $item->date,
            'amount' => $item->amount,
            'type' => 'دفعة',
        ];
    });

  // دمج وترتيب حسب التاريخ
  $statement = $sessionItems->merge($paymentItems)->sortBy('date')->values();

  // حساب الرصيد
  $balance = 0;
  foreach ($statement as $item) {
    if ($item->type == 'جلسة') {
        $balance += $item->amount;
    } else {
        $balance -= $item->amount;
    }
    $item->balance = $balance;
  }

   return view('Admin.Patient.statement', compact('statement', 'balance', 'patient'));
}

public function patientStatementPdf($patient_id){
    $patient = \App\Models\PatientModel::findOrFail($patient_id);

    $sessionItems = \App\Models\Clinic_SessionsModel::where('patient_id', $patient_id)
        ->select('session_date as date', 'required_amount as amount')
        ->get()
        ->map(fn($item) => (object)[
            'date' => $item->date,
            'amount' => $item->amount,
            'type' => 'جلسة',
        ]);

    $paymentItems = patient_PaymentModel::where('patient_id', $patient_id)
        ->select('payment_date as date', 'paid_amount as amount')
        ->get()
        ->map(fn($item) => (object)[
            'date' => $item->date,
            'amount' => $item->amount,
            'type' => 'دفعة',
        ]);

    $statement = $sessionItems->merge($paymentItems)->sortBy('date')->values();

    $balance = 0;
    foreach ($statement as $item) {
        $balance += ($item->type === 'جلسة') ? $item->amount : -$item->amount;
        $item->balance = $balance;
    }

    $pdf = Pdf::loadView('patients.statement_pdf', compact('statement', 'balance', 'patient'))
              ->setPaper('A4', 'portrait');

    return $pdf->download('كشف_حساب_' . $patient->name . '.pdf');
}

// public function patientsDebtsSummary()
// {
//     // نجلب جميع المرضى مع المبالغ
//     $patients = PatientModel::with([
//         'clinicSessions' => function ($q) {
//             $q->select('patient_id', 'remaining_amount', 'session_date');
//         },
//         'payments' => function ($q) {
//             $q->select('patient_id', 'paid_cash', 'paid_card', 'payment_date');
//         }
//     ])->get();

//     $result = [];

//     foreach ($patients as $patient) {
//         $total_remaining = $patient->clinicSessions->sum('remaining_amount');
//         $total_paid = $patient->payments->sum(function ($payment) {
//             return $payment->paid_cash + $payment->paid_card;
//         });

//         $balance = $total_remaining - $total_paid;

//         if ($balance > 0) {
//             // أحدث تاريخ بين الجلسات والدفعات
//             $lastSessionDate = $patient->clinicSessions->max('session_date');
//             $lastPaymentDate = $patient->payments->max('payment_date');
//             $lastDate = max($lastSessionDate, $lastPaymentDate);

//             $result[] = (object)[
//                 'patient_id' => $patient->id,
//                 'patient_name' => $patient->name,
//                 'balance' => $balance,
//                 'last_date' => $lastDate,
//             ];
//         }
//     }

//     // ترتيب النتائج حسب آخر تاريخ تنازلي
//     $result = collect($result)->sortByDesc('last_date')->values();

//     return view('Admin.Patient.patientsDebtsSummary', [
//         'debts' => $result,
//     ]);
// }

public function patientsDebtsSummary(Request $request)
{
    $sort = $request->query('sort','last_date_asc');

    $patients = PatientModel::with(['clinicSessions', 'payments'])->get();

    // تحليل القيمة إلى جزئين: الحقل والاتجاه
    [$sort_by, $sort_dir] = explode('_', $sort);

    $result = [];

    foreach ($patients as $patient) {
        $total_remaining = optional($patient->clinicSessions)->sum('remaining_amount') ?? 0;

        $total_paid = optional($patient->payments)->sum(function ($payment) {
            return $payment->paid_cash + $payment->paid_card;
        }) ?? 0;

        $balance = $total_remaining - $total_paid;

        if ($balance > 0) {
            $lastSessionDate = optional($patient->clinicSessions)->max('session_date');
            $lastPaymentDate =   optional($patient->payments)->max('payment_date');
            $lastDate =date('Y-m-d', strtotime(  max($lastSessionDate, $lastPaymentDate)));

            $result[] = (object)[
                'patient_id' => $patient->id,
                'patient_name' => $patient->name,
                'phone' => $patient->phone ?? '-',
                'address' => $patient->address ?? '-',
                // 'dob' => $patient->date_of_birth ?? null,
                'age' => $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->age : '-',
                'balance' => $balance,
                'last_date' => $lastDate,
            ];
        }
    }

    // ترتيب النتائج حسب طلب المستخدم
    
    $result = collect($result)->sortBy(function ($item) use ($sort_by) {
        return match ($sort_by) {
            'balance' => $item->balance,
            'age'     => $item->age ?? 0,
            'last_date' => $item->lastDate ,
            default => 0,
        };
    }, SORT_REGULAR, $sort_dir === 'desc')->values();
    // dd($result->pluck('last_date'));
    $total_debts = collect($result)->sum('balance');

    return view('Admin.Patient.patientsDebtsSummary', [
        'debts' => $result,
        'sort_by' => $sort_by,
        'result' => $result,
        'total_debts' => $total_debts,
    ]);
}



}
