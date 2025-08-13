<?php

namespace App\Http\Controllers;

use App\Models\AppointmentsModel;
use App\Models\Clinic_SessionsModel;
use App\Models\DoctorModel;
use App\Models\PatientModel;
use App\Models\ServicesModel;
use App\Models\session_services_toothModel;
use App\Models\TeethModel;
use Dom\Text;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Session\SessionServiceProvider;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Type\Time;

use function Laravel\Prompts\text;
use Illuminate\Support\Str;


class Clinic_SessionsController extends Controller{

    public function index(Request $request){
     try {
        $from = $request->query('from', date('Y-m-d'));
        $to = $request->query('to', date('Y-m-d'));

        // Validate the date range
        if (!$from || !$to) {
            return response()->json(['error' => 'Invalid date range'], 400);
        }
        $sessions=Clinic_SessionsModel::with('services')
            ->whereBetween('session_date', [$from, $to]);

        if ($request->filled('search')) {
            $search = trim($request->search);

            // البحث أولاً في المرضى
            $patient = \App\Models\PatientModel::where('name', 'like', "%{$search}%")->first();

            // إذا لم نجد مريض، نبحث في الأطباء
            $doctor = \App\Models\DoctorModel::where('name', 'like', "%{$search}%")->first();

            if ($patient) {
                $sessions->where('patient_id', $patient->id);
            } elseif ($doctor) {
                $sessions->where('doctor_id', $doctor->id);
            } else {
                // لا يوجد مطابق => ارجع نتائج فارغة
                $sessions->whereRaw('0 = 1');
            }
        }
        switch ($request->sort) {
                case 'date_asc':
                    $sessions->orderBy('session_date', 'asc')->orderBy('start_time', 'asc');
                    break;
                case 'date_desc':
                    $sessions->orderBy('session_date', 'desc')->orderBy('start_time', 'asc');
                    break;
                default:
                    $sessions->latest('session_date')->latest('start_time');
        }

        $sessions = $sessions->latest()->paginate(10)->appends($request->all());

        return view('Admin.Clinic_Sessions.index', compact('sessions'));
      }
       catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تحميل الجلسات: ' . $e->getMessage());
        }
    }
    
    public function create(){
      try {
        $patients = PatientModel::all(); 
        $doctors = DoctorModel::all();  
        $services=ServicesModel::all();

        return view('Admin.Clinic_Sessions.create',compact('patients', 'doctors','services'));
        }
      catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'حدث خطأ أثناء انشاء الجلسة: ' . $e->getMessage());
      }
    }

    public function store(Request $request){
    
        $request->validate([
                'patient_id' => 'required|exists:patients,id',
                'doctor_id' => 'required|exists:doctor,id',
                'session_date' => 'required|date',
                'start_time' => 'required',
                'end_time' => 'nullable',
                'drug' => 'nullable|string',
                'required_amount' => 'nullable|numeric',
                'cash_payment' => 'nullable|numeric',
                'card_payment' => 'nullable|numeric',
                'total_amount' => 'nullable|numeric',
                'name_of_bank_account' => 'nullable|string',
                'remaining_amount' => 'nullable|numeric',
                'xray_image' => 'nullable|image|max:2048',
                'notes' => 'nullable|string',
        ]);

            //  رفع صورة الأشعة إن وجدت
            $xrayPath = null;
            if ($request->hasFile('xray_image')) {
                $xrayPath = $request->file('xray_image')->store('xray_images', 'public');
            }
        try {
            //  حفظ الجلسة
            $session = Clinic_SessionsModel::create([
                'patient_id' => $request->patient_id,
                'doctor_id' => $request->doctor_id,
                'session_date' => $request->session_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'drug' => $request->drug,
                'required_amount' => $request->required_amount ?? 0,
                'cash_payment' => $request->cash_payment ?? 0,
                'card_payment' => $request->card_payment ?? 0,
                'total_amount' => $request->total_amount ?? 0,
                'name_of_bank_account' => $request->name_of_bank_account,
                'remaining_amount' => $request->remaining_amount ?? 0,
                'xray_image' => $xrayPath,
                'notes' => $request->notes,
            ]);
            if($request->services){
                foreach ($request->services as $service) {
                    $session->services()->attach($service['id'], [
                        'tooth_number' => $service['tooth_number'] ?? null 
                ]);}
                }

            return redirect()->route('sessions.index')->with('success', 'تمت إضافة الجلسة بنجاح');
        } 
        catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إضافة الجلسة: ' . $e->getMessage());
        }

    }  

    public function edit($id){
      try {
        $patients = PatientModel::all(); 
        $doctors = DoctorModel::all();  
        $services=ServicesModel::all();

        $session = Clinic_SessionsModel::with('services')->findOrFail($id);

        // dd($session);
        return view('Admin.Clinic_Sessions.edit',compact('session','services','patients','doctors'));
      }
      catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'حدث خطأ أثناء تحميل بيانات الجلسة: ' . $e->getMessage());
      }
    }

    public function show($id){
      try {
        $session=Clinic_SessionsModel::with('services','doctor','patient')->findOrFail($id);
        return view('Admin.Clinic_Sessions.show',compact('session'));
      }
      catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'حدث خطأ أثناء تحميل بيانات الجلسة: ' . $e->getMessage());
      }  
    }
        
    public function update(Request $request, $id){
       
        $request->validate([
                    'patient_id' => 'required|exists:patients,id',
                    'doctor_id' => 'required|exists:doctor,id',
                    'session_date' => 'required|date',
                    'start_time' => 'required',
                    'end_time' => 'nullable',
                    'drug' => 'nullable|string',
                    'required_amount' => 'nullable|numeric',
                    'cash_payment' => 'nullable|numeric',
                    'card_payment' => 'nullable|numeric',
                    'total_amount' => 'nullable|numeric',
                    'name_of_bank_account' => 'nullable|string',
                    'remaining_amount' => 'nullable|numeric',
                    'xray_image' => 'nullable|image|max:2048',
                    'notes' => 'nullable|string',
                ]);

                $session = Clinic_SessionsModel::findOrFail($id);

                // رفع صورة الأشعة الجديدة (مع حذف القديمة إن وُجدت)
                
        try {
                if ($request->hasFile('xray_image')) {
                    if ($session->xray_image) {
                        Storage::disk('public')->delete($session->xray_image);
                    }
                    $session->xray_image = $request->file('xray_image')->store('xray_images', 'public');
                }
                // تحديث البيانات
                $session->update([
                    'patient_id' => $request->patient_id,
                    'doctor_id' => $request->doctor_id,
                    'session_date' => $request->session_date,
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                    'drug' => $request->drug,
                    'required_amount' => $request->required_amount ?? 0,
                    'cash_payment' => $request->cash_payment ?? 0,
                    'card_payment' => $request->card_payment ?? 0,
                    'total_amount' => $request->total_amount ?? 0,
                    'name_of_bank_account' => $request->name_of_bank_account,
                    'remaining_amount' => $request->remaining_amount ?? 0,
                    'notes' => $request->notes,
                    // 'xray_image' تم تحديثه أعلاه إذا لزم الأمر
                ]);

                // تحديث الخدمات المرتبطة
                if ($request->has('services') && is_array($request->services)) {
                    $servicesData = [];
                    foreach ($request->services as $service) {
                        $servicesData[$service['id']] = ['tooth_number' => $service['tooth_number'] ?? null];
                    }
                    $session->services()->sync($servicesData);
                }

                return redirect()->route('sessions.index')->with('success', 'تم تحديث الجلسة بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث الجلسة: ' . $e->getMessage());
        }
    }
    
    public function destroy($id){
      try {
        $session=Clinic_SessionsModel::find($id);
        $session->services()->detach();
        $session->forceDelete();

        return redirect()
                ->route('sessions.index')
                ->with('success', 'تم حذف الجلسة بنجاح');
      } 
      catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء حذف الجلسة: ' . $e->getMessage());
      }
         
    }
}