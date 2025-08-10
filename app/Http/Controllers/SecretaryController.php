<?php

namespace App\Http\Controllers;

use App\Models\SecretaryModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Termwind\Components\Raw;

class SecretaryController extends Controller{

    public function index(Request $request){
      try {  
        $secretaries=SecretaryModel::query();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $secretaries->where('name', 'like', "%{$search}%");
        }

        $secretaries = $secretaries->latest()->paginate(1);

        return view('Admin.Secretary.index',compact('secretaries'));
      }
      catch (\Exception $e) {
        Log::error('Error fetching secretaries: ' . $e->getMessage(), [
            'exception' => $e,
        ]);
        return redirect()
            ->back()
            ->with('error', 'حدث خطأ أثناء جلب بيانات الموظفين.');
      }
    }
    
    public function create(){
        return view('Admin.Secretary.create');
    }

    public function store(Request $request){
        $validated=$request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|unique:doctor,phone|regex:/^\d{10}$/',
            'date_of_birth' => 'nullable|date|before_or_equal:today',
            'username' => 'required|string|max:255|unique:doctor,username|regex:/^[a-zA-Z0-9_]+$/',
            'password' => 'required|string|min:6|confirmed',
        ],[
            'name.required' => 'الرجاء إدخال اسم الموظف.',
            'specialization.required' => 'الرجاء إدخال التخصص.',
            'username.required' => 'الرجاء إدخال اسم المستخدم.',
            'username.unique' => 'اسم المستخدم مستخدم من قبل، الرجاء اختيار اسم آخر.',
            'password.required' => 'الرجاء إدخال كلمة المرور.',
            'password.min' => 'كلمة المرور يجب أن تكون على الأقل 6 أحرف.',
            'password.confirmed' => 'تأكيد كلمة المرور لا يطابق كلمة المرور.',
            'phone.numeric' => 'رقم الهاتف يجب أن يكون أرقامًا فقط.',
            'phone.unique' => 'رقم الهاتف مستخدم من قبل، الرجاء اختيار رقم آخر.',
            'date_of_birth.date' => 'صيغة تاريخ الميلاد غير صحيحة.',
            'username.regex' => 'اسم المستخدم يجب أن يحتوي على حروف إنجليزية وأرقام فقط بدون فراغات.',
            'date_of_birth.before_or_equal' => 'تاريخ الميلاد لا يمكن أن يكون في المستقبل.',
            'phone.digits' => 'رقم الهاتف يجب أن يكون مكونًا من 10 أرقام بالضبط.',

        ]);
        
      try {
         // إنشاء موظف جديد باستخدام البيانات المرسلة
        $secretary=SecretaryModel::query()->create([
            'name' => $validated['name'],
            'specialization' => $validated['specialization'],
            'address' => $validated['address'],
            'phone' => $validated['phone'],
            'date_of_birth' => $validated['date_of_birth'],
            // 'username' => $validated['username'],
            // 'password' => bcrypt($validated['password']),
            ]);
        User::create([
                'name' => $secretary->name,
                'username' => $validated['username'],
                'password' => bcrypt($validated['password']),
                'type' => 'secretary',
                'type_id' => $secretary->id,
            ]);
        
        return redirect()
            ->route('admin.secretary.index')
            ->with('success', 'تم إضافة الموظف بنجاح');
      } 
      catch (\Exception $e) {
        Log::error('Error creating secretary: ' . $e->getMessage(), [
            'exception' => $e,
        ]);
        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'حدث خطأ أثناء إضافة الموظف: ' . $e->getMessage());
      }
    }
    
    public function edit($id){
      try {  
        $secretary = SecretaryModel::query()->findOrFail($id);
        return view('Admin.Secretary.edit',compact('secretary'));
      }
      catch (\Exception $e) {
        Log::error('Error fetching secretary for edit: ' . $e->getMessage(), [
            'exception' => $e,
        ]);
        return redirect()
            ->back()
            ->with('error', 'حدث خطأ أثناء تحميل بيانات الموظف: ' . $e->getMessage());
      }
    }

    public function show(Request $request,$id){
      try{  
        $secretary = SecretaryModel::findOrFail($id);

        return view('Admin.Secretary.show', compact('secretary'));
      }
      catch (\Exception $e) {
        Log::error('Error fetching secretary for show: ' . $e->getMessage(), [
            'exception' => $e,
        ]);
        return redirect()
            ->back()
            ->with('error', 'حدث خطأ أثناء تحميل بيانات الموظف: ' . $e->getMessage());
      }
    }

    public function update(Request $request, $id){
        
        $secretary = SecretaryModel::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => [
            'nullable',
            'digits:10',
            Rule::unique('doctor', 'phone')->ignore($secretary->id),],
            'date_of_birth' => ['nullable', 'date', 'before_or_equal:today'],
            'username' => [
                'required', 'string', 'max:255',
                Rule::unique('doctor', 'username')->ignore($secretary->id),
                'regex:/^[a-zA-Z0-9_]+$/',
            ],
            'password' => 'nullable|string|min:6|confirmed',
        ],[
                'name.required' => 'الرجاء إدخال اسم الموظف.',
                'specialization.required' => 'الرجاء إدخال التخصص.',
                'username.required' => 'الرجاء إدخال اسم المستخدم.',
                'username.unique' => 'اسم المستخدم مستخدم من قبل، الرجاء اختيار اسم آخر.',
                'password.required' => 'الرجاء إدخال كلمة المرور.',
                'password.min' => 'كلمة المرور يجب أن تكون على الأقل 6 أحرف.',
                'password.confirmed' => 'تأكيد كلمة المرور لا يطابق كلمة المرور.',
                'phone.numeric' => 'رقم الهاتف يجب أن يكون أرقامًا فقط.',
                'phone.unique' => 'رقم الهاتف مستخدم من قبل، الرجاء اختيار رقم آخر.',
                'date_of_birth.date' => 'صيغة تاريخ الميلاد غير صحيحة.',
                'username.regex' => 'اسم المستخدم يجب أن يحتوي على حروف إنجليزية وأرقام فقط بدون فراغات.',
                'date_of_birth.before_or_equal' => 'تاريخ الميلاد لا يمكن أن يكون في المستقبل.',
                'phone.digits' => 'رقم الهاتف يجب أن يكون مكونًا من 10 أرقام بالضبط.',

        ]);
        try {   
        $secretary->name = $validated['name'];
        $secretary->specialization = $validated['specialization'];
        $secretary->address = $validated['address'] ?? null;
        $secretary->phone = $validated['phone'] ?? null;
        $secretary->date_of_birth = $validated['date_of_birth'] ?? null;
        $secretary->username = $validated['username'];

        // تحديث كلمة المرور فقط إذا أدخلها المستخدم
        if (!empty($validated['password'])) {
            $secretary->password = bcrypt($validated['password']);
        }

        $secretary->save();

        return redirect()->route('admin.secretary.index')->with('success', 'تم تعديل بيانات الموظف بنجاح');
        } 
        catch (\Exception $e) {
            Log::error('Error updating secretary: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تعديل بيانات الموظف: ' . $e->getMessage());
        }
    }

    public function destroy($id){
        try {

        SecretaryModel::query()->find($id)->delete();
        
        return redirect()
                ->route('admin.secretary.index')
                ->with('success', 'تم حذف الموظف بنجاح');

        }catch (\Exception $e) {
            Log::error('Error deleting doctor: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء حذف الموظف : ' );
        }  
    }

}





