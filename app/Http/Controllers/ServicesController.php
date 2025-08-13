<?php

namespace App\Http\Controllers;

use App\Models\ServicesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ServicesController extends Controller{
   
    public function index(Request $request){
        try {

        $services=ServicesModel::query();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $services->where('name', 'like', "%{$search}%");
        }

        $services = $services->latest()->paginate(10);


        return view('Admin.Clinic-services.index',compact('services'));
        }
        catch (\Exception $e) {
            Log::error('Error fetching services: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء جلب بيانات الخدمات.');
        }
    }

    public function create(){
        return view('Admin.Clinic-services.create');
    }

    public function store(Request $request){

        $validated=$request->validate([
            'name' => 'required|string|max:255|regex:/^[\pL\s]+$/u',
            'description' => 'nullable|string|max:255',         
        ],[
            'name.required' => 'الرجاء إدخال اسم الخدمة.',
            'name.regex' => 'الاسم يجب أن يحتوي على أحرف فقط بدون أرقام أو رموز.',
            'name.string' => 'اسم الخدمة يجب أن يكون نصًا.',


        ]);
        try {
        $services=ServicesModel::query()->create([
            'name' => $validated['name'],
            'description' => $validated['description'],
        ]);
        
        return redirect()
                ->route('admin.services.index')
                ->with('success', 'تم إضافة الخدمة بنجاح');
        }
        catch (\Exception $e) {
            Log::error('Error creating service: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إضافة الخدمة: ' . $e->getMessage());
        }
    } 

    public function edit($id){
        $services = ServicesModel::query()->findOrFail($id);
        return view('Admin.Clinic-services.edit',compact('services'));
    }
    
    public function update(Request $request, $id){
        $services = ServicesModel::findOrFail($id);

        $validated=$request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',         
        ],[
            'name.required' => 'الرجاء إدخال اسم الخدمة.',
            'name.string' => 'اسم الخدمة يجب أن يكون نصًا.',

        ]);
        try {
        $services->name = $validated['name'];
        $services->description = $validated['description'];

        $services->save();

        return redirect()->route('admin.services.index')->with('success', 'تم تعديل بيانات الخدمة بنجاح');

        }   
        catch (\Exception $e) {
            Log::error('Error updating service: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تعديل بيانات الخدمة: ' . $e->getMessage());
        }
    }
    
    public function destroy($id){
        try {

        ServicesModel::query()->find($id)->delete();
        
        return redirect()
                ->route('admin.services.index')
                ->with('success', 'تم حذف الخدمة بنجاح');

        }catch (\Exception $e) {
            Log::error('Error deleting doctor: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء حذف الخدمة : ' );
        }  
    }

}
