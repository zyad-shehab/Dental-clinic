@extends('admin.layouts.app')

@section('title', 'إضافة طلب  لمعمل')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">إضافة طلب </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('LaboratoryRequests.store') }}" method="POST">
                @csrf

                <!-- بيانات الطلب -->
                <div class="row mb-3">
                    {{-- <div class="col-md-4">
                        <label for="patient_id" class="form-label">المريض</label>
                        <select name="patient_id" id="patient_id" class="form-select" required>
                            <option value="">اختر المريض</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                            @endforeach
                        </select>
                    </div> --}}
                     <div class="col-md-4">
                        <label for="patient_name" class="form-label">اختر المريض</label>
                        <input type="text" id="patient_name" name="patient_name" class="form-control" autocomplete="off" placeholder="ابحث عن اسم المريض" required>
                        <input type="hidden" id="patient_id" name="patient_id">
                        <div id="patient-error" class="text-danger mt-1" style="display:none;">يرجى اختيار مريض من القائمة فقط.</div>
                    </div>

                    <div class="col-md-4">
                        <label for="laboratory_id" class="form-label">المعمل</label>
                        <select name="laboratory_id" id="laboratory_id" class="form-select" required>
                            <option value="">اختر المعمل</option>
                            @foreach($laboratories as $lab)
                                <option value="{{$lab->id}}">{{ $lab->name ?? 'لا يوجد' }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="request_date" class="form-label">تاريخ الطلب</label>
                        <input type="date" name="request_date" id="request_date" class="form-control"  value="{{ \Carbon\Carbon::now()->toDateString() }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">الحالة</label>
                    <select name="status" id="status" class="form-select">
                        <option value="pending" selected>قيد الانتظار</option>
                        <option value="in_progress">قيد المعالجة</option>
                        <option value="completed">مكتمل</option>
                        <option value="cancelled">ملغي</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">ملاحظات</label>
                    <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
                </div>

                <hr>
                <h5>أصناف الطلب</h5>

                <div id="items-container">
                    <div class="row mb-2 item-row">
                        <div class="col-md-4">
                            <input type="text" name="items[0][category]" class="form-control" placeholder="الصنف" required>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="items[0][quantity]" class="form-control" placeholder="الكمية" min="1" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="items[0][tooth_number]" class="form-control" placeholder="رقم السن">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-sm" onclick="removeItem(this)">حذف</button>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-outline-primary mb-3" onclick="addItem()">إضافة صنف</button>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">حفظ الطلب</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- jQuery --}}
<script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>

{{-- jQuery UI --}}
<link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.css') }}">
<script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>
{{-- لعرض المرضي --}}
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
{{--  --}}
<script>
    let index = 1;

    function addItem() {
        const container = document.getElementById('items-container');
        const row = document.createElement('div');
        row.classList.add('row', 'mb-2', 'item-row');
        row.innerHTML = `
            <div class="col-md-4">
                <input type="text" name="items[${index}][category]" class="form-control" placeholder="الصنف" required>
            </div>
            <div class="col-md-3">
                <input type="number" name="items[${index}][quantity]" class="form-control" placeholder="الكمية" min="1" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="items[${index}][tooth_number]" class="form-control" placeholder="رقم السن">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm" onclick="removeItem(this)">حذف</button>
            </div>
        `;
        container.appendChild(row);
        index++;
    }

    function removeItem(button) {
        const row = button.closest('.item-row');
        row.remove();
    }
</script>
@endsection
