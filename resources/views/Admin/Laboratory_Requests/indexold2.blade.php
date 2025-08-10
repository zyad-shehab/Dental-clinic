@extends('admin.layouts.app')

@section('title', 'قائمة طلبات المعمل')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">طلبات المعمل</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>اسم المريض</th>
                        <th>اسم المعمل</th>
                        <th>تاريخ الطلب</th>
                        <th>الصنف</th>
                        <th>العدد</th>
                        <th>رقم الأسنان</th>
                        <th>الحالة</th>
                        <th>ملاحظات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($laboratoryRequests as $request)
                        @forelse ($request->items as $index => $item)
                            <tr>
                                @if($index == 0)
                                    <td rowspan="{{ $request->items->count() }}">{{ $loop->parent->iteration }}</td>
                                    <td rowspan="{{ $request->items->count() }}">{{ $request->patient->name ?? '-' }}</td>
                                    <td rowspan="{{ $request->items->count() }}">{{ $request->laboratory->name ?? '-' }}</td>
                                    <td rowspan="{{ $request->items->count() }}">{{ $request->request_date }}</td>
                                @endif
                                <td>{{ $item->category }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->tooth_number }}</td>

                                @if($index == 0)
                                    <td rowspan="{{ $request->items->count() }}">
                                        @php
                                            $status = $request->status;
                                        @endphp
                                        @if($status == 'pending')
                                            <span class="badge bg-warning text-dark">قيد الانتظار</span>
                                        @elseif($status == 'in_progress')
                                            <span class="badge bg-info text-dark">قيد المعالجة</span>
                                        @elseif($status == 'completed')
                                            <span class="badge bg-success">مكتمل</span>
                                        @else
                                            <span class="badge bg-danger">ملغي</span>
                                        @endif
                                    </td>
                                    <td rowspan="{{ $request->items->count() }}">{{ $request->notes ?? '-' }}</td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td colspan="8" class="text-center">لا توجد أصناف لهذا الطلب</td>
                            </tr>
                        @endforelse
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">لا توجد طلبات</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
