@extends('admin.layouts.app')
@section('title', 'تعديل المريض')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-warning text-white">
            <h5 class="mb-0">تعديل بيانات المريض</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.patients.update', $patient->id) }}" method="POST">
                @csrf
                @method('PUT')
                {{-- الاسم --}}
                <div class="mb-3">
                    <label for="name" class="form-label">اسم المريض</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name', $patient->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                {{-- الجنس --}}
                <div class="mb-3">
                    <label class="form-label d-block">الجنس</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" id="male" value="male"
                            {{ old('gender', $patient->gender) == 'male' ? 'checked' : '' }}>
                        <label class="form-check-label" for="male">ذكر</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" id="female" value="female"
                            {{ old('gender', $patient->gender) == 'female' ? 'checked' : '' }}>
                        <label class="form-check-label" for="female">أنثى</label>
                    </div>
                </div>

                {{-- العنوان --}}
                <div class="mb-3">
                    <label for="address" class="form-label">العنوان</label>
                    <input type="text" class="form-control @error('address') is-invalid @enderror"
                           id="address" name="address" value="{{ old('address', $patient->address) }}">
                    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- الهاتف --}}
                <div class="mb-3">
                    <label for="phone" class="form-label">الهاتف</label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                           id="phone" name="phone" pattern="\d{10}" maxlength="10"
                           value="{{ old('phone', $patient->phone) }}">
                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- تاريخ الميلاد --}}
                <div class="mb-3">
                    <label for="date_of_birth" class="form-label">تاريخ الميلاد</label>
                    <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                           id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $patient->date_of_birth) }}">
                    @error('date_of_birth') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label for="chronic_diseases" class="form-label">امراض مزمنة </label>
                    <input type="text" class="form-control @error('chronic_diseases') is-invalid @enderror"
                           id="chronic_diseases" name="chronic_diseases" value="{{ old('chronic_diseases', $patient->chronic_diseases) }}">
                    @error('chronic_diseases') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label for="allergies" class="form-label">حساسية</label>
                    <input type="text" class="form-control @error('allergies') is-invalid @enderror"
                           id="allergies" name="allergies" value="{{ old('allergies', $patient->allergies) }}">
                    @error('allergies') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label for="clinic_source" class="form-label">من أين عرفت العيادة ؟</label>
                    <input type="text" class="form-control @error('clinic_source') is-invalid @enderror"
                           id="clinic_source" name="clinic_source" value="{{ old('clinic_source', $patient->clinic_source) }}">
                    @error('clinic_source') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <button type="submit" class="btn btn-success">حفظ التعديلات</button>
                <a href="{{ route('admin.patients.index') }}" class="btn btn-secondary">إلغاء</a>
            </form>
        </div>
    </div>
</div>
@endsection
