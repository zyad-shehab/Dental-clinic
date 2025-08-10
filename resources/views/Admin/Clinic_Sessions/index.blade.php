@extends('admin.layouts.app')
@section('title', ' إدارة عيادة روتس دانت')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>إدارة الجلسات</h2>
        <a href="{{route('sessions.create')}}" class="btn btn-primary">
            <i class="fas fa-plus ml-1"></i>
            إضافة جلسة جديدة
        </a>
    </div>
    <form id="filter-form" method="GET" action="{{ route('sessions.index') }}">
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
                            <th>عرض</th>
                            <th>#</th>
                            <th>اسم المريض</th>
                            <th>اسم الطبيب</th>
                            <th>التاريخ</th>
                            <th>من الساعة</th>
                            <th>إلي الساعة</th>
                            <th>الخدمة</th>
                            <th>السن</th>
                            <th>التكلفة</th>
                            <th>ملاحظة</th>
                            <th>الاجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sessions as $session)
                        <tr>
                            <td><a href="{{route('sessions.show' , $session->id)}}" class="btn btn-sm btn-info" title="عرض">
                                        <i class="fas fa-eye"></i></a>
                            </td> 
                            <td>{{ $loop->iteration + ($sessions->currentPage() - 1) * $sessions->perPage() }}</td>
                            <td>{{ $session->patient->name ?? null}}</td>
                            <td>{{ $session->doctor->name ?? null}}</td>
                            <td>{{ $session->session_date ?? null}}</td>
                            <td>{{ $session->start_time ?? null}}</td>
                            <td>{{ $session->end_time ?? null}}</td>
                            <td>
                                @foreach ($session->services as $service)
                                    <li>{{$service->name}}</li>     
                                @endforeach
                            </td>
                            <td>
                                @foreach ($session->services as $service)
                                    <li>{{$service->pivot->tooth_number}}</li>
                                @endforeach
                            </td>
                            <td>{{ $session->required_amount ?? null}}</td>
                            <td>{{ $session->notes ?? null}}</td>
                            {{-- <td>{{ $loop->iteration + ($sessionService->currentPage() - 1) * $sessionService->perPage() }}</td>
                            <td>{{ $session->clinicSession->patient->name ?? null}}</td>
                            <td>{{ $session->clinicSession->doctor->name ?? null}}</td>
                            <td>{{ $session->clinicSession->session_date ?? null}}</td>
                            <td>{{ $session->clinicSession->start_time ?? null}}</td>
                            <td>{{ $session->clinicSession->end_time ?? null}}</td>
                            <td>{{ $session->service->name ?? null}}</td>
                            <td>{{ $session->tooth_number ?? null}}</td>
                            <td>{{ $session->clinicSession->required_amount ?? null }}</td>
                            <td>{{ $session->clinicSession->notes ?? '-'}}</td> --}}
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('sessions.edit', $session->id) }}" class="btn btn-sm btn-warning" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{route('sessions.destroy', $session->id) }}" method="POST" class="delete-form" style="display: inline;">
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
                            <td colspan="6" class="text-center">لا يوجد جلسات مسجلة</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{$sessions->links() }}
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