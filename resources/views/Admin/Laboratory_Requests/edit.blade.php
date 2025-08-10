@extends('admin.layouts.app')

@section('title', 'تعديل طلب معمل')

@section('content')
<div class="container mt-4">
    <form action="{{ route('LaboratoryRequests.update', $request->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">تعديل الطلب</div>
            <div class="card-body">

                <div class="form-group mb-3">
                    <label>المريض:</label>
                    <select name="patient_id" class="form-control" required>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ $patient->id == $request->patient_id ? 'selected' : '' }}>
                                {{ $patient->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label>المعمل:</label>
                    <select name="laboratory_id" class="form-control" required>
                        @foreach($laboratories as $lab)
                            <option value="{{ $lab->id }}" {{ $lab->id == $request->laboratory_id ? 'selected' : '' }}>
                                {{ $lab->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label>تاريخ الطلب:</label>
                    <input type="date" name="request_date" value="{{ $request->request_date }}" class="form-control" required>
                </div>

                <div class="form-group mb-3">
                    <label>الحالة:</label>
                    <select name="status" class="form-control">
                        @foreach(['pending', 'in_progress', 'completed', 'cancelled'] as $status)
                            <option value="{{ $status }}" {{ $request->status == $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label>ملاحظات:</label>
                    <textarea name="notes" class="form-control">{{ $request->notes }}</textarea>
                </div>

                <hr>

                
<h5>الأصناف</h5>

<div id="items-container">
    @foreach($request->items as $index => $item)
        <div class="row g-2 mb-2 item-row">
            <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
            <div class="col-md-4">
                <input type="text" name="items[{{ $index }}][category]" class="form-control" value="{{ $item->category }}" required>
            </div>
            <div class="col-md-3">
                <input type="number" name="items[{{ $index }}][quantity]" class="form-control" value="{{ $item->quantity }}" min="1" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="items[{{ $index }}][tooth_number]" class="form-control" value="{{ $item->tooth_number }}">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger remove-existing-item" data-id="{{ $item->id }}">حذف</button>
            </div>
        </div>
    @endforeach
</div>

<input type="hidden" name="deleted_items" id="deleted-items" value="[]">

<button type="button" class="btn btn-sm btn-secondary my-2" id="add-item-btn">+ إضافة صنف جديد</button>

            
                <button type="submit" class="btn btn-success mt-3">حفظ التعديلات</button>
                <a href="{{ route('LaboratoryRequests.index') }}" class="btn btn-secondary mt-3">رجوع</a>

            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
let itemIndex = {{ count($request->items) }};

document.getElementById('add-item-btn').addEventListener('click', function () {
    const container = document.getElementById('items-container');

    const html = `
        <div class="row g-2 mb-2 item-row">
            <div class="col-md-4">
                <input type="text" name="items[${itemIndex}][category]" class="form-control" required>
            </div>
            <div class="col-md-3">
                <input type="number" name="items[${itemIndex}][quantity]" class="form-control" value="1" min="1" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="items[${itemIndex}][tooth_number]" class="form-control">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger remove-item">حذف</button>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', html);
    itemIndex++;
});

// حذف الأصناف الجديدة
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-item')) {
        e.target.closest('.item-row').remove();
    }
});

// حذف الأصناف الموجودة (تُرسل IDs إلى السيرفر)
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-existing-item')) {
        const id = e.target.getAttribute('data-id');
        const deleted = JSON.parse(document.getElementById('deleted-items').value);
        deleted.push(id);
        document.getElementById('deleted-items').value = JSON.stringify(deleted);
        e.target.closest('.item-row').remove();
    }
});
</script>
@endpush
