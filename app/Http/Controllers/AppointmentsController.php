<?php

namespace App\Http\Controllers;

use App\Models\AppointmentsModel;
use App\Models\DoctorModel;
use App\Models\PatientModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use function Pest\Laravel\get;

class AppointmentsController extends Controller{

    public function index(Request $request){
        try {
            
            $appointments=AppointmentsModel::with(['patient','doctor']);

            if ($request->has('status') && is_array($request->status)) {
                $appointments->whereIn('status', $request->status);
            }
            
            if ($request->filled('search')) {

                $search = trim($request->search);

                // البحث أولاً في المرضى
                $patient = \App\Models\PatientModel::where('name', 'like', "%{$search}%")->first();

                // إذا لم نجد مريض، نبحث في الأطباء
                $doctor = \App\Models\DoctorModel::where('name', 'like', "%{$search}%")->first();

                if ($patient) {
                    $appointments->where('patient_id', $patient->id);
                } elseif ($doctor) {
                    $appointments->where('doctor_id', $doctor->id);
                } else {
                    // لا يوجد مطابق => ارجع نتائج فارغة
                    $appointments->whereRaw('0 = 1');
                }
            }
            // الترتيب
            switch ($request->sort) {
                case 'date_asc':
                    $appointments->orderBy('appointment_date', 'asc');
                    break;
                case 'date_desc':
                    $appointments->orderBy('appointment_date', 'desc');
                    break;
                default:
                    $appointments->orderBy('appointment_date');
            }
            

            $appointments = $appointments->paginate(5)->appends($request->all());

            return view('Admin.Appointments.index',compact('appointments'));
        }
        catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تحميل قائمة المواعيد: ' . $e->getMessage());
        }
    }
    
    public function create(){
        try {
            $patients = PatientModel::all(); 
            $doctors = DoctorModel::all();   
            return view('Admin.Appointments.create',compact('patients', 'doctors'));
        } 
        catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تحميل نموذج إضافة الموعد: ' . $e->getMessage());
        }
    }

    public function store(Request $request){

        $validated = $request->validate([
        'patient_id' => 'required',
        'doctor_id' => 'required',
        'appointment_date' => 'required|date|after_or_equal:today',
        'start_time' => 'required|date_format:H:i',
        'end_time' => 'required|date_format:H:i|after:start_time',
        'notes' => 'nullable|string|max:1000',
        ],[

        'appointment_date.required' => 'يرجى تحديد تاريخ الموعد.',
        'appointment_date.date' => 'تنسيق التاريخ غير صحيح.',
        'appointment_date.after_or_equal' => 'تاريخ الموعد يجب أن يكون اليوم أو في المستقبل.',

        'start_time.required' => 'يرجى إدخال وقت البداية.',
        'start_time.date_format' => 'وقت البداية يجب أن يكون بتنسيق 24 ساعة (مثال: 14:30).',

        'end_time.required' => 'يرجى إدخال وقت الانتهاء.',
        'end_time.date_format' => 'وقت الانتهاء يجب أن يكون بتنسيق 24 ساعة (مثال: 15:30).',
        'end_time.after' => 'وقت الانتهاء يجب أن يكون بعد وقت البداية.',

        'notes.string' => 'يجب أن تكون الملاحظات نصًا.',
        'notes.max' => 'الملاحظات يجب ألا تتجاوز 1000 حرف.'
        ]);

        try {
            $appointments=AppointmentsModel::query()->create([
            'patient_id' => $validated['patient_id'],
            'doctor_id' => $validated['doctor_id'],
            'appointment_date' => $validated['appointment_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'status' => 'pending', // الحالة الافتراضية للموعد
            'notes' => $validated['notes'] ?? null,
            ]);

            return redirect()->route('appointments.index')->with('success', 'تمت إضافة الموعد بنجاح');
         
        } 
        catch (\Exception $e) {
        // يمكنك هنا تسجيل الخطأ في السجلات أو عرض رسالة للمطور إن كنت في بيئة dev
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء حفظ الموعد: ' . $e->getMessage());
        }    
    }
    
    public function edit($id){

        try {
            $appointment = AppointmentsModel::findorFail($id);
            $patients = PatientModel::all(); 
            $doctors = DoctorModel::all();   

            return view('Admin.Appointments.edit',compact('appointment','patients','doctors'));
        } 
        catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تحميل بيانات الموعد: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id){
        try {
            $appointment = AppointmentsModel::findOrFail($id);

            $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctor,id',
            'appointment_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'status'=> 'required',
            'notes' => 'nullable|string|max:1000',
            ],[

            'patient_id.required' => 'يرجى اختيار المريض.',
            'patient_id.exists' => 'المريض المحدد غير موجود.',
            
            'doctor_id.required' => 'يرجى اختيار الطبيب.',
            'doctor_id.exists' => 'الطبيب المحدد غير موجود.',

            'appointment_date.required' => 'يرجى تحديد تاريخ الموعد.',
            'appointment_date.date' => 'تنسيق التاريخ غير صحيح.',
            'appointment_date.after_or_equal' => 'تاريخ الموعد يجب أن يكون اليوم أو بعده.',

            'start_time.required' => 'يرجى إدخال وقت البداية.',
            'start_time.date_format' => 'وقت البداية يجب أن يكون بتنسيق 24 ساعة (مثال: 14:30).',

            'end_time.required' => 'يرجى إدخال وقت الانتهاء.',
            'end_time.date_format' => 'وقت الانتهاء يجب أن يكون بتنسيق 24 ساعة (مثال: 15:30).',
            'end_time.after' => 'وقت الانتهاء يجب أن يكون بعد وقت البداية.',
    
            'notes.string' => 'يجب أن تكون الملاحظات نصًا.',
            'notes.max' => 'الملاحظات يجب ألا تتجاوز 1000 حرف.'
            ]);
        

            $appointment->patient_id = $validated['patient_id'];
            $appointment->doctor_id = $validated['doctor_id'];
            $appointment->appointment_date = $validated['appointment_date'] ?? null;
            $appointment->start_time = $validated['start_time'] ;
            $appointment->end_time = $validated['end_time'] ;
            $appointment->status = $validated['status'] ;
            $appointment->notes = $validated['notes'] ?? null;
        

            $appointment->save();

            return redirect()->route('appointments.index')->with('success', 'تم تعديل بيانات الموعد بنجاح');
        } 
        catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تعديل الموعد: ' . $e->getMessage());
        }
    }
   
    public function destroy($id){
        try {

            AppointmentsModel::query()->find($id)->delete();
            
            return redirect()
                    ->route('appointments.index')
                    ->with('success', 'تم حذف الموعد بنجاح');

        }
        catch (\Exception $e) {
            Log::error('Error deleting doctor: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء حذف الموعد : ' );
        }  
    }
}
