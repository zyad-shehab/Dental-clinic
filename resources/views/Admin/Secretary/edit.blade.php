@extends('admin.layouts.app')
@section('title', 'تعديل الموظف')
@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-warning text-white">
            <h5 class="mb-0">تعديل بيانات الموظف</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.secretary.update', $secretary->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- الاسم --}}
                <div class="mb-3">
                    <label for="name" class="form-label">اسم الطبيب</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name', $secretary->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- التخصص --}}
                <div class="mb-3">
                    <label for="specialization" class="form-label">التخصص</label>
                    <input type="text" class="form-control @error('specialization') is-invalid @enderror"
                           id="specialization" name="specialization" value="{{ old('specialization', $secretary->specialization) }}" required>
                    @error('specialization') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- العنوان --}}
                <div class="mb-3">
                    <label for="address" class="form-label">العنوان</label>
                    <input type="text" class="form-control @error('address') is-invalid @enderror"
                           id="address" name="address" value="{{ old('address', $secretary->address) }}">
                    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- الهاتف --}}
                <div class="mb-3">
                    <label for="phone" class="form-label">الهاتف</label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                           id="phone" name="phone" pattern="\d{10}" maxlength="10"
                           value="{{ old('phone', $secretary->phone) }}">
                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- تاريخ الميلاد --}}
                <div class="mb-3">
                    <label for="date_of_birth" class="form-label">تاريخ الميلاد</label>
                    <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                           id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $secretary->date_of_birth) }}">
                    @error('date_of_birth') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- اسم المستخدم --}}
                <div class="mb-3">
                    <label for="username" class="form-label">اسم المستخدم</label>
                    <input type="text" class="form-control @error('username') is-invalid @enderror"
                           id="username" name="username" value="{{ old('username', $secretary->username) }}" required>
                    @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- كلمة المرور (اختياري) --}}
                <div class="mb-3">
                    <label for="password" class="form-label">كلمة المرور (اتركها فارغة إذا لا تريد التغيير)</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                           id="password" name="password">
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- تأكيد كلمة المرور --}}
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                </div>

                <button type="submit" class="btn btn-success">حفظ التعديلات</button>
                <a href="{{ route('admin.secretary.index') }}" class="btn btn-secondary">إلغاء</a>
            </form>
        </div>
    </div>
</div>
@endsection
