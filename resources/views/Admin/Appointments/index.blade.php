@extends('admin.layouts.app')
@section('title', ' إدارة عيادة روتس دانت')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>إدارة المواعيد</h2>
        <a href="{{route('appointments.create')}}" class="btn btn-primary">
            <i class="fas fa-plus ml-1"></i>
            إضافة موعيد جديدة
        </a>
    </div>
            {{--  البحث و فلتر الحالة والترتيب--}}
    <form id="filter-form" method="GET" action="{{ route('appointments.index') }}">
        {{-- البحث --}}
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="ابحث عن طبيب او مريض..." value="{{ request('search') }}">
            <button class="btn btn-outline-secondary" type="submit">
                <i class="fas fa-search"></i> بحث
            </button>
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary ms-2">
                <i class="fas fa-arrow-right"></i> رجوع
            </a>
        </div>

        {{--  الفلتر --}}
        <div class="d-flex gap-3 mb-3">
            <label>
                <input type="checkbox" name="status[]" value="pending"
                    {{ in_array('pending', request('status', [])) ? 'checked' : '' }}
                    onchange="document.getElementById('filter-form').submit();">
                في الانتظار
            </label>

            <label>
                <input type="checkbox" name="status[]" value="confirmed"
                    {{ in_array('confirmed', request('status', [])) ? 'checked' : '' }}
                    onchange="document.getElementById('filter-form').submit();">
                مكتملة
            </label>

            <label>
                <input type="checkbox" name="status[]" value="cancelled"
                    {{ in_array('cancelled', request('status', [])) ? 'checked' : '' }}
                    onchange="document.getElementById('filter-form').submit();">
                ملغاة
            </label>
        </div>
        {{-- الترتيب --}}
        <div class="col-md-3">
            <select name="sort" class="form-select">
                <option value="">ترتيب حسب...</option>
                <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>التاريخ من الأقدم</option>
                <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>التاريخ من الأحدث</option>
            </select>
        </div>
    </form>


    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>اسم المريض</th>
                            <th>اسم الطبيب</th>
                            <th>التاريخ</th> 
                            <th>من الساعة</th>
                            <th>إلي الساعة</th>
                            <th>الحالة</th>
                            <th>ملاحظة</th>
                            <th>الاجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $appointment)
                        <tr>
                            <td>{{ $loop->iteration + ($appointments->currentPage() - 1) * $appointments->perPage() }}</td>
                            <td>{{ $appointment->patient->name ?? null}}</td>
                            <td>{{ $appointment->doctor->name ?? null}}</td>
                            <td>{{ $appointment->appointment_date}}</td>
                            <td>{{ $appointment->start_time}}</td>
                            <td>{{ $appointment->end_time}}</td>
                            <td>
                                                @if ($appointment->status === 'pending')
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="bi bi-hourglass-split me-1"></i> انتظار
                                                    </span>
                                                @elseif ($appointment->status === 'confirmed')
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle-fill me-1"></i> مكتمل
                                                    </span>
                                                @elseif ($appointment->status === 'cancelled')
                                                    <span class="badge bg-danger">
                                                        <i class="bi bi-x-circle-fill me-1"></i> ملغي
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        <i class="bi bi-question-circle-fill me-1"></i> غير محددة
                                                    </span>
                                                @endif
                            </td>
                            <td>{{ $appointment->notes ?? '-'}}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-sm btn-warning" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{route('appointments.destroy', $appointment->id) }}" method="POST" class="delete-form" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger delete-university" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">لا يوجد مواعيد مسجلة</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{$appointments->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('filter-form');

        // checkboxes
        form.querySelectorAll('input[type="checkbox"]').forEach(input => {
            input.addEventListener('change', () => form.submit());
        });

        // البحث مع تأخير
        const searchInput = form.querySelector('input[name="search"]');
        let timer;
        searchInput.addEventListener('input', () => {
            clearTimeout(timer);
            timer = setTimeout(() => form.submit(), 500);
        });

        // الترتيب
        const sortSelect = form.querySelector('select[name="sort"]');
        sortSelect.addEventListener('change', () => form.submit());
    });
</script>
@endpush




