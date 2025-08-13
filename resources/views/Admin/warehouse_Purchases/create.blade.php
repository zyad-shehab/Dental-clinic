@extends('admin.layouts.app')

@section('title', 'إضافة فاتورة مشتريات مستودع')

@section('content')
<div class="container mt-4">
    <form action="{{ route('warehousePurchases.store') }}" method="POST">
        @csrf

        <div class="card shadow mb-3">
            <div class="card-header bg-primary text-white">بيانات الفاتورة</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <label>المستودع</label>
                        <select name="storehouses_id" class="form-control" required>
                            <option value="">--  اختر المستودع--</option>
                            @foreach($storehouses as $store)
                                <option value="{{ $store->id }}">{{ $store->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>تاريخ الشراء</label>
                        <input type="date" name="purchase_date" class="form-control" value="{{ \Carbon\Carbon::now()->toDateString() }}" required>
                    </div>
                </div>
                <div class="mt-3">
                    <label>ملاحظات</label>
                    <textarea name="notes" class="form-control"></textarea>
                </div>
            </div>
        </div>

        <div class="card shadow mb-3">
            <div class="card-header bg-secondary text-white">الأصناف</div>
            <div class="card-body">
                <div id="items-wrapper">
                    <div class="row mb-2 item-row">
                        <div class="col-md-3"><input type="text" name="items[0][item_name]" class="form-control" placeholder="اسم الصنف" required></div>
                        <div class="col-md-2"><input type="number" name="items[0][quantity]" class="form-control" placeholder="الكمية" required></div>
                        <div class="col-md-2"><input type="number" name="items[0][price]" step="0.01" class="form-control" placeholder="السعر" required></div>
                        <div class="col-md-2"><input type="date" name="items[0][end_date]" class="form-control" placeholder="تاريخ الانتهاء"></div>
                        <div class="col-md-2"><input type="text" name="items[0][notes]" class="form-control" placeholder="ملاحظات"></div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger remove-item">X</button>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-outline-success" id="add-item">إضافة صنف</button>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">حفظ الفاتورة</button>
    </form>
</div>

<script>
    let itemIndex = 1;
    document.getElementById('add-item').addEventListener('click', function () {
        let row = `
        <div class="row mb-2 item-row">
            <div class="col-md-3"><input type="text" name="items[${itemIndex}][item_name]" class="form-control" placeholder="اسم الصنف" required></div>
            <div class="col-md-2"><input type="number" name="items[${itemIndex}][quantity]" class="form-control" placeholder="الكمية" required></div>
            <div class="col-md-2"><input type="number" name="items[${itemIndex}][price]" step="0.01" class="form-control" placeholder="السعر" required></div>
            <div class="col-md-2"><input type="date" name="items[${itemIndex}][end_date]" class="form-control"></div>
            <div class="col-md-2"><input type="text" name="items[${itemIndex}][notes]" class="form-control"></div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger remove-item">X</button>
            </div>
        </div>
        `;
        document.getElementById('items-wrapper').insertAdjacentHTML('beforeend', row);
        itemIndex++;
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item')) {
            e.target.closest('.item-row').remove();
        }
    });
</script>
@endsection
