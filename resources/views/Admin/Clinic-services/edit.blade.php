@extends('admin.layouts.app')
@section('title', 'تعديل الخدمة')
@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-warning text-white">
            <h5 class="mb-0">تعديل بيانات الخدمة</h5>
        </div>
        <div class="card-body">
            <form action="{{route('admin.services.update', $services->id) }}" method="POST">
                @csrf
                @method('PUT')
                {{-- الاسم --}}
                <div class="mb-3">
                    <label for="name" class="form-label">اسم الخدمة :</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name', $services->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- الوصف --}}
                <div class="mb-3">
                    <label for="description" class="form-label">وصف الخدمة :</label>
                    <input type="text" class="form-control @error('description') is-invalid @enderror"
                           id="description" name="description" value="{{ old('description', $services->description) }}">
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-success">حفظ التعديلات</button>
                <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">إلغاء</a>
            </form>
        </div>
    </div>
</div>
@endsection
