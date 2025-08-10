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

                <div class="mb-3">
                    <label for="patient_id" class="form-label">اسم المريض</label>
                    <select name="patient_id" class="form-control" required>
                        <option value="">اختر اسم المريض</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}">{{ $patient->name ?? null }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">المبلغ نقدًا</label>
                    <input type="number" name="Paid_cash" class="form-control" step="0.01" value="0">
                </div>

                <div class="mb-3">
                    <label class="form-label">المبلغ بالبطاقة</label>
                    <input type="number" name="Paid_card" class="form-control" step="0.01" value="0">
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
