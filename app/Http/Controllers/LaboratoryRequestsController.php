<?php

namespace App\Http\Controllers;

use App\Models\laboratory_requests_itemsModel;
use App\Models\laboratory_requestsModel;
use App\Models\laboratoryModel;
use App\Models\LaboratoryRequestsModel;
use App\Models\PatientModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LaboratoryRequestsController extends Controller{

    // public function index(Request $request){

    //   try {
        
    //     $laboratoryRequests = laboratory_requestsModel::with('laboratory','patient','items');

    //     if ($request->filled('search')) {
    //         $search = trim($request->search);
    //         $laboratoryRequests->where('name', 'like', "%{$search}%");
    //     }

    //     $laboratoryRequests = $laboratoryRequests->latest()->paginate(4);

    //     return view('Admin.Laboratory_Requests.index', compact('laboratoryRequests'));
    //     }
    //   catch (\Exception $e) {
    //         Log::error('Error fetching doctors: ' . $e->getMessage(), [
    //             'exception' => $e,
    //         ]);
    //         return redirect()
    //             ->back()
    //             ->with('error', 'حدث خطأ أثناء جلب بيانات طلبات المعامل.');
    //     }
    // }
    public function index(Request $request)
{
    try {
        $from = $request->query('from', date('Y-m-d'));
        $to = $request->query('to', date('Y-m-d'));

        // Validate the date range
        if (!$from || !$to) {
            return response()->json(['error' => 'Invalid date range'], 400);
        }
        $laboratoryRequests = laboratory_requestsModel::with('laboratory', 'patient', 'items')->whereBetween('request_date', [$from, $to]);

        if ($request->filled('search')) {
            $search = trim($request->search);

            $laboratoryRequests->where(function ($query) use ($search) {
                $query->whereHas('patient', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('laboratory', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            });
        }

        $laboratoryRequests = $laboratoryRequests->latest()->paginate(10);

        return view('Admin.Laboratory_Requests.index', compact('laboratoryRequests'));
    } catch (\Exception $e) {
        Log::error('Error fetching laboratory requests: ' . $e->getMessage(), [
            'exception' => $e,
        ]);
        return redirect()
            ->back()
            ->with('error', 'حدث خطأ أثناء جلب بيانات طلبات المعامل.');
    }
}

    


    public function create(){
        $laboratories=laboratoryModel::all();
        $patients=PatientModel::all();
        // dd($laboratories->toArray());
        return view('Admin.Laboratory_Requests.create', compact('laboratories', 'patients'));
    }

    // public function store(Request $request){
    //     $request->validate([
    //         'patient_id' => 'required|exists:patients,id',
    //         'laboratory_id' => 'required|exists:laboratories,id',
    //         'request_date' => 'required|date|before_or_equal:today',
    //         'quantity' => 'required|integer|min:1',
    //         'number_tooth' => 'required|integer|min:1',
    //         // 'status' => 'required|in:pending,completed,cancelled,completed',
    //         'notes' => 'nullable|string|max:255',
    //     ],[ 
    //         'patient_id.required' => 'الرجاء اختيار المريض.',
    //         'laboratory_id.required' => 'الرجاء اختيار المعمل.',
    //         'request_date.required' => 'الرجاء إدخال تاريخ الطلب.',
    //         'request_date.date' => 'تاريخ الطلب يجب أن يكون تاريخًا صحيحًا.',
    //         'quantity.required' => 'الرجاء إدخال الكمية.',
    //         'number_tooth.required' => 'الرجاء إدخال رقم الأسنان.',
    //         // 'status.required' => 'الرجاء اختيار حالة الطلب.',
    //     ]);
    //   try {
    //     LaboratoryRequestsModel::create($request->all());
    //     return redirect()->route('LaboratoryRequests.index')->with('success', 'تم إضافة الطلب بنجاح');
    //     }
    //   catch (\Exception $e) {
    //         Log::error('Error storing laboratory: ' . $e->getMessage(), [
    //             'exception' => $e,
    //         ]);
    //         return redirect()
    //             ->back()
    //             ->with('error', 'حدث خطأ أثناء إضافة الطلب.');
    //     }
    // }
    public function store(Request $request)
{
    // التحقق من صحة البيانات
    $validated = $request->validate([
        'patient_id' => 'required|exists:patients,id',
        'laboratory_id' => 'required|exists:laboratories,id',
        'request_date' => 'required|date',
        'status' => 'required|in:pending,in_progress,completed,cancelled',
        'notes' => 'nullable|string',
        'items' => 'required|array|min:1',
        'items.*.category' => 'required|string',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.tooth_number' => 'nullable|string',
    ]);

    // إنشاء الطلب الأساسي
    $laboratoryRequest = laboratory_requestsModel::create([
        'patient_id' => $validated['patient_id'],
        'laboratory_id' => $validated['laboratory_id'],
        'request_date' => $validated['request_date'],
        'status' => $validated['status'],
        'notes' => $validated['notes'],
    ]);

    // حفظ الأصناف المرتبطة بالطلب
    foreach ($validated['items'] as $item) {
        $laboratoryRequest->items()->create([
            'category' => $item['category'],
            'quantity' => $item['quantity'],
            'tooth_number' => $item['tooth_number'],
        ]);
    }

    return redirect()->route('LaboratoryRequests.index')->with('success', 'تم حفظ الطلب بنجاح.');
}

    
    public function edit($id)
{
    $request = laboratory_requestsModel::with('items')->findOrFail($id);
    $patients = PatientModel::all();
    $laboratories = laboratoryModel::all();

    return view('Admin.Laboratory_Requests.edit',compact('request', 'patients', 'laboratories'));
}


    public function update(Request $request, $id)
{
    $request->validate([
        'patient_id' => 'required|exists:patients,id',
        'laboratory_id' => 'required|exists:laboratories,id',
        'request_date' => 'required|date',
        'status' => 'required|in:pending,in_progress,completed,cancelled',
        'items' => 'required|array|min:1',
        'items.*.category' => 'required|string',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.tooth_number' => 'nullable|string',
    ]);

    $labRequest =laboratory_requestsModel::findOrFail($id);
    $labRequest->update([
        'patient_id' => $request->patient_id,
        'laboratory_id' => $request->laboratory_id,
        'request_date' => $request->request_date,
        'status' => $request->status,
        'notes' => $request->notes,
    ]);

    
    
    // حذف الأصناف
if ($request->filled('deleted_items')) {
    $deletedIds = json_decode($request->deleted_items, true);
    laboratory_requests_itemsModel::whereIn('id', $deletedIds)->delete();
}

// إضافة أصناف جديدة
foreach ($request->items as $item) {
    if (empty($item['id'])) {
        laboratory_requests_itemsModel::create([
            'laboratory_request_id' => $labRequest->id,
            'category' => $item['category'],
            'quantity' => $item['quantity'],
            'tooth_number' => $item['tooth_number'],
        ]);
    }
}
// تحديث الأصناف
foreach ($request->items as $item) {
        if (isset($item['id'])) {
            // تحديث صنف موجود
            laboratory_requests_itemsModel::where('id', $item['id'])->update([
                'category' => $item['category'],
                'quantity' => $item['quantity'],
                'tooth_number' => $item['tooth_number'],
            ]);
        }
    }


    return redirect()->route('LaboratoryRequests.index')->with('success', 'تم تعديل الطلب بنجاح');
}

    public function destroy($id)
{
    $request = laboratory_requestsModel::findOrFail($id);
    $request->forcedelete();  // هذا سيحذف الطلب والأصناف المرتبطة (إذا فعلت boot() السابق)

    return redirect()->route('LaboratoryRequests.index')->with('success', 'تم حذف الطلب بنجاح');
}

}
