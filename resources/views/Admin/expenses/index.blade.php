@extends('admin.layouts.app')
@section('title', 'عرض  مصاريف العيادة')

@section('content')
<div class="container mt-4">
    <a href="{{ route('expenses.create') }}" class="btn btn-success mb-3 on-print">إضافة دفعة</a>
        
        <form action="{{ route('expenses.index') }}" method="GET" class="mb- on-print">
                <div class="row">
                    <div class="col-md-3">
                        <label for="from">من تاريخ:</label>
                        <input type="date" name="from" class="form-control" value="{{ request('from', date('Y-m-d')) }}">
                    </div>
                    <div class="col-md-3">
                        <label for="to">إلى تاريخ:</label>
                        <input type="date" name="to" class="form-control" value="{{ request('to', date('Y-m-d')) }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">بحث</button>
                    </div>
                </div>
        </form>
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
                        <th class="on-print">الاجراءات</th>
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
                            <td class="on-print">
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
            <a href="#" onclick="window.print()" class="btn btn-light btn-sm on-print">
                <i class="fas fa-print"></i> طباعة
            </a>
            <div class="d-flex justify-content-center on-print">
                {{ $expenses->links() }}
            </div> 
        </div>
    </div>
</div>
@endsection
