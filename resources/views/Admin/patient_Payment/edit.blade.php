@extends('admin.layouts.app')
@section('title', 'تعديل دفعة')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-warning text-white">
            <h5 class="mb-0">تعديل دفعة المريض</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('patientPayment.update', $payment->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="payment_date" class="form-label">تاريخ الدفعة</label>
                    <input type="date" name="payment_date" class="form-control" value="{{ $payment->payment_date }}" required>
                </div>

                {{-- <div class="mb-3">
                    <label for="patient_id" class="form-label">اسم المريض</label>
                    <select name="patient_id" class="form-control" required>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}"
                                {{ $payment->patient->patient_id == $patient->id ? 'selected' : '' }}>
                                {{ $patient->name }}
                            </option>
                        @endforeach
                    </select>
                </div> --}}
                <div class="mb-3">
                    <label for="patient_name" class="form-label">اسم المريض</label>
                    <input type="text" id="patient_name" name="patient_name" class="form-control" 
                        value="{{ old('patient_name', $payment->patient->name ?? '') }}" 
                        autocomplete="off" required>
                    
                    <input type="hidden" id="patient_id" name="patient_id" 
                        value="{{ old('patient_id', $payment->patient->id ?? '') }}">

                    <div id="patient-error" class="text-danger mt-1" style="display:none;">
                        يرجى اختيار مريض من القائمة فقط.
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">المبلغ نقدًا</label>
                    <input type="number" name="Paid_cash" class="form-control" step="0.01" value="{{ $payment->paid_cash }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">المبلغ بالبطاقة</label>
                    <input type="number" name="Paid_card" class="form-control" step="0.01" value="{{ $payment->paid_card }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">اسم الحساب البنكي</label>
                    <input type="text" name="name_of_bank_account" class="form-control" value="{{ $payment->name_of_bank_account }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">ملاحظات</label>
                    <textarea name="notes" class="form-control" rows="3">{{ $payment->notes }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">تحديث</button>
            </form>
        </div>
    </div>
</div>
@endsection
{{-- @include('Admin.partials.scripts'); --}}

{{-- jQuery --}}
<script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>

{{-- jQuery UI --}}
<link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.css') }}">
<script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>

<script>
    $(function() {
        let validPatient = false;

        $("#patient_name").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: '{{ route("ajax.patients") }}',
                    dataType: "json",
                    data: { term: request.term },
                    success: function(data) {
                        response($.map(data, function(item) {
                            return {
                                label: item.name,
                                value: item.name,
                                id: item.id
                            };
                        }));
                    }
                });
            },
            minLength: 2,
            select: function(event, ui) {
                $('#patient_id').val(ui.item.id);
                validPatient = true;
                $('#patient-error').hide();
            },
            change: function(event, ui) {
                if (!ui.item) {
                    $('#patient_id').val('');
                    validPatient = false;
                    $('#patient-error').show();
                }
            }
        });

        // تحقق عند إرسال الفورم أن المريض صالح
        $('form').on('submit', function(e) {
            if (!validPatient) {
                e.preventDefault();
                $('#patient-error').show();
                $('#patient_name').focus();
            }
        });
    });
</script>