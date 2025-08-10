@extends('admin.layouts.app')
@section('title', ' إدارة عيادة روتس دانت')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>إدارة المعامل</h2>
        <a href="{{route('laboratory.create')}}" class="btn btn-primary">
            <i class="fas fa-plus ml-1"></i>
            إضافة معمل جديد
        </a>
    </div>

    <form action="" method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="ابحث عن الطبيب..." value="{{ request('search') }}">
            <button class="btn btn-outline-secondary" type="submit">
                <i class="fas fa-search"></i> بحث
            </button>
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary ms-2">
                <i class="fas fa-arrow-right"></i> رجوع
            </a>
        </div>
    </form>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>اسم المعمل</th>
                            <th>العنوان</th>
                            <th>الهاتف</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($Laboratorys as $Laboratory)
                        <tr>
                            <td>{{ $Laboratory->name }}</td>
                            <td>{{ $Laboratory->location }}</td>
                            <td>{{ $Laboratory->contact_number }}</td>
                            <td>
                                <a href="{{route('laboratory.statement', $Laboratory->id)}}">تقرير مالي</a>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('laboratory.edit', $Laboratory->id) }}" class="btn btn-sm btn-warning" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{route('laboratory.destroy', $Laboratory->id) }}" method="POST" class="delete-form" style="display: inline;">
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
                            <td colspan="6" class="text-center">لا يوجد معامل مسجلة</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                    <div class="d-flex justify-content-center">
                        {{ $Laboratorys->links() }}
                    </div>            
            </div>
        </div>
    </div>
</div>
@endsection