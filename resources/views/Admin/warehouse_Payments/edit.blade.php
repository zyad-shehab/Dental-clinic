@extends('admin.layouts.app')
@section('title', 'تعديل دفعة')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-warning text-white">
            <h5 class="mb-0">تعديل دفعة</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('warehousepayment.update', $payment->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="payment_date" class="form-label">تاريخ الدفعة</label>
                    <input type="date" name="payment_date" class="form-control" value="{{ $payment->payment_date }}" required>
                </div>

                <div class="mb-3">
                    <label for="storehouses_id" class="form-label">المستودع</label>
                    <select name="storehouses_id" class="form-control" required>
                        @foreach($storehouse as $store)
                            <option value="{{ $store->id }}"
                                {{ $store->id == $payment->storehouses_id ? 'selected' : '' }}>
                                {{ $store->name }}
                            </option>
                        @endforeach
                        

                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">المبلغ نقدًا</label>
                    <input type="number" name="Paid_cash" class="form-control"  value="{{ $payment->paid_cash }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">المبلغ بالبطاقة</label>
                    <input type="number" name="Paid_card" class="form-control"  value="{{ $payment->paid_card }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">اسم الحساب البنكي</label>
                    <input type="text" name="name_of_bank_account" class="form-control" value="{{ $payment->name_of_bank_account }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">ملاحظات</label>
                    <textarea name="notes" class="form-control" rows="3">{{ $payment->notes }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">تحديث</button>
            </form>
        </div>
    </div>
</div>
@endsection
