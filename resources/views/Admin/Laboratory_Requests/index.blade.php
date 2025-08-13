@extends('admin.layouts.app')

@section('title', 'قائمة طلبات المعمل')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>إدارة طلبات المعامل</h2>
        <a href="{{route('LaboratoryRequests.create')}}" class="btn btn-primary on-print">
            <i class="fas fa-plus ml-1"></i>
            إضافة طلب جديد
        </a>
    </div>

    <form action="" method="GET" class="mb-3 on-print">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="ابحث عن مريض او اسم المعمل..." value="{{ request('search') }}">
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
                        <th>الإجراءات</th>
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
                                    <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('LaboratoryRequests.edit', $request->id) }}" class="btn btn-sm btn-warning" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{route('LaboratoryRequests.destroy', $request->id) }}" method="POST" class="delete-form" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger delete-university" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
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
            <a href="#" onclick="window.print()" class="btn btn-light btn-sm on-print">
                <i class="fas fa-print"></i> طباعة
            </a>
            <div class="d-flex justify-content-center on-print">
                    {{$laboratoryRequests->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
