@extends('admin.layouts.app')
@section('title', 'إضافة موعد ')
@php
    use Carbon\Carbon;
    $now = Carbon::now();
    $defaultDate = $now->toDateString(); // YYYY-MM-DD
    $defaultStart = $now->format('H:i'); // HH:MM
    $defaultEnd = $now->copy()->addMinutes(30)->format('H:i'); // HH:MM + 30 دقيقة
@endphp

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">إضافة موعد جديد</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('appointments.store') }}" method="POST">
            @csrf

                {{-- المريض --}}  
                <div class="mb-3">
                        <label for="patient_name" class="form-label">اختر المريض</label>
                        <input type="text" id="patient_name" name="patient_name" class="form-control" autocomplete="off" placeholder="ابحث عن اسم المريض" required>
                        <input type="hidden" id="patient_id" name="patient_id">
                    <div id="patient-error" class="text-danger mt-1" style="display:none;">يرجى اختيار مريض من القائمة فقط.</div>
                </div>

                {{-- الطبيب --}}
                
                <div class="mb-3">
                        <label for="doctor_name" class="form-label">اختر الطيب</label>
                        <input type="text" id="doctor_name" name="doctor_name" class="form-control" autocomplete="off" placeholder="ابحث عن اسم الطبيب" required>
                        <input type="hidden" id="doctor_id" name="doctor_id">
                    <div id="doctor-error" class="text-danger mt-1" style="display:none;">يرجى اختيار طبيب من القائمة فقط.</div>
                </div>
                
                {{-- تاريخ الموعد  --}}
                <div class="mb-3">
                    <label for="appointment_date" class="form-label">تاريخ الموعد</label>
                    <input type="date" class="form-control @error('appointment_date') is-invalid @enderror" id="appointment_date" name="appointment_date" value="{{ old('appointment_date',$defaultDate) }}" required >
                    @error('appointment_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                {{-- من --}}
                <div class="mb-3">
                    <label for="start_time" class="form-label">من الساعة </label>
                    <input type="time" class="form-control @error('start_time') is-invalid @enderror" id="start_time" name="start_time" value="{{ old('start_time',$defaultStart) }}" required >
                    @error('start_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                {{-- الي الساعة --}}
                <div class="mb-3">
                    <label for="end_time" class="form-label">إلي الساعة </label>
                    <input type="time" class="form-control @error('end_time') is-invalid @enderror" id="end_time" name="end_time" value="{{ old('end_time',$defaultEnd) }}" required >
                    @error('end_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="notes" class="form-label">ملاحظة :</label>
                    <input type="text" class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" value="{{ old('notes') }}">
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">إضافة موعد</button>
                <a href="{{ route('appointments.index') }}" class="btn btn-secondary">إلغاء</a>
            </form>
        </div>
    </div>
</div>
@endsection

@include('Admin.partials.scripts');