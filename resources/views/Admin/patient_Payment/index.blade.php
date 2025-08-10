@extends('admin.layouts.app')
@section('title', 'عرض دفعات المرضى')

@section('content')
<div class="container mt-4">
    <a href="{{ route('patientPayment.create') }}" class="btn btn-success mb-3">إضافة دفعة</a>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">قائمة الدفعات</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>تاريخ الدفعة</th>
                        <th>اسم المريض </th>
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
                            <td>{{ $payment->patient->name ?? '---' }}</td>
                            <td>{{ $payment->paid_cash }}</td>
                            <td>{{ $payment->paid_card }}</td>
                            <td>{{ $payment->total }}</td>
                            <td>{{ $payment->notes }}</td>
                            <td>
                                <a href="{{ route('patientPayment.edit', $payment->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                                <form action="{{ route('patientPayment.destroy', $payment->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                </form>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        الرصيد : 
    </div>
</div>
@endsection
