<?php

namespace App\Http\Controllers;

use App\Models\AppointmentsModel;
use App\Models\Clinic_SessionsModel;
use App\Models\DoctorModel;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class DoctorController extends Controller{
    
     public function index(Request $request){
        try {
        $doctors=DoctorModel::query();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $doctors->where('name', 'like', "%{$search}%");
        }

        $doctors = $doctors->latest()->paginate(10);

        return view('Admin.Doctor.index',compact('doctors'));
        }
        catch (\Exception $e) {
            Log::error('Error fetching doctors: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء جلب بيانات الأطباء.');
        }
    }
    
    public function create(){
        return view('Admin.Doctor.create');
    }

    public function store(Request $request){

        $validated=$request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|unique:doctor,phone|regex:/^\d{10}$/',
            'date_of_birth' => 'nullable|date|before_or_equal:today',
            // 'username' => 'required|string|max:255|unique:doctor,username|regex:/^[a-zA-Z0-9_]+$/',
            // 'password' => 'required|string|min:6|confirmed',
        ],[
            'name.required' => 'الرجاء إدخال اسم الطبيب.',
            'specialization.required' => 'الرجاء إدخال التخصص.',
            // 'username.required' => 'الرجاء إدخال اسم المستخدم.',
            // 'username.unique' => 'اسم المستخدم مستخدم من قبل، الرجاء اختيار اسم آخر.',
            // 'password.required' => 'الرجاء إدخال كلمة المرور.',
            // 'password.min' => 'كلمة المرور يجب أن تكون على الأقل 6 أحرف.',
            // 'password.confirmed' => 'تأكيد كلمة المرور لا يطابق كلمة المرور.',
            'phone.numeric' => 'رقم الهاتف يجب أن يكون أرقامًا فقط.',
            'phone.unique' => 'رقم الهاتف مستخدم من قبل، الرجاء اختيار رقم آخر.',
            'date_of_birth.date' => 'صيغة تاريخ الميلاد غير صحيحة.',
            // 'username.regex' => 'اسم المستخدم يجب أن يحتوي على حروف إنجليزية وأرقام فقط بدون فراغات.',
            'date_of_birth.before_or_equal' => 'تاريخ الميلاد لا يمكن أن يكون في المستقبل.',
            'phone.digits' => 'رقم الهاتف يجب أن يكون مكونًا من 10 أرقام بالضبط.',

        ]);
      try {
        $doctor=DoctorModel::query()->create([
            'name' => $validated['name'],
            'specialization' => $validated['specialization'],
            'address' => $validated['address'],
            'phone' => $validated['phone'],
            'date_of_birth' => $validated['date_of_birth']]);
            // User::create([
            //     'name' => $doctor->name,
            //     'username' => $validated['username'],
            //     'password' => bcrypt($validated['password']),
            //     'type' => 'doctor',
            //     'type_id' => $doctor->id,
            // ]);
        
        return redirect()
                ->route('admin.doctor.index')
                ->with('success', 'تم إضافة الطبيب بنجاح');
        }
      catch (\Exception $e) {
            Log::error('Error creating doctor: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء إضافة الطبيب: ' . $e->getMessage());
        } 
    }

    public function edit($id){
      try {
        $doctor = DoctorModel::query()->findOrFail($id);
        return view('Admin.Doctor.edit',compact('doctor'));
      }
      catch (\Exception $e) {
        Log::error('Error fetching doctor for edit: ' . $e->getMessage(), [
            'exception' => $e,
        ]);
        return redirect()
            ->back()
            ->with('error', 'حدث خطأ أثناء تحميل بيانات الطبيب: ' . $e->getMessage());
      }
    }

    public function show(Request $request,$id){
      try {  
        $doctor = DoctorModel::findOrFail($id);
        $sessions=Clinic_SessionsModel::with('services')->where('doctor_id', $doctor->id)->paginate(2);

        $sortBy = $request->get('sort_by', 'appointment_date');
        $sortOrder = $request->get('order', 'asc');

        $appointments =AppointmentsModel::with('patient')
        ->where('doctor_id', $doctor->id)
        ->orderBy($sortBy, $sortOrder)
        ->paginate(2)
        ->appends(request()->query()); // عدد المواعيد لكل صفحة

        return view('admin.doctor.show', compact('doctor','sessions','appointments'));
      }
      catch (\Exception $e) {
        Log::error('Error fetching doctor details: ' . $e->getMessage(), [
            'exception' => $e,
        ]);
        return redirect()
            ->back()
            ->with('error', 'حدث خطأ أثناء تحميل بيانات الطبيب: ' . $e->getMessage());
      }
    }

    public function update(Request $request, $id){
        
        $doctor = DoctorModel::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => [
            'nullable',
            'digits:10',
            Rule::unique('doctor', 'phone')->ignore($doctor->id),],
            'date_of_birth' => ['nullable', 'date', 'before_or_equal:today'],
            // 'username' => [
            //     'required', 'string', 'max:255',
            //     Rule::unique('doctor', 'username')->ignore($doctor->id),
            //     'regex:/^[a-zA-Z0-9_]+$/',
            // ],
            // 'password' => 'nullable|string|min:6|confirmed',
        ],[
                'name.required' => 'الرجاء إدخال اسم الطبيب.',
                'specialization.required' => 'الرجاء إدخال التخصص.',
                // 'username.required' => 'الرجاء إدخال اسم المستخدم.',
                // 'username.unique' => 'اسم المستخدم مستخدم من قبل، الرجاء اختيار اسم آخر.',
                // 'password.required' => 'الرجاء إدخال كلمة المرور.',
                // 'password.min' => 'كلمة المرور يجب أن تكون على الأقل 6 أحرف.',
                // 'password.confirmed' => 'تأكيد كلمة المرور لا يطابق كلمة المرور.',
                'phone.numeric' => 'رقم الهاتف يجب أن يكون أرقامًا فقط.',
                'phone.unique' => 'رقم الهاتف مستخدم من قبل، الرجاء اختيار رقم آخر.',
                'date_of_birth.date' => 'صيغة تاريخ الميلاد غير صحيحة.',
                // 'username.regex' => 'اسم المستخدم يجب أن يحتوي على حروف إنجليزية وأرقام فقط بدون فراغات.',
                'date_of_birth.before_or_equal' => 'تاريخ الميلاد لا يمكن أن يكون في المستقبل.',
                'phone.digits' => 'رقم الهاتف يجب أن يكون مكونًا من 10 أرقام بالضبط.',

        ]);
      try {  
        $doctor->name = $validated['name'];
        $doctor->specialization = $validated['specialization'];
        $doctor->address = $validated['address'] ?? null;
        $doctor->phone = $validated['phone'] ?? null;
        $doctor->date_of_birth = $validated['date_of_birth'] ?? null;
        // $doctor->username = $validated['username'];

        // تحديث كلمة المرور فقط إذا أدخلها المستخدم
        // if (!empty($validated['password'])) {
        //     $doctor->password = bcrypt($validated['password']);
        // }

        $doctor->save();

        return redirect()->route('admin.doctor.index')->with('success', 'تم تعديل بيانات الطبيب بنجاح');
      } 
      catch (\Exception $e) {
            Log::error('Error updating doctor: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تعديل بيانات الطبيب: ' . $e->getMessage());
      }  
    }

    public function destroy($id){
        try {

        DoctorModel::query()->find($id)->delete();
        
        return redirect()
                ->route('admin.doctor.index')
                ->with('success', 'تم حذف الطبيب بنجاح');

        }catch (\Exception $e) {
            Log::error('Error deleting doctor: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء حذف الطبيب : ' );
        }  
    }

}
