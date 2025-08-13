@extends('admin.layouts.app')

@section('title', 'إضافة فاتورة مشتريات')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">إضافة فاتورة مشتريات</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('laboratoryPurchases.store') }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="laboratory_id" class="form-label">المعمل</label>
                        <select name="laboratory_id" class="form-select" required>
                            <option value="">اختر المعمل</option>
                            @foreach($laboratories as $lab)
                                <option value="{{ $lab->id }}">{{ $lab->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- <div class="col-md-4">
                        <label for="patient_id" class="form-label">المريض</label>
                        <select name="patient_id" class="form-select" required>
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
                        <label for="purchase_date" class="form-label">تاريخ الشراء</label>
                        <input type="date" name="purchase_date" class="form-control" value="{{ \Carbon\Carbon::now()->toDateString() }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">ملاحظات</label>
                    <textarea name="notes" class="form-control" rows="3"></textarea>
                </div>

                <hr>
                <h5>أصناف الفاتورة</h5>

                <div id="items-container">
                    <div class="row mb-2 item-row">
                        <div class="col-md-3"><input type="text" name="items[0][item_name]" class="form-control" placeholder="اسم الصنف" required></div>
                        <div class="col-md-2"><input type="text" name="items[0][number_of_teeth]" class="form-control" placeholder="رقم الأسنان"></div>
                        <div class="col-md-2"><input type="number" name="items[0][price]" class="form-control" placeholder="السعر" step="0.01" required></div>
                        <div class="col-md-2"><input type="number" name="items[0][quantity]" class="form-control" placeholder="الكمية" required></div>
                        <div class="col-md-2"><input type="text" name="items[0][notes]" class="form-control" placeholder="ملاحظات"></div>
                        <div class="col-md-1"><button type="button" class="btn btn-danger btn-sm" onclick="removeItem(this)">×</button></div>
                    </div>
                </div>

                <button type="button" class="btn btn-outline-primary mb-3" onclick="addItem()">إضافة صنف</button>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">حفظ الفاتورة</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

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
<script>
    let index = 1;

    function addItem() {
        const container = document.getElementById('items-container');
        const row = document.createElement('div');
        row.classList.add('row', 'mb-2', 'item-row');
        row.innerHTML = `
            <div class="col-md-3"><input type="text" name="items[${index}][item_name]" class="form-control" placeholder="اسم الصنف" required></div>
            <div class="col-md-2"><input type="text" name="items[${index}][number_of_teeth]" class="form-control" placeholder="رقم الأسنان"></div>
            <div class="col-md-2"><input type="number" name="items[${index}][price]" class="form-control" placeholder="السعر" step="0.01" required></div>
            <div class="col-md-2"><input type="number" name="items[${index}][quantity]" class="form-control" placeholder="الكمية" required></div>
            <div class="col-md-2"><input type="text" name="items[${index}][notes]" class="form-control" placeholder="ملاحظات"></div>
            <div class="col-md-1"><button type="button" class="btn btn-danger btn-sm" onclick="removeItem(this)">×</button></div>
        `;
        container.appendChild(row);
        index++;
    }

    function removeItem(button) {
        button.closest('.item-row').remove();
    }
</script>
