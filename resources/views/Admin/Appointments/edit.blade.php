@extends('admin.layouts.app')
@section('title', 'تعديل الموعد')
@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-warning text-white">
            <h5 class="mb-0">تعديل بيانات الموعد</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('appointments.update',$appointment->id) }}" method="POST">
                @csrf
                @method('PUT')
                {{-- المريض --}}
                <div class="mb-3">
                    <label for="patient_name" class="form-label">اسم المريض</label>
                    <input type="text" id="patient_name" name="patient_name" class="form-control" 
                        value="{{ old('patient_name', $appointment->patient->name ?? '') }}" 
                        autocomplete="off" required>
                    
                    <input type="hidden" id="patient_id" name="patient_id" 
                        value="{{ old('patient_id', $appointment->patient->id ?? '') }}">

                    <div id="patient-error" class="text-danger mt-1" style="display:none;">
                        يرجى اختيار مريض من القائمة فقط.
                    </div>
                </div>
                {{-- الطبيب --}}
                
                <div class="mb-3">
                    <label for="doctor_name" class="form-label">اسم الطبيب</label>
                    <input type="text" id="doctor_name" name="doctor_name" class="form-control" 
                        value="{{ old('doctor_name', $appointment->doctor->name ?? '') }}" 
                        autocomplete="off" required>
                    
                    <input type="hidden" id="doctor_id" name="doctor_id" 
                        value="{{ old('doctor_id', $appointment->doctor->id ?? '') }}">

                    <div id="doctor-error" class="text-danger mt-1" style="display:none;">
                        يرجى اختيار طبيب من القائمة فقط.
                    </div>
                </div>
                {{-- تاريخ الموعد  --}}
                <div class="mb-3">
                    <label for="appointment_date" class="form-label">تاريخ الموعد</label>
                    <input type="date" class="form-control @error('appointment_date') is-invalid @enderror" id="appointment_date" name="appointment_date" value="{{ old('appointment_date',$appointment->appointment_date) }}" required >
                    @error('appointment_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                {{-- من --}}
                <div class="mb-3">
                    <label for="start_time" class="form-label">من الساعة </label>
                    <input type="time" class="form-control @error('start_time') is-invalid @enderror" id="start_time" name="start_time" value="{{ old('start_time',$appointment->start_time) }}" required >
                    @error('start_time')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                {{-- الي الساعة --}}
                <div class="mb-3">
                    <label for="end_time" class="form-label">إلي الساعة </label>
                    <input type="time" class="form-control @error('end_time') is-invalid @enderror" id="end_time" name="end_time" value="{{ old('end_time',$appointment->end_time) }}" required >
                    @error('end_time')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                {{-- الحالة --}}
                <div class="mb-3">
                    <label for="status" class="form-label">حالة الموعد</label>
                     <select name="status" id="status" class="form-control" required>
                        <option value="pending" {{ $appointment->status == 'pending' ? 'selected' : '' }}>انتظار</option>
                        <option value="confirmed" {{ $appointment->status == 'confirmed' ? 'selected' : '' }}>مكتمل</option>
                        <option value="cancelled" {{ $appointment->status == 'cancelled' ? 'selected' : '' }}>ملغي</option>     
                    </select>
                    @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                {{-- ملاحظة --}}
                <div class="mb-3">
                    <label for="notes" class="form-label">ملاحظة :</label>
                    <input type="text" class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" value="{{ old('notes',$appointment->notes) }}">
                    @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">تعديل</button>
                <a href="{{ route('appointments.index') }}" class="btn btn-secondary">إلغاء</a>
            </form>
        </div>
    </div>
</div>
@endsection
@include('Admin.partials.scripts');

