@extends('admin.layouts.app')
@section('title', 'تعديل دفعة')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-warning text-white">
            <h5 class="mb-0">تعديل دفعة (صرف)</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('expenses.update', $expenses->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="date" class="form-label">تاريخ الدفعة</label>
                    <input type="date" name="date" class="form-control" value="{{ $expenses->date }}" required>
                </div>

                <div class="mb-3">
                    <label for="pay_to" class="form-label">الدفع إلي</label>
                    <input type="text" name="pay_to" class="form-control" value="{{ $expenses->pay_to }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">المبلغ نقدًا</label>
                    <input type="number" name="paid_cash" class="form-control"  value="{{ $expenses->paid_cash }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">المبلغ بنكي</label>
                    <input type="number" name="paid_card" class="form-control" step="0.01" value="{{ $expenses->paid_card }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">اسم الحساب البنكي</label>
                    <input type="text" name="name_of_bank_account" class="form-control" value="{{ $expenses->name_of_bank_account }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">ملاحظات</label>
                    <textarea name="note" class="form-control" rows="3">{{ $expenses->note }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">تحديث</button>
            </form>
        </div>
    </div>
</div>
@endsection
