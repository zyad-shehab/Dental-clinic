@extends('admin.layouts.app')
@section('title', 'عرض  مصاريف العيادة')

@section('content')
<div class="container mt-4">
    <a href="{{ route('expenses.create') }}" class="btn btn-success mb-3">إضافة دفعة</a>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">قائمة المصاريف</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>تاريخ الدفعة</th>
                        <th>إلي</th>
                        <th>نقدًا</th>
                        <th>تطبيق</th>
                        <th>اسم الحساب</th>
                        <th>المجموع</th>
                        <th>ملاحظات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($expenses as $expense)
                        <tr>
                            <td>{{ $expense->date }}</td>
                            <td>{{ $expense->pay_to }}</td>
                            <td>{{ $expense->paid_cash }}</td>
                            <td>{{ $expense->paid_card }}</td>
                            <td>{{ $expense->name_of_bank_account }}</td>
                            <td>{{ $expense->total }}</td>
                            <td>{{ $expense->note }}</td>
                            <td>
                                <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-warning btn-sm">تعديل</a>
                                <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" style="display:inline;">
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
