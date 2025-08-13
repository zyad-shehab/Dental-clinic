@extends('admin.layouts.app')
@section('title', 'إضافة دفعة جديدة')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">إضافة دفعة من مريض</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('patientPayment.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="payment_date" class="form-label">تاريخ الدفعة</label>
                    <input type="date" name="payment_date" class="form-control" value="{{ \Carbon\Carbon::now()->toDateString() }}" required>
                </div>

                {{-- <div class="mb-3">
                    <label for="patient_id" class="form-label">اسم المريض</label>
                    <select name="patient_id" class="form-control" required>
                        <option value="">اختر اسم المريض</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}">{{ $patient->name ?? null }}</option>
                        @endforeach
                    </select>
                </div> --}}
                 <div class="mb-3">
                        <label for="patient_name" class="form-label">اختر المريض</label>
                        <input type="text" id="patient_name" name="patient_name" class="form-control" autocomplete="off" placeholder="ابحث عن اسم المريض" required>
                        <input type="hidden" id="patient_id" name="patient_id">
                    <div id="patient-error" class="text-danger mt-1" style="display:none;">يرجى اختيار مريض من القائمة فقط.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">المبلغ نقدًا</label>
                    <input type="number" name="Paid_cash" class="form-control" >
                </div>

                <div class="mb-3">
                    <label class="form-label">المبلغ بالبطاقة</label>
                    <input type="number" name="Paid_card" class="form-control" >
                </div>

                <div class="mb-3">
                    <label class="form-label">اسم الحساب البنكي</label>
                    <input type="text" name="name_of_bank_account" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">ملاحظات</label>
                    <textarea name="notes" class="form-control" rows="3"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">حفظ الدفعة</button>
            </form>
        </div>
    </div>
</div>
@endsection
{{-- @include('Admin.partials.scripts'); --}}
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