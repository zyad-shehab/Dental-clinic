@extends('admin.layouts.app')

@section('title', 'عرض فاتورة')

@section('content')
<div class="container mt-4">

    <div class="card mb-4 shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">تفاصيل الفاتورة #{{ $purchase->id }}</h5>
        </div>
        <div class="card-body">
            <p><strong>المستودع:</strong> {{ $purchase->storehouse->name ?? '-' }}</p>
            <p><strong>تاريخ الشراء:</strong> {{ $purchase->purchase_date }}</p>
            <p><strong>الملاحظات:</strong> {{ $purchase->notes ?? 'لا توجد' }}</p>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-secondary text-white">
            <h6 class="mb-0">الأصناف</h6>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>اسم الصنف</th>
                        <th>الكمية</th>
                        <th>السعر</th>
                        <th>الإجمالي</th>
                        <th>تاريخ الانتهاء</th>
                        <th>الحالة</th>
                        <th>ملاحظات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchase->items as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->item_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->price, 2) }}</td>
                            <td>{{ number_format($item->total, 2) }}</td>
                          @php
    $endDate = $item->end_date ? \Carbon\Carbon::parse($item->end_date) : null;
@endphp
<td>
    {{ $item->end_date ?? '-' }}

    @if($endDate && $item->status === 'Available')
        @if($endDate->isPast())
            <span class="text-danger fw-bold d-block">❌ منتهي</span>
        @elseif($endDate->lte($alert_threshold))
            <span class="text-warning fw-bold d-block">⚠ يوشك على الانتهاء</span>
        @endif
    @endif
</td>
                            <td>
                                @if($item->status == 'Available')
                                    <span class="badge bg-success">متاح</span>
                                @else
                                    <span class="badge bg-danger">فارغ</span>
                                @endif
                            </td>
                            <td>{{ $item->notes ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <a href="#" onclick="window.print()" class="btn btn-light btn-sm on-print">
                <i class="fas fa-print"></i> طباعة
            </a>

            <div class="p-3 text-end">
                <strong>الإجمالي الكلي:</strong>
                <span class="fw-bold">{{ number_format($purchase->total_amount, 2) }} شيكل</span>
            </div>
        </div>
    </div>

</div>
@endsection
