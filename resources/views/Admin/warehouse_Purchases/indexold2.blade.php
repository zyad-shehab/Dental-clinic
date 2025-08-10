@extends('admin.layouts.app')

@section('title', 'فواتير مشتريات المخزن')

@section('content')
<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 text-primary"><i class="fas fa-warehouse me-2"></i> فواتير مشتريات المخزن</h4>
        <a href="{{ route('warehousePurchases.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-1"></i> إضافة فاتورة جديدة
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">قائمة الفواتير</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0 align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>المخزن</th>
                            <th>تاريخ الشراء</th>
                            <th>عدد الأصناف</th>
                            <th>الإجمالي</th>
                            <th>ملاحظات</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($purchases as $purchase)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $purchase->storehouse->name ?? '-' }}</td>
                                <td>{{ $purchase->purchase_date }}</td>
                                <td>{{ $purchase->items->count() }}</td>
                                <td>{{ number_format($purchase->total_amount, 2) }}</td>
                                <td>{{ $purchase->notes ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('warehousePurchases.show', $purchase->id) }}" class="btn btn-sm btn-info" title="عرض">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('warehousePurchases.edit', $purchase->id) }}" class="btn btn-sm btn-warning" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('warehousePurchases.destroy', $purchase->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف الفاتورة؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">لا توجد فواتير حالياً.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
