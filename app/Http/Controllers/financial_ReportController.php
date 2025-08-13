<?php

namespace App\Http\Controllers;

use App\Models\Clinic_SessionsModel;
use App\Models\expensesModel;
use App\Models\lab_PaymentModel;
use App\Models\Laboratory_purchasesModel;
use App\Models\patient_PaymentModel;
use App\Models\Warehouse_paymentsModel;
use App\Models\Warehouse_purchasesModel;
use Illuminate\Http\Request;

class financial_ReportController extends Controller
{
public function getReport(Request $request)
    {   
        $from = $request->query('from', date('Y-m-d'));
        $to = $request->query('to', date('Y-m-d'));

        // Validate the date range
        if (!$from || !$to) {
            return response()->json(['error' => 'Invalid date range'], 400);
        }

        //الارادات كاش و تطبيق

        $session_cash_payment = Clinic_SessionsModel::whereBetween('session_date', [$from, $to])->sum('cash_payment');
        $session_card_payment = Clinic_SessionsModel::whereBetween('session_date', [$from, $to])->sum('card_payment');

        $patient_cash_payment = patient_PaymentModel::whereBetween('payment_date', [$from, $to])->sum('paid_cash');
        $patient_card_payment = patient_PaymentModel::whereBetween('payment_date', [$from, $to])->sum('paid_card');
        
        $cash_revenue=$session_cash_payment + $patient_cash_payment;
        $card_revenue=$session_card_payment + $patient_card_payment;

        //المصاريف
        $expenses_cash = expensesModel::whereBetween('date', [$from, $to])->sum('paid_cash');
        $expenses_card = expensesModel::whereBetween('date', [$from, $to])->sum('paid_card');
        
        $lab_payments_cash = lab_PaymentModel::whereBetween('payment_date', [$from, $to])->sum('paid_cash');
        $lab_payments_card = lab_PaymentModel::whereBetween('payment_date', [$from, $to])->sum('paid_card');

        $warehouse_payments_cash = Warehouse_paymentsModel::whereBetween('payment_date', [$from, $to])->sum('paid_cash');
        $warehouse_payments_card = Warehouse_paymentsModel::whereBetween('payment_date', [$from, $to])->sum('paid_card'); 
        
        $cash_expenses = $expenses_cash + $lab_payments_cash + $warehouse_payments_cash;
        $card_expenses = $expenses_card + $lab_payments_card + $warehouse_payments_card;

        //الديون علينا
        $Laboratory_purchases = Laboratory_purchasesModel::whereBetween('purchase_date', [$from, $to])->sum('total_amount');
        $Warehouse_purchases = Warehouse_purchasesModel::whereBetween('purchase_date', [$from, $to])->sum('total_amount');

        //الديون لنا
        $session = Clinic_SessionsModel::whereBetween('session_date', [$from, $to])->sum('remaining_amount');
    $debts_to_us2 =$session - ($patient_cash_payment + $patient_card_payment);

        //الديون علينا
        $total_pay = $lab_payments_cash + $warehouse_payments_cash+$lab_payments_card + $warehouse_payments_card;
                 
        $total_purchases=$Laboratory_purchases + $Warehouse_purchases;
        
        $debts_to_have2 =$total_purchases - $total_pay;


        // $debts_to_have2=0;
        // $debts_to_us2=0;

        // if($debts_to_us < 0){
        //     $debts_to_have2 = abs($debts_to_us);    
        // }else{
        //     $debts_to_us2 = abs($debts_to_us);
        // }
        // if($debts_to_have < 0){
        //     $debts_to_us2 = abs($debts_to_have);
        // }else{
        //     $debts_to_have2 = abs($debts_to_have);
        // }
        $profit_cash = $cash_revenue - $cash_expenses;
 $profit_card = $card_revenue - $card_expenses;

 $total_profit = $profit_cash + $profit_card;
 //الربح بعد تحصيل الديون
 $true_total_profit = $total_profit + $debts_to_us2 - $debts_to_have2;

        $cash_box= $cash_revenue - $cash_expenses;
        $card_box= $card_revenue - $card_expenses;
    return view('admin.reports.index', [
    'cash_revenue' => $cash_revenue,
    'card_revenue' => $card_revenue,
    'cash_expenses' => $cash_expenses,
    'card_expenses' => $card_expenses,
    'debts_to_us' => $debts_to_us2,
    'debts_to_have' => $debts_to_have2,
    'cash_box' => $cash_box,
    'card_box' => $card_box,
    'true_total_profit' => $true_total_profit,
 ]);
        // return response()->json([
        //     'cash_revenue' => $cash_revenue,
        //     'card_revenue' => $card_revenue,
        //     'cash_expenses' => $cash_expenses,
        //     'card_expenses' => $card_expenses,
        //     'debts_to_us' => $debts_to_us2,
        //     'debts_to_have' => $debts_to_have2,
        //     'cash_box' => $cash_box,
        //     'card_box' => $card_box,
        // ])->setStatusCode(200);
    }       


public function getCashBox(Request $request){

    $from = $request->query('from', date('Y-m-d'));
    $to = $request->query('to', date('Y-m-d'));

    // تحقق من صحة التاريخ
    if (!$from || !$to) {
        return response()->json(['error' => 'Invalid date range'], 400);
    }

    // إيرادات الجلسات النقدية
    $session_cash_payments_details = Clinic_SessionsModel::with('patient')
        ->whereBetween('session_date', [$from, $to])
        ->where('cash_payment', '>', 0)
        ->get();

    // دفعات المرضى النقدية
    $patient_cash_payments_details = patient_PaymentModel::with('patient')
        ->whereBetween('payment_date', [$from, $to])
        ->where('paid_cash', '>', 0)
        ->get();

    // المصروفات النقدية
    $expenses_cash_details = expensesModel::whereBetween('date', [$from, $to])
        ->where('paid_cash', '>', 0)
        ->get();

    // دفعات معمل نقدية
    $lab_cash_details = lab_PaymentModel::with('laboratories')
        ->whereBetween('payment_date', [$from, $to])
        ->where('paid_cash', '>', 0)
        ->get();

    // دفعات مستودع نقدية
    $warehouse_cash_details = Warehouse_paymentsModel::with('storehouses')
        ->whereBetween('payment_date', [$from, $to])
        ->where('paid_cash', '>', 0)
        ->get();

    // اجمالي الرصيد النقدي
    $cash_in = $session_cash_payments_details->sum('cash_payment') + $patient_cash_payments_details->sum('paid_cash');
    $cash_out = $expenses_cash_details->sum('paid_cash') + $lab_cash_details->sum('paid_cash') + $warehouse_cash_details->sum('paid_cash');

    $cash_balance = $cash_in - $cash_out;
    

    return view('admin.reports.cash_box', [
        'from' => $from,
        'to' => $to,
        'session_cash_payments_details' => $session_cash_payments_details,
        'patient_cash_payments_details' => $patient_cash_payments_details,
        'expenses_cash_details' => $expenses_cash_details,
        'lab_cash_details' => $lab_cash_details,
        'warehouse_cash_details' => $warehouse_cash_details,
        'cash_balance' => $cash_balance,
        'cash_in' => $cash_in,
        'cash_out' => $cash_out,
    ]);
}
public function getCardBox(Request $request)
{
    $from = $request->query('from', date('Y-m-d'));
    $to = $request->query('to', date('Y-m-d'));

    if (!$from || !$to) {
        return response()->json(['error' => 'Invalid date range'], 400);
    }

    // الإيرادات البنكية
    $session_card_details = Clinic_SessionsModel::with('patient')
        ->whereBetween('session_date', [$from, $to])
        ->where('card_payment', '>', 0)
        ->get();

    $patient_card_details = patient_PaymentModel::with('patient')
        ->whereBetween('payment_date', [$from, $to])
        ->where('paid_card', '>', 0)
        ->get();

    // المصروفات البنكية
    $expenses_card_details = expensesModel::whereBetween('date', [$from, $to])
        ->where('paid_card', '>', 0)
        ->get();

    $lab_card_details = lab_PaymentModel::with('laboratories')
        ->whereBetween('payment_date', [$from, $to])
        ->where('paid_card', '>', 0)
        ->get();

    $warehouse_card_details = Warehouse_paymentsModel::with('storehouses')
        ->whereBetween('payment_date', [$from, $to])
        ->where('paid_card', '>', 0)
        ->get();

    // الحسابات
    $card_in = $session_card_details->sum('card_payment') + $patient_card_details->sum('paid_card');
    $card_out = $expenses_card_details->sum('paid_card') + $lab_card_details->sum('paid_card') + $warehouse_card_details->sum('paid_card');
    $card_balance = $card_in - $card_out;

    return view('Admin.reports.card_box', [
        'from' => $from,
        'to' => $to,
        'session_card_details' => $session_card_details,
        'patient_card_details' => $patient_card_details,
        'expenses_card_details' => $expenses_card_details,
        'lab_card_details' => $lab_card_details,
        'warehouse_card_details' => $warehouse_card_details,
        'card_in' => $card_in,
        'card_out' => $card_out,
        'card_balance' => $card_balance,
    ]);
}


}