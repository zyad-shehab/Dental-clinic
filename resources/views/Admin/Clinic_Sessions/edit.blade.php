{{-- <!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- jQuery UI CSS -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

<!-- jQuery UI JS -->
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script> --}}

@extends('admin.layouts.app')

@section('title', 'تعديل جلسة علاج')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-warning text-white">
            <h5 class="mb-0">تعديل جلسة علاج</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('sessions.update', $session->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- المريض --}}
            <div class="mb-3">
                <label for="patient_name" class="form-label">اسم المريض</label>
                <input type="text" id="patient_name" name="patient_name" class="form-control" 
                    value="{{ old('patient_name', $session->patient->name ?? '') }}" 
                    autocomplete="off" required>
                
                <input type="hidden" id="patient_id" name="patient_id" 
                    value="{{ old('patient_id', $session->patient->id ?? '') }}">

                <div id="patient-error" class="text-danger mt-1" style="display:none;">
                    يرجى اختيار مريض من القائمة فقط.
                </div>
            </div>

                {{-- الطبيب --}}
                       
            <div class="mb-3">
                <label for="doctor_name" class="form-label">اسم الطبيب</label>
                <input type="text" id="doctor_name" name="doctor_name" class="form-control" 
                    value="{{ old('doctor_name', $session->doctor->name ?? '') }}" 
                    autocomplete="off" required>
                
                <input type="hidden" id="doctor_id" name="doctor_id" 
                    value="{{ old('doctor_id', $session->doctor->id ?? '') }}">

                <div id="doctor-error" class="text-danger mt-1" style="display:none;">
                    يرجى اختيار طبيب من القائمة فقط.
                </div>
            </div>

                {{-- التاريخ والوقت --}}
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="session_date" class="form-label">تاريخ الجلسة</label>
                        <input type="date" name="session_date" id="session_date" class="form-control" value="{{ $session->session_date }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="start_time" class="form-label">من الساعة</label>
                        <input type="time" name="start_time" id="start_time" class="form-control" value="{{ $session->start_time }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="end_time" class="form-label">إلى الساعة</label>
                        <input type="time" name="end_time" id="end_time" class="form-control" value="{{ $session->end_time }}" required>
                    </div>
                </div>

                {{-- الخدمات المقدمة --}}
                <h5>الخدمات المقدمة</h5>
                <div id="services-wrapper">
                    @foreach($session->services as $index => $item)
                    <div class="row mb-2 service-item">
                        <div class="col-md-5">
                            <select name="services[{{ $index }}][id]" class="form-control service-select" onchange="calculateRequiredAmount()" required>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" data-price="{{ $service->price }}" {{ $item->id == $service->id ? 'selected' : '' }}>
                                        {{ $service->name }} 
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="services[{{ $index }}][tooth_number]" class="form-control" value="{{ $item->pivot->tooth_number }}" pattern="^\d{1,2}(-\d{1,2})*$" title="رقم سن أو أرقام مفصولة بشرطة مثل 13 أو 12-15" required>
                        </div>
                        <div class="col-md-2 d-flex align-items-center">
                            <button type="button" class="btn btn-danger btn-sm" onclick="removeService(this)">❌</button>
                        </div>
                    </div>
                    @endforeach
                </div>
                <button type="button" onclick="addService()" class="btn btn-secondary mb-3">إضافة خدمة</button>

                {{-- البيانات المالية --}}
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="required_amount" class="form-label">المبلغ المطلوب</label>
                        <input type="number"  name="required_amount" id="required_amount" class="form-control" value="{{ $session->required_amount  }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="cash_payment" class="form-label">مدفوع نقدًا</label>
                        <input type="number"  name="cash_payment" id="cash_payment" class="form-control" value="{{ $session->cash_payment }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="card_payment" class="form-label">مدفوع بنكي</label>
                        <input type="number"  name="card_payment" id="card_payment" class="form-control" value="{{ $session->card_payment }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="total_amount" class="form-label">المبلغ الإجمالي</label>
                        <input type="number"  name="total_amount" id="total_amount" class="form-control" value="{{ $session->total_amount }}" readonly>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="remaining_amount" class="form-label">المبلغ المتبقي</label>
                        <input type="number"  name="remaining_amount" id="remaining_amount" class="form-control" value="{{ $session->remaining_amount }}" readonly>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="name_of_bank_account" class="form-label">اسم الحساب البنكي</label>
                        <input type="text" name="name_of_bank_account" class="form-control" value="{{ $session->name_of_bank_account }}">
                    </div>
                </div>

                {{-- الدواء والملاحظات --}}
                <div class="mb-3">
                    <label for="drug" class="form-label">الدواء المستخدم</label>
                    <input type="text" name="drug" id="drug" class="form-control" value="{{ $session->drug }}">
                </div>
                <div class="mb-3">
                    <label for="notes" class="form-label">ملاحظات</label>
                    <textarea name="notes" id="notes" class="form-control" rows="3">{{ $session->notes }}</textarea>
                </div>

                {{-- صورة الأشعة --}}               
            <div class="mb-3">
                <label for="xray_image" class="form-label">صورة الأشعة (يمكن تركها فارغة إذا لم تتغير)</label>
                <input type="file" name="xray_image" id="xray_image" class="form-control">
                
                @if($session->xray_image)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $session->xray_image) }}" alt="صورة الأشعة" style="max-height: 150px;">
                        <small>الصورة الحالية: <a href="{{ asset('storage/'.$session->xray_image) }}" target="_blank">عرض</a></small>
                    </div>
                @endif
            </div>

                {{-- زر التحديث --}}
                <button type="submit" class="btn btn-success">تحديث</button>
                <a href="{{ route('sessions.index') }}" class="btn btn-secondary">إلغاء</a>
            </form>
        </div>
    </div>
