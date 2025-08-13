@extends('admin.layouts.app')

@section('title', 'تعديل فاتورة مشتريات')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">تعديل فاتورة مشتريات</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('laboratoryPurchases.update', $purchase->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">المعمل</label>
                        <select name="laboratory_id" class="form-select" required>
                            @foreach($laboratories as $lab)
                                <option value="{{ $lab->id }}" {{ $purchase->laboratory_id == $lab->id ? 'selected' : '' }}>
                                    {{ $lab->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                    <label for="patient_name" class="form-label">اسم المريض</label>
                    <input type="text" id="patient_name" name="patient_name" class="form-control" 
                        value="{{ old('patient_name', $purchase->patient->name ?? '') }}" 
                        autocomplete="off" required>
                    
                    <input type="hidden" id="patient_id" name="patient_id" 
                        value="{{ old('patient_id', $purchase->patient->id ?? '') }}">

                    <div id="patient-error" class="text-danger mt-1" style="display:none;">
                        يرجى اختيار مريض من القائمة فقط.
                    </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">تاريخ الشراء</label>
                        <input type="date" name="purchase_date" class="form-control" value="{{ $purchase->purchase_date }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">ملاحظات</label>
                    <textarea name="notes" class="form-control">{{ $purchase->notes }}</textarea>
                </div>

                <hr>
                <h5>أصناف الفاتورة</h5>

                <div id="items-container">
                    @foreach($purchase->items as $index => $item)
                        <div class="row mb-2 item-row">
                            <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                            <div class="col-md-3"><input type="text" name="items[{{ $index }}][item_name]" class="form-control" value="{{ $item->item_name }}" required></div>
                            <div class="col-md-2"><input type="text" name="items[{{ $index }}][number_of_teeth]" class="form-control" value="{{ $item->number_of_teeth }}"></div>
                            <div class="col-md-2"><input type="number" name="items[{{ $index }}][price]" class="form-control" step="0.01" value="{{ $item->price }}" required></div>
                            <div class="col-md-2"><input type="number" name="items[{{ $index }}][quantity]" class="form-control" value="{{ $item->quantity }}" required></div>
                            <div class="col-md-2"><input type="text" name="items[{{ $index }}][notes]" class="form-control" value="{{ $item->notes }}"></div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger btn-sm remove-existing" data-id="{{ $item->id }}">×</button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <input type="hidden" name="deleted_items" id="deleted-items" value="[]">

                <button type="button" class="btn btn-outline-primary mb-3" onclick="addItem()">إضافة صنف</button>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">حفظ التعديلات</button>
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
    let index = {{ $purchase->items->count() }};

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

    // حذف صنف قديم
    document.querySelectorAll('.remove-existing').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            const deleted = JSON.parse(document.getElementById('deleted-items').value);
            deleted.push(id);
            document.getElementById('deleted-items').value = JSON.stringify(deleted);
            this.closest('.item-row').remove();
        });
    });
</script>
@endsection
