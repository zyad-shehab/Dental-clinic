@extends('admin.layouts.app')
@section('title', 'إضافة خدمة')
@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">إضافة خدمة جديدة</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.services.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">اسم الخدمة </label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required >
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">وصف الخدمة:</label>
                    <input type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description" value="{{ old('description') }}">
                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">إضافة خدمة</button>
                <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">إلغاء</a>

            </form>
        </div>
    </div>
</div>
@endsection
