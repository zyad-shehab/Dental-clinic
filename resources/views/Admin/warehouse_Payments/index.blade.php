@extends('admin.layouts.app')
@section('title', 'عرض دفعات المستودع')

@section('content')
<div class="container mt-4">
    <a href="{{ route('warehousepayment.create') }}" class="btn btn-success mb-3">إضافة دفعة</a>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">قائمة الدفعات</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>تاريخ الدفعة</th>
                        <th>اسم المستودع</th>
                        <th>نقدًا</th>
                        <th>بطاقة</th>
                        <th>المجموع</th>
                        <th>ملاحظات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                        <tr>
                            <td>{{ $payment->payment_date }}</td>
                            <td>{{ $payment->storehouses->name ?? '---' }}</td>
                            <td>{{ $payment->paid_cash }}</td>
                            <td>{{ $payment->paid_card }}</td>
                            <td>{{ $payment->total }}</td>
                            <td>{{ $payment->notes }}</td>
                            <td>
                                <a href="{{ route('warehousepayment.edit', $payment->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                                <form action="{{ route('warehousepayment.destroy', $payment->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                </form>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
