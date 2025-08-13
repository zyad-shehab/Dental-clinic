@extends('admin.layouts.app')

@section('title', 'فواتير المشتريات')

@section('content')
<div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>إدارة مشتريات المعامل</h2>
        <a href="{{route('laboratoryPurchases.create')}}" class="btn btn-primary on-print">
            <i class="fas fa-plus ml-1"></i>
            إضافة فاتورة جديدة
        </a>
    </div>

    <form action="" method="GET" class="mb-3 on-print">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="ابحث عن المريض او المعمل..." value="{{ request('search') }}">
            <button class="btn btn-outline-secondary" type="submit">
                <i class="fas fa-search"></i> بحث
            </button>
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary ms-2">
                <i class="fas fa-arrow-right"></i> رجوع
            </a>
        </div>
        <br>
        <div class="row">
        <div class="col-md-3">
                <label>من تاريخ:</label>
                <input type="date" name="from" class="form-control"
                       value="{{ request('from', date('Y-m-d')) }}">
        </div>
        <div class="col-md-3">
                <label>إلى تاريخ:</label>
                <input type="date" name="to" class="form-control"
                       value="{{ request('to', date('Y-m-d')) }}">
        </div>
        </div>
    </form>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">فواتير المشتريات</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>المعمل</th>
                        <th>المريض</th>
                        <th>تاريخ الشراء</th>
                        <th>عدد الأصناف</th>
                        <th>الإجمالي</th>
                        <th>ملاحظات</th>
                        <th class="on-print">الاجراراءت </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchases as $purchase)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $purchase->laboratory->name ?? '-' }}</td>
                            <td>{{ $purchase->patient->name ?? '-' }}</td>
                            <td>{{ $purchase->purchase_date }}</td>
                            <td>{{ $purchase->items->count() }}</td>
                            <td>{{ number_format($purchase->total_amount, 2) }}</td>
                            <td>{{ $purchase->notes ?? '-' }}</td>
                            <td class="on-print">
                                <a href="{{route('laboratoryPurchases.show', $purchase->id) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('laboratoryPurchases.edit', $purchase->id) }}" class="btn btn-sm btn-warning" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{route('laboratoryPurchases.destroy', $purchase->id) }}" method="POST" class="delete-form" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger delete-university" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <a href="#" onclick="window.print()" class="btn btn-light btn-sm on-print">
                <i class="fas fa-print"></i> طباعة
            </a>
            <div class="d-flex justify-content-center on-print">
                {{ $purchases->links() }}
        </div>
    </div>
</div>
@endsection
