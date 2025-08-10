@extends('admin.layouts.app')
@section('title', 'عرض الجلسة')
@php
 use Carbon\Carbon;
@endphp
@section('content')
<div class="container mt-4 mb-5">
    <div class="card shadow-sm border rounded-3">
        <div class="card-body p-4">
                <h1 class="card-title mb-3 text-center text-success fw-bold fs-3">
                    <i class="bi bi-university me-2 text-success"></i> رقم الجلسة : {{ $session->id}} 
                </h1>
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">بيانات الجلسة</h5>
            </div>
            <div class="card-body"> 
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>الطبيب</th>
                                <th>المريض</th>
                                <th>التاريخ</th>
                                <th>من الساعة</th>
                                <th>الي الساعة</th>
                                <th>الخدمة</th>
                                <th>رقم السن </th>
                                <th>الدواء</th>
                                <th>ملاحظة</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $session->doctor->name ?? null}}</td>
                                <td>{{ $session->patient->name ?? null}}</td>
                                <td>{{ $session->session_date ?? null}}</td>
                                <td>{{ $session->start_time ?? null}}</td>
                                <td>{{ $session->end_time ?? null}}</td>
                                <td>
                                    @foreach ($session->services as $service)
                                        <li>{{$service->name}}</li>     
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($session->services as $service)
                                        <li>{{$service->pivot->tooth_number}}</li>
                                    @endforeach
                                </td>
                                <td>{{ $session->drug ?? null}}</td>
                                <td>{{ $session->notes ?? null}}</td>
                            </tr>  
                        </tbody>
                    </table>
            </div> 
            {{-- ///////////////المواعيد --}}
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">بيانات مالية</h5>
            </div>
            <div class="card-body"> 
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>المبلغ المطلوب</th>
                                <th>المدفوع كاش</th>
                                <th>المدفوع تطبيق</th>
                                <th>اسم صاحب الحساب</th>
                                <th>اجمالي المدفوع</th>
                                <th> المتبقي</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $session->required_amount}}</td>
                                <td>{{ $session->cash_payment ?? null}}</td>
                                <td>{{ $session->card_payment ?? null}}</td>
                                <td>{{ $session->name_of_bank_account ?? null}}</td>
                                <td>{{ $session->total_amount ?? null}}</td>
                                <td>{{ $session->remaining_amount ?? null}}</td>
                            </tr>  
                        </tbody>
                    </table>
            </div>
             {{--///////بيانات عن االمريض//////////  --}}
             <div class="card-header bg-primary text-white">
                <h5 class="mb-0">بيانات عن المريض</h5>
            </div>
            <div class="card-body"> 
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>الاسم</th>
                                <th>الجنس</th>
                                <th>العنوان</th>
                                <th>رقم الهاتف</th>
                                <th>أمراض مزمنة </th>
                                <th>حساسية</th>
                                <th>السن</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $session->patient->name ?? null}}</td>
                                <td>{{ $session->patient->gender === 'male' ? 'ذكر' : 'أنثى' }}</td>
                                <td>{{ $session->patient->address ?? null}}</td>
                                <td>{{ $session->patient->phone ?? null}}</td>
                                <td>{{ $session->patient->chronic_diseases ?? null}}</td>
                                <td>{{ $session->patient->allergies ?? null}}</td>
                                <td>{{ $session->patient->date_of_birth ? \Carbon\Carbon::parse($session->patient->date_of_birth)->age  : 'غير محدد'}}</td>
                            </tr>  
                        </tbody>
                    </table>
            </div>
        </div> 
        @if($session->xray_image)
            <div class="mb-4">
                <label class="form-label fw-bold">صورة الجلسة:</label>
                <div class="border rounded p-2" style="max-width: 300px;">
                    <a href="{{ asset('storage/' . $session->xray_image) }}" target="_blank">
                        <img src="{{ asset('storage/' . $session->xray_image) }}" alt="صورة الجلسة" class="img-fluid rounded shadow-sm">
                    </a>
                </div>
            </div>
        @else
            <p class="text-muted">لا توجد صورة  مرفقة.</p>
        @endif   
    </div>
</div>
@endsection