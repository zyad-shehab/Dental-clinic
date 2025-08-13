@extends('admin.layouts.app')

@section('title', 'تفاصيل الفاتورة')

@section('content')
<div class="container mt-4">

    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">تفاصيل الفاتورة رقم #{{ $purchase->id }}</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4"><strong>المعمل:</strong> {{ $purchase->laboratory->name ?? '-' }}</div>
                <div class="col-md-4"><strong>المريض:</strong> {{ $purchase->patient->name ?? '-' }}</div>
                <div class="col-md-4"><strong>تاريخ الشراء:</strong> {{ $purchase->purchase_date }}</div>
            </div>
        </div>
    </div>

    {{-- جدول الأصناف --}}
    <div class="card shadow mb-4">
        <div class="card-header bg-secondary text-white">
            <h6 class="mb-0">الأصناف</h6>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>اسم الصنف</th>
                        <th>رقم الأسنان</th>
                        <th>السعر</th>
                        <th>الكمية</th>
                        <th>الإجمالي</th>
                        <th>ملاحظات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($purchase->items as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->item_name }}</td>
                            <td>{{ $item->number_of_teeth ?? '-' }}</td>
                            <td>{{ number_format($item->price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->total, 2) }}</td>
                            <td>{{ $item->notes ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">لا توجد أصناف لهذه الفاتورة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- إجمالي الفاتورة والملاحظات --}}
    <div class="card shadow">
        <div class="card-body">
            <div class="mb-3">
                <strong>ملاحظات الفاتورة:</strong>
                <div class="border p-2 bg-light">
                    {{ $purchase->notes ?? 'لا توجد ملاحظات' }}
                </div>
            </div>
            <div class="text-end">
                <strong>الإجمالي الكلي للفاتورة:</strong>
                <span class="fs-5 text-success">{{ number_format($purchase->total_amount, 2) }} شيكل</span>
            </div>
                <a href="#" onclick="window.print()" class="btn btn-light btn-sm on-print">
                                <i class="fas fa-print"></i> طباعة
                </a>
        </div>
    </div>

</div>
@endsection
