@extends('admin.layouts.app')
@section('title', 'إضافة مستودع ')
@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">إضافة مستودع جديد</h5>
        </div>
        <div class="card-body">
            <form action="{{route('storehouse.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">اسم المستودع </label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required >
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="location" class="form-label">العنوان:</label>
                    <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location') }}" required>
                    @error('location')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="contact_number" class="form-label">الهاتف:</label>
                    <input type="text"  maxlength="10" class="form-control @error('contact_number') is-invalid @enderror" id="contact_number" name="contact_number" value="{{ old('contact_number') }}" required>
                    @error('contact_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">إضافة المعمل</button>
                <a href="{{ route('storehouse.index') }}" class="btn btn-secondary">إلغاء</a>

            </form>
        </div>
    </div>
</div>
@endsection
