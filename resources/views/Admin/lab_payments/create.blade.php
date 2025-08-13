@extends('admin.layouts.app')
@section('title', 'إضافة دفعة جديدة')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">إضافة دفعة للمعمل</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('labpayment.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="payment_date" class="form-label">تاريخ الدفعة</label>
                    <input type="date" name="payment_date" class="form-control" value="{{ \Carbon\Carbon::now()->toDateString() }}" required>
                </div>

                <div class="mb-3">
                    <label for="laboratories_id" class="form-label">المعمل</label>
                    <select name="laboratories_id" class="form-control" required>
                        <option value="">اختر المعمل</option>
                        @foreach($laboratories as $lab)
                            <option value="{{ $lab->id }}">{{ $lab->name ?? null }}</option>
                        @endforeach
                    </select>
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
                    <input type="text" name="name_of_bank_account" class="form-control" value="محمد زاهر حرزالله">
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
