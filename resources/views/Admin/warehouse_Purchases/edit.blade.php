@extends('admin.layouts.app')

@section('title', 'تعديل فاتورة مشتريات')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">تعديل فاتورة مشتريات</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('warehousePurchases.update', $purchase->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">المخزن</label>
                        <select name="storehouses_id" class="form-select" required>
                            @foreach($storehouses as $storehouse)
                                <option value="{{ $storehouse->id }}" {{ $purchase->storehouses_id == $storehouse->id ? 'selected' : '' }}>
                                    {{ $storehouse->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
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
                            <div class="col-md-2">
                                <input type="text" name="items[{{ $index }}][item_name]" class="form-control" value="{{ $item->item_name }}" required>
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="items[{{ $index }}][price]" class="form-control" step="0.01" value="{{ $item->price }}" required>
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="items[{{ $index }}][quantity]" class="form-control" value="{{ $item->quantity }}" required>
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="items[{{ $index }}][end_date]" class="form-control" value="{{ $item->end_date ? \Carbon\Carbon::parse($item->end_date)->format('Y-m-d') : '' }}" placeholder="تاريخ الانتهاء">
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="items[{{ $index }}][notes]" class="form-control" value="{{ $item->notes }}">
                            </div>
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

<script>
    let index = {{ $purchase->items->count() }};

    function addItem() {
        const container = document.getElementById('items-container');
        const row = document.createElement('div');
        row.classList.add('row', 'mb-2', 'item-row');
        row.innerHTML = `
            <div class="col-md-2">
                <input type="text" name="items[${index}][item_name]" class="form-control" placeholder="اسم الصنف" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="items[${index}][price]" class="form-control" placeholder="السعر" step="0.01" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="items[${index}][quantity]" class="form-control" placeholder="الكمية" required>
            </div>
            <div class="col-md-3">
                <input type="date" name="items[${index}][end_date]" class="form-control" placeholder="تاريخ الانتهاء">
            </div>
            <div class="col-md-2">
                <input type="text" name="items[${index}][notes]" class="form-control" placeholder="ملاحظات">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm" onclick="removeItem(this)">×</button>
            </div>
        `;
        container.appendChild(row);
        index++;
    }

    function removeItem(button) {
        button.closest('.item-row').remove();
    }

    // حذف صنف قديم مع تسجيله في الحقل المخفي
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
