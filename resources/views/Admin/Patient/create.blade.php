@extends('admin.layouts.app')
@section('title', 'إضافة مرضى')
@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">إضافة مريض جديد</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.patients.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">اسم المريض </label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required >
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

               <label>الجنس:</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="male" value="male" {{ old('gender') == 'male' ? 'checked' : '' }}>
                            <label class="form-check-label" for="male">ذكر</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="female" value="female" {{ old('gender') == 'female' ? 'checked' : '' }}>
                            <label class="form-check-label" for="female">أنثى</label>
                        </div>
                        @error('gender')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                <hr>
                <div class="mb-3">
                    <label for="address" class="form-label">العنوان:</label>
                    <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}" required>
                    @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">الهاتف:</label>
                    <input type="text" pattern="\d{10}" maxlength="10" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required>
                    @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="date_of_birth" class="form-label">تاريخ الملاد:</label>
                    <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" required>
                    @error('date_of_birth')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="chronic_diseases" class="form-label">أمراض مزمنة:</label>
                    <input type="text" class="form-control @error('chronic_diseases') is-invalid @enderror" id="chronic_diseases" name="chronic_diseases" value="{{ old('chronic_diseases') }}">
                    @error('chronic_diseases')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="allergies" class="form-label">حساسية :</label>
                    <input type="text" class="form-control @error('allergies') is-invalid @enderror" id="allergies" name="allergies" value="{{ old('allergies') }}" >
                    @error('allergies')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="clinic_source" class="form-label">من اين عرفت العيادة ؟</label>
                    <input type="text" class="form-control @error('clinic_source') is-invalid @enderror" id="clinic_source" name="clinic_source" value="{{ old('clinic_source') }}" >
                    @error('clinic_source')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>                
                <button type="submit" class="btn btn-primary">إضافة طبيب</button>
                <a href="{{ route('admin.patients.index') }}" class="btn btn-secondary">إلغاء</a>

            </form>
        </div>
    </div>
</div>
@endsection
