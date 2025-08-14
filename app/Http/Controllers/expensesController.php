<?php

namespace App\Http\Controllers;

use App\Models\expensesModel;
use Illuminate\Http\Request;

class expensesController extends Controller
{
    public function index(){
        try {
            $from = request()->query('from', date('Y-m-d'));
            $to = request()->query('to', date('Y-m-d'));

            // Validate the date range
            if (!$from || !$to) {
                return response()->json(['error' => 'Invalid date range'], 400);
            }
        
        $expenses = expensesModel::whereBetween('date', [$from, $to])->paginate(10);
        return view('Admin.expenses.index', compact('expenses'));
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء جلب بيانات المصاريف.');
        }
    }

    public function create(){
        return view('Admin.expenses.create');
    }

    public function store(Request $request){
        // dd($request->all());
        $request->validate([
            'date'=> 'required|date',
            'pay_to'=> 'required|string',
            'paid_cash'=> 'nullable|numeric',
            'paid_card'=> 'nullable|numeric',
            'name_of_bank_account'=> 'nullable|string',
            'note'=> 'nullable|string',
        ]);

        $total = $request->paid_cash + $request->paid_card;
        try {
        expensesModel::create([
            'date' => $request->date,
            'pay_to' => $request->pay_to,
            'paid_cash' => $request->paid_cash ?? 0,
            'paid_card' => $request->paid_card ?? 0,
            'name_of_bank_account' => $request->name_of_bank_account,
            'total' => $total,
            'note' => $request->note,
        ]);
        // return   dd($request->all());

        return redirect()->route('expenses.index')->with('success', 'تم إضافة الدفعة بنجاح.');
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إضافة الدفعة: ' . $e->getMessage());
        }
    }

    public function edit($id){

        $expenses = expensesModel::findOrFail($id);

        return view('Admin.expenses.edit', compact('expenses'));
    }

    public function update(Request $request, $id){

        $request->validate([
                'date'=> 'required|date',
                'pay_to'=> 'required|string',
                'paid_cash'=> 'nullable|numeric',
                'paid_card'=> 'nullable|numeric',
                'name_of_bank_account'=> 'nullable|string',
                'note'=> 'nullable|string',
            ]);

        $total = $request->paid_cash + $request->paid_card;

        try {
            $expenses = expensesModel::findOrFail($id);

            $expenses->update([
                'date' => $request->date,
                'pay_to' => $request->pay_to,
                'paid_cash' => $request->paid_cash ?? 0,
                'paid_card' => $request->paid_card ?? 0,
                'name_of_bank_account' => $request->name_of_bank_account,
                'total' => $total,
                'note' => $request->note,
            ]);

            return redirect()->route('expenses.index')->with('success', 'تم تعديل الدفعة بنجاح.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تعديل الدفعة: ' . $e->getMessage());
        }
    }

    public function destroy($id){
        try {
            $expenses = expensesModel::findOrFail($id);
            $expenses->delete(); // إذا تستخدم soft delete، سيتم عمل soft delete
            return redirect()->route('expenses.index')->with('success', 'تم حذف الدفعة بنجاح.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف الدفعة: ' . $e->getMessage());
        }
    }


}
     





