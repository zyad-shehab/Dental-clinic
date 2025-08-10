@extends('admin.layouts.app')
@section('title', 'إضافة دفعة جديدة')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">إضافة دفعة (صرف)</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('expenses.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="date" class="form-label">تاريخ الدفعة</label>
                    <input type="date" name="date" class="form-control" value="{{ \Carbon\Carbon::now()->toDateString() }}" required>
                </div>

                <div class="mb-3">
                    <label for="pay_to" class="form-label">الدفع الي</label>
                    <input type="text" name="pay_to" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">المبلغ نقدًا</label>
                    <input type="number" name="paid_cash" class="form-control" >
                </div>

                <div class="mb-3">
                    <label class="form-label">المبلغ بنكي</label>
                    <input type="number" name="paid_card" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">اسم الحساب البنكي</label>
                    <input type="text" name="name_of_bank_account" value="محمد زاهر حرزالله" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">ملاحظات</label>
                    <textarea name="note" class="form-control" rows="3"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">حفظ الدفعة</button>
            </form>
        </div>
    </div>
</div>
@endsection
