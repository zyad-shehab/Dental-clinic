@extends('admin.layouts.app')
@section('title', 'تعديل المعمل')
@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-warning text-white">
            <h5 class="mb-0">تعديل بيانات المعمل</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('laboratory.update', $laboratory->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">اسم المعمل </label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                        value="{{ old('name', $laboratory->name) }}" >
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="location" class="form-label">العنوان:</label>
                    <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location"
                        value="{{ old('location', $laboratory->location) }}" >
                    @error('location')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="contact_number" class="form-label">الهاتف:</label>
                    <input type="text" maxlength="10" class="form-control @error('contact_number') is-invalid @enderror" id="contact_number" name="contact_number"
                        value="{{ old('contact_number', $laboratory->contact_number) }}" >
                    @error('contact_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-warning">حفظ التعديلات</button>
                <a href="{{ route('laboratory.index') }}" class="btn btn-secondary">إلغاء</a>
            </form>
        </div>
    </div>
</div>
@endsection
