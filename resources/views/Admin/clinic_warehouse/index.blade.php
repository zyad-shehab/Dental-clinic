@extends('admin.layouts.app')

@section('title', 'مستودع العيادة')

@section('content')
<div class="container mt-4">
   <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>اصناف مستودع العيادة</h2>
        
    </div>

    <form action="" method="GET" class="mb-3">
        <div class="input-group on-print">
            <input type="text" name="search" class="form-control" placeholder="ابحث عن الصنف..." value="{{ request('search') }}">
            <button class="btn btn-outline-secondary" type="submit">
                <i class="fas fa-search"></i> بحث
            </button>
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary ms-2">
                <i class="fas fa-arrow-right"></i> رجوع
            </a>
        </div>
    
    <div class="row g-2 align-items-end on-print">
        <div class="col-md-3">
            <hr>
            {{-- <label>تصفية حسب الحالة:</label> --}}
            <select name="status" class="form-control">
                <option value="">-- تصنيف حسب الحالة --</option>
                <option value="Available" {{ request('status') === 'Available' ? 'selected' : '' }}>متاح</option>
                <option value="finished" {{ request('status') === 'finished' ? 'selected' : '' }}>فارغ</option>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-outline-primary" type="submit">تطبيق</button>
        </div>
    </div>
    </form>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">اصناف المستودع</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>اسم الصنف</th>
                        <th>الكمية</th>
                        <th>تاريخ الانتهاء</th>
                        <th>الحالة</th>
                        <th class="on-print">الإجراء</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr @if($item->end_date && $item->status === 'Available' && \Carbon\Carbon::parse($item->end_date)->lte($alert_threshold)) style="background-color: #fff3cd;" @endif>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $item->item_name }}</td>
    <td>{{ $item->quantity }}</td>
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
        @if($item->status === 'Available')
            <span class="badge bg-success">متاح</span>
        @else
            <span class="badge bg-danger">فارغ</span>
        @endif
    </td>
    <td>
        <form class="on-print" action="{{ route('clinic_warehouse.toggle_status', $item->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm btn-warning">تغيير الحالة</button>
        </form>
    </td>
</tr>

                    @empty
                        <tr><td colspan="6" class="text-center">لا توجد أصناف حاليًا.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <a href="#" onclick="window.print()" class="btn btn-light btn-sm on-print">
                <i class="fas fa-print"></i> طباعة
            </a>
            <div class="d-flex justify-content-center on-print">
                        {{ $items->links() }}
            </div> 
        </div>
    </div>
</div>
@endsection
