<?php

namespace App\Http\Controllers;

use App\Models\storehouseModel;
use App\Models\Warehouse_paymentsModel;
use App\Models\Warehouse_purchasesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class storehouseController extends Controller
{
    public function index(Request $request){
      try {
        $storehouses = storehouseModel::query();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $storehouses->where('name', 'like', "%{$search}%");
        }

        $storehouses = $storehouses->latest()->paginate(4);

        return view('Admin.storehouse.index', compact('storehouses'));
        }
      catch (\Exception $e) {
            Log::error('Error fetching doctors: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء جلب بيانات المستودع.');
        }
    }

    public function create(){
        return view('Admin.storehouse.create');
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|unique:storehouses,name|string|max:255',
            'location' => 'nullable|string|max:255',
            'contact_number' => 'required|numeric|unique:storehouses,contact_number|regex:/^\d{10}$/',
        ],[ 
            'name.required' => 'الرجاء إدخال اسم المستودع.',
            'name.unique' => 'اسم المستودع مستخدم من قبل، الرجاء اختيار اسم آخر.',
            'location.string' => 'الموقع يجب أن يكون نصًا.',
            'location.max' => 'الموقع يجب أن لا يتجاوز 255 حرفًا.',
            'contact_number.required' => 'الرجاء إدخال رقم الهاتف.',
            'contact_number.numeric' => 'رقم الهاتف يجب أن يكون أرقامًا فقط.',
            'contact_number.unique' => 'رقم الهاتف مستخدم من قبل، الرجاء اختيار رقم آخر.',
            'contact_number.regex' => 'رقم الهاتف يجب أن يكون مكونًا من 10 أرقام بالضبط.',

        ]);
      try {
        storehouseModel::create($request->all());
        return redirect()->route('storehouse.index')->with('success', 'تم إضافة المستودع بنجاح');
        }
      catch (\Exception $e) {
            Log::error('Error storing laboratory: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء إضافة المستودع.');
        }
    }
    
    public function edit($id){
        $storehouse = storehouseModel::findOrFail($id);
        return view('Admin.storehouse.edit', compact('storehouse'));       

    }

    public function update(Request $request, $id){
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('storehouses', 'name')->ignore($id),
            ],
            'location' => 'nullable|string|max:255',
            'contact_number' => [
            'required',
            'numeric',
            'regex:/^([0-9]{10})$/',
            Rule::unique('storehouses', 'contact_number')->ignore($id),],
            ],[ 
            'name.required' => 'الرجاء إدخال اسم المستودع.',
            'name.unique' => 'اسم المستودع مستخدم من قبل، الرجاء اختيار اسم آخر.',
            'location.string' => 'الموقع يجب أن يكون نصًا.',
            'location.max' => 'الموقع يجب أن لا يتجاوز 255 حرفًا.',
            'contact_number.required' => 'الرجاء إدخال رقم الهاتف.',
            'contact_number.numeric' => 'رقم الهاتف يجب أن يكون أرقامًا فقط.',
            'contact_number.unique' => 'رقم الهاتف مستخدم من قبل، الرجاء اختيار رقم آخر.',
            'contact_number.regex' => 'رقم الهاتف يجب أن يكون مكونًا من 10 أرقام بالضبط.',
        ]);
      try {
        $storehouse = storehouseModel::findOrFail($id);
        $storehouse->update([
            'name' => $request->name,
            'location' => $request->location,
            'contact_number' => $request->contact_number,
        ]);        return redirect()->route('storehouse.index')->with('success', 'تم تحديث بيانات المستودع بنجاح');
      }
        catch (\Exception $e) {
            Log::error('Error updating laboratory: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء تحديث بيانات المستودع: ' . $e->getMessage());
      }
    }

    public function destroy($id){
        try {
        $storehouse = storehouseModel::findOrFail($id);
        $storehouse->delete();
        return redirect()->route('storehouse.index')->with('success', 'تم حذف المستودع بنجاح');   
      }   
        catch (\Exception $e) {
            Log::error('Error deleting laboratory: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء حذف المستودع: ' . $e->getMessage());
        }
    }

    public function storehouseStatement($storehouse_id){

        $storehouse = storehouseModel::findOrFail($storehouse_id);

        $WarehouseItems = Warehouse_purchasesModel::where('storehouses_id', $storehouse_id)
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

        $paymentItems = Warehouse_paymentsModel::where('storehouses_id', $storehouse_id)
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
        $statement = $WarehouseItems->merge($paymentItems)->sortBy('date')->values();

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

        return view('Admin.storehouse.statement', compact('statement', 'balance', 'storehouse'));
    }
}
