@extends('admin.layouts.app')
@section('title', ' إدارة عيادة روتس ')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>إدارة المرضى</h2>
        <a href="{{route('admin.patients.create')}}" class="btn btn-primary">
            <i class="fas fa-plus ml-1"></i>
            إضافة مريض جديد
        </a>
    </div>

    <form action="" method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="ابحث عن المريض..." value="{{ request('search') }}">
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
                            <th>عرض</th>
                            <th>اسم المريض</th>
                            <th>الجنس</th>
                            <th>العنوان</th>
                            <th>السن</th>
                            <th>الامراض المزمة</th>
                            <th>الحساسية</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patients as $patient)
                        <tr>
                            <td><a href="{{route('admin.patients.show' , $patient->id)}}" class="btn btn-sm btn-info" title="عرض">
                                        <i class="fas fa-eye"></i>
                            </a></td>
                            <td>{{ $patient->name }}</td>
                            <td>{{ $patient->gender =='male' ? 'ذكر' : 'أنثي' }}</td>
                            <td>{{ $patient->address }}</td>
                            <td>{{ $patient->date_of_birth }}</td>
                            <td>{{ $patient->chronic_diseases ?? 'لا يوجد امراض مزمنة' }}</td>
                            <td>{{ $patient->allergies ?? 'لا يوجد حساسية'}}</td>
                            <td>
                                <a href="{{route('patients.statement', $patient->id)}}">تقرير مالي</a>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.patients.edit', $patient->id) }}" class="btn btn-sm btn-warning" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{route('admin.patients.destroy', $patient->id) }}" method="POST" class="delete-form" style="display: inline;">
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
                            <td colspan="6" class="text-center">لا يوجد مرضى مسجلين</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                    <div class="d-flex justify-content-center">
                        {{$patients->links() }}
                    </div>            
            </div>
        </div>
    </div>
</div>
@endsection