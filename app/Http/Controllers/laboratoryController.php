<?php

namespace App\Http\Controllers;

use App\Models\lab_PaymentModel;
use App\Models\Laboratory_purchasesModel;
use App\Models\laboratoryModel;
use App\Models\StatementItemModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class laboratoryController extends Controller
{
    public function index(Request $request){
      try {
        $Laboratorys = laboratoryModel::query();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $Laboratorys->where('name', 'like', "%{$search}%");
        }

        $Laboratorys = $Laboratorys->latest()->paginate(4);

        return view('Admin.laboratory.index', compact('Laboratorys'));
        }
      catch (\Exception $e) {
            Log::error('Error fetching doctors: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء جلب بيانات المعامل.');
        }
    }

    public function create(){
        return view('Admin.laboratory.create');
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|unique:laboratories,name|string|max:255',
            'location' => 'nullable|string|max:255',
            'contact_number' => 'required|numeric|unique:laboratories,contact_number|regex:/^\d{10}$/',
        ],[ 
            'name.required' => 'الرجاء إدخال اسم المعمل.',
            'name.unique' => 'اسم المعمل مستخدم من قبل، الرجاء اختيار اسم آخر.',
            'location.string' => 'الموقع يجب أن يكون نصًا.',
            'location.max' => 'الموقع يجب أن لا يتجاوز 255 حرفًا.',
            'contact_number.required' => 'الرجاء إدخال رقم الهاتف.',
            'contact_number.numeric' => 'رقم الهاتف يجب أن يكون أرقامًا فقط.',
            'contact_number.unique' => 'رقم الهاتف مستخدم من قبل، الرجاء اختيار رقم آخر.',
            'contact_number.regex' => 'رقم الهاتف يجب أن يكون مكونًا من 10 أرقام بالضبط.',

        ]);
      try {
        laboratoryModel::create($request->all());
        return redirect()->route('laboratory.index')->with('success', 'تم إضافة المعمل بنجاح');
        }
      catch (\Exception $e) {
            Log::error('Error storing laboratory: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء إضافة المعمل.');
        }
    }
    
    public function edit($id){
        $laboratory = laboratoryModel::findOrFail($id);
        return view('Admin.laboratory.edit', compact('laboratory'));       

    }

    public function update(Request $request, $id){
        $request->validate([
            'name' => 'required|unique:laboratories,name|string|max:255',
            'location' => 'nullable|string|max:255',
            'contact_number' => 'required|numeric|unique:laboratories,contact_number|regex:/^\d{10}$/',
        ],[ 
            'name.required' => 'الرجاء إدخال اسم المعمل.',
            'name.unique' => 'اسم المعمل مستخدم من قبل، الرجاء اختيار اسم آخر.',
            'location.string' => 'الموقع يجب أن يكون نصًا.',
            'location.max' => 'الموقع يجب أن لا يتجاوز 255 حرفًا.',
            'contact_number.required' => 'الرجاء إدخال رقم الهاتف.',
            'contact_number.numeric' => 'رقم الهاتف يجب أن يكون أرقامًا فقط.',
            'contact_number.unique' => 'رقم الهاتف مستخدم من قبل، الرجاء اختيار رقم آخر.',
            'contact_number.regex' => 'رقم الهاتف يجب أن يكون مكونًا من 10 أرقام بالضبط.',
        ]);
      try {
        $Laboratory = laboratoryModel::findOrFail($id);
        $Laboratory->update($request->all());
        return redirect()->route('laboratory.index')->with('success', 'تم تحديث بيانات المعمل بنجاح');
      }
        catch (\Exception $e) {
            Log::error('Error updating laboratory: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء تحديث بيانات المعمل: ' . $e->getMessage());
      }
    }

    public function destroy($id){
        try {
        $Laboratory = laboratoryModel::findOrFail($id);
        $Laboratory->delete();
        return redirect()->route('laboratory.index')->with('success', 'تم حذف المعمل بنجاح');   
      }   
        catch (\Exception $e) {
            Log::error('Error deleting laboratory: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء حذف المعمل: ' . $e->getMessage());
        }
    }

    public function laboratoryStatement($laboratory_id){
        try {

        $lab = laboratoryModel::findOrFail($laboratory_id);

        $labItems = Laboratory_purchasesModel::where('laboratory_id', $laboratory_id)
            ->select('purchase_date as date', 'total_amount as amount')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->date,
                    'amount' => $item->amount,
                    'type' => 'فاتورة شراء',
                    'balance' => null,
                    
                ];
            });

        $paymentItems = lab_PaymentModel::where('laboratories_id', $laboratory_id)
            ->select('payment_date as date', 'total as amount')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->date,
                    'amount' => $item->amount,
                    'type' => 'دفعة',
                    'balance' => null
                    
                ];
            });

        // دمج وترتيب حسب التاريخ
        $statement = $labItems->merge($paymentItems)->sortBy('date')->values();

        // حساب الرصيد
        $balance = 0;
        
            $statement = $statement->map(function ($item) use (&$balance) {
            if ($item['type'] == 'فاتورة شراء') {
                $balance -= $item['amount'];
            } else {
                $balance += $item['amount'];
            }

            $item['balance'] = $balance;
            return $item;
            });

        return view('Admin.laboratory.statement', compact('statement', 'balance', 'lab'));
        }
           
        catch (\Exception $e) {
                Log::error('Error generating laboratory statement: ' . $e->getMessage(), [
                    'exception' => $e,
                ]);
                return redirect()
                    ->back()
                    ->with('error', 'حدث خطأ أثناء إنشاء كشف الحساب للمعمل: ' . $e->getMessage());
        }
        
        }
    }
