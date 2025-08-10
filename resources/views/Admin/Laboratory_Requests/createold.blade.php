@extends('admin.layouts.app')

@section('title', 'إضافة طلب  لمعمل')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">إضافة طلب </h5>
        </div>
        <div class="card-body">
<form action="{{ route('LaboratoryRequests.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- اختيار المريض --}}
                {{-- <div class="mb-3">
                    <label for="patient_id" class="form-label">المريض</label>
                    <select name="patient_id" id="patient_id" class="form-select" required>
                        <option value="">اختر مريضًا</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                        @endforeach
                    </select>
                </div> --}}
                {{-- //////////// --}}
                <div class="mb-3">
    <label for="patient_name" class="form-label">اختر المريض</label>
    <input type="text" id="patient_name" name="patient_name" class="form-control" autocomplete="off" placeholder="ابحث عن اسم المريض" required>
    <input type="hidden" id="patient_id" name="patient_id">
    <div id="patient-error" class="text-danger mt-1" style="display:none;">يرجى اختيار مريض من القائمة فقط.</div>
    </div>


                {{-- اختيار الطبيب --}}
                {{-- <div class="mb-3">
                    <label for="doctor_id" class="form-label">الطبيب</label>
                    <select name="doctor_id" id="doctor_id" class="form-select" required>
                        <option value="">اختر طبيبًا</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                        @endforeach
                    </select>
                </div> --}}
                <div class="mb-3">
    <label for="laboratory_name" class="form-label">اختر المعمل</label>
    <input type="text" id="laboratory_name" name="laboratory_name" class="form-control" autocomplete="off" placeholder="ابحث عن اسم المعمل" required>
    <input type="hidden" id="laboratory_id" name="laboratory_id">
    <div id="laboratory-error" class="text-danger mt-1" style="display:none;">يرجى اختيار معمل من القائمة فقط.</div>
    </div>

                {{-- التاريخ والوقت --}}
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="request_date" class="form-label">تاريخ الجلسة</label>
                        <input type="date" name="request_date" id="request_date" class="form-control" value="{{ \Carbon\Carbon::now()->toDateString() }}" required>
                    </div>
                </div>

                {{-- الخدمات المقدمة --}}
                <h5>الأصناف</h5>
                <div id="services-wrapper">
                    <div class="row mb-2 service-item">
                        <div class="col-md-5">
                            <label>الصنف</label>
                            <input type="text" name="services[0][tooth_number]" 
                                    class="form-control"
                                    placeholder="مثال: 11 أو 12-15-18"
                                    pattern="^\d+(-\d+)*$"
                                    title="يجب أن يكون رقمًا واحدًا أو أرقام مفصولة بشرطة مثل 13 أو 12-15-18" required>
                        </div>
                        <div class="col-md-5">
                            <label>العدد</label>
                            <input type="text" name="services[0][tooth_number]" 
                                    class="form-control"
                                    placeholder="مثال: 11 أو 12-15-18"
                                    pattern="^\d+(-\d+)*$"
                                    title="يجب أن يكون رقمًا واحدًا أو أرقام مفصولة بشرطة مثل 13 أو 12-15-18" required>
                        </div>
                        <div class="col-md-5">
                            <label>رقم السن</label>
                            <input type="text" name="services[0][tooth_number]" 
                                    class="form-control"
                                    placeholder="مثال: 11 أو 12-15-18"
                                    pattern="^\d+(-\d+)*$"
                                    title="يجب أن يكون رقمًا واحدًا أو أرقام مفصولة بشرطة مثل 13 أو 12-15-18" required>
                        </div>
                        <div class="col-md-2 d-flex align-items-center">
                            <button type="button" class="btn btn-danger btn-sm" onclick="removeService(this)">❌</button>
                        </div>
                    </div>
                </div>
                <div>
                    <button type="button" onclick="addService()" class="btn btn-secondary mb-3">إضافة صنف اخر</button>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">حالة الموعد</label>
                     <select name="status" id="status" class="form-control" required>
                        <option value="pending" >انتظار</option>
                        <option value="pending" >يتم التنفيذ</option>
                        <option value="confirmed" >مكتمل</option>
                        <option value="cancelled" >ملغي</option>     
                    </select>
                    @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                
                <div class="mb-3">
                    <label for="notes" class="form-label">ملاحظات</label>
                    <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
                </div>                

                {{-- زر الإرسال --}}
                <button type="submit" class="btn btn-success">حفظ الطلب</button>
                <a href="{{ route('LaboratoryRequests.index') }}" class="btn btn-secondary">إلغاء</a>

</form>

        </div>
    </div>
</div>

{{-- JavaScript لإضافة وحذف خدمات ديناميكيًا --}}
<script>
    let serviceIndex = 1;

    function addService() {
        const wrapper = document.getElementById('services-wrapper');
        const div = document.createElement('div');
        div.classList.add('row', 'mb-2', 'service-item');

        div.innerHTML = `
            <div class="col-md-5">
                <select name="services[${serviceIndex}][id]" class="form-control" required>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <input type="text" name="services[${serviceIndex}][tooth_number]" class="form-control" placeholder="رقم السن">
            </div>
            <div class="col-md-2 d-flex align-items-center">
                <button type="button" class="btn btn-danger btn-sm" onclick="removeService(this)">❌</button>
            </div>
        `;

        wrapper.appendChild(div);
        serviceIndex++;
    }

    function removeService(button) {
        button.closest('.service-item').remove();
    }
</script>

{{-- //////////////للحسابات التلقائية///////////// --}}
<script>
    function calculateTotalAmount() {
        // جلب القيم كأرقام
        let required = parseFloat(document.getElementById('required_amount').value) || 0;
        let cash = parseFloat(document.getElementById('cash_payment').value) || 0;
        let card = parseFloat(document.getElementById('card_payment').value) || 0;

        // هنا نفترض أن المبلغ الإجمالي = المبلغ المدفوع
        let total = cash + card;

        // عرض القيمة في الحقل
        document.getElementById('total_amount').value = total.toFixed(2);

        // حساب المبلغ المتبقي تلقائيا
        let remaining = required - (cash + card);
        // if (remaining < 0) remaining = 0; // لا تقبل قيم سالبة
        document.getElementById('remaining_amount').value = remaining.toFixed(2);
    }

    // ربط الدالة مع الأحداث onchange أو oninput في الحقول الثلاثة
    document.getElementById('required_amount').addEventListener('input', calculateTotalAmount);
    document.getElementById('cash_payment').addEventListener('input', calculateTotalAmount);
    document.getElementById('card_payment').addEventListener('input', calculateTotalAmount);

    // نفذ الحساب أول مرة عند تحميل الصفحة
    window.addEventListener('load', calculateTotalAmount);
</script>

@endsection
{{-- للبحث عن المرضي ةالاطباء --}}
@include('Admin.partials.scripts');