</div>
@endsection
{{-- JavaScript للبحث عن المريض والطبيب عند الاضافة--}}
@include('Admin.partials.scripts');



{{-- /////////////للحسابات التلقائية و اضافة الخدمات///////////////// --}}
<script>
    let serviceIndex = {{ isset($session) ? count($session->services) : 1 }};

    function addService() {
        const wrapper = document.getElementById('services-wrapper');
        const div = document.createElement('div');
        div.classList.add('row', 'mb-2', 'service-item');

        div.innerHTML = `
            <div class="col-md-5">
                <select name="services[${serviceIndex}][id]" class="form-control service-select" onchange="calculateRequiredAmount()" required>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" data-price="{{ $service->price }}">{{ $service->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <input type="text" name="services[${serviceIndex}][tooth_number]" class="form-control" placeholder="مثال: 11 أو 12-15" pattern="^\\d{1,2}(-\\d{1,2})*$" title="رقم سن أو أرقام مفصولة بشرطة مثل 13 أو 12-15" required>
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
        calculateRequiredAmount();
    }

    // function calculateRequiredAmount() {
    //     let total = 0;
    //     document.querySelectorAll('.service-select').forEach(select => {
    //         const selectedOption = select.options[select.selectedIndex];
    //         const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
    //         total += price;
    //     });

    //     document.getElementById('required_amount').value = total.toFixed(2);
    //     calculateTotalAmount();
    // }
    function calculateRequiredAmount() {
    let total = 0;
    document.querySelectorAll('.service-select').forEach(select => {
        const selectedOption = select.options[select.selectedIndex];
        const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        total += price;
    });

    const requiredInput = document.getElementById('required_amount');

    // فقط عيّن القيمة إذا كانت الحقل فارغ أو صفر
    if (!requiredInput.value || requiredInput.value === '0' || requiredInput.value === '0.00') {
        requiredInput.value = total.toFixed(2);
    }

    calculateTotalAmount();
}


    function calculateTotalAmount() {
        let required = parseFloat(document.getElementById('required_amount').value) || 0;
        let cash = parseFloat(document.getElementById('cash_payment').value) || 0;
        let card = parseFloat(document.getElementById('card_payment').value) || 0;

        let total = cash + card;
        let remaining = required - total;

        document.getElementById('total_amount').value = total.toFixed(2);
        document.getElementById('remaining_amount').value = remaining.toFixed(2);
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.service-select').forEach(select => {
            select.addEventListener('change', calculateRequiredAmount);
        });

        document.getElementById('required_amount')?.addEventListener('input', calculateTotalAmount);
        document.getElementById('cash_payment')?.addEventListener('input', calculateTotalAmount);
        document.getElementById('card_payment')?.addEventListener('input', calculateTotalAmount);

        calculateRequiredAmount();
    });
</script>
{{-- واحد جديد من الشات  --}}
