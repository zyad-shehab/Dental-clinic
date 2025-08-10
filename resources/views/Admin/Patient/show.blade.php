@extends('admin.layouts.app')
@section('title', 'عرض المريض')

@section('content')
<div class="container mt-4 mb-5">
    <div class="card shadow-sm border rounded-3">
        <div class="card-body p-4">
            @php
    use Carbon\Carbon;

    $birthDate = $patient->date_of_birth;
    $isBirthday = false;

    if ($birthDate) {
        $birth = Carbon::parse($birthDate);
        $isBirthday = $birth->isBirthday(); // يتحقق من اليوم والشهر فقط
    }
@endphp

@if ($isBirthday)
    <div class="alert alert-success text-center fw-bold" role="alert">
        🎉 عيد ميلاد سعيد يا {{ $patient->name }}! نتمنى لك سنة مليئة بالصحة والسعادة! 🎂
    </div>
@endif
        <h1 class="card-title mb-3 text-center text-success fw-bold fs-3">
            <i class="bi bi-university me-2 text-success"></i> {{$patient->name}}
        </h1>
            <p class="text-center text-muted mb-4 small">نظرة عن الجلسات والمواعد</p>
        <div class="container mt-3">
        <div class="row justify-content-center">
            <div class="col-auto">
                <span class="fw-bold"> رقم الهاتف:</span> {{ $patient->phone ?? 'غير محدد' }}
            </div>
            <div class="col-auto">
                <span class="fw-bold"> العنوان:</span> {{ $patient->address ?? 'غير محدد' }}
            </div>
            <div class="col-auto">
                <span class="fw-bold"> السن:</span>
                {{ $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->age . ' سنوات' : 'غير محدد' }}
                <br>
            </div>    
            <div class="col-auto">    
                <span class="fw-bold"> تاريخ الميلاد:</span>
                {{$patient->date_of_birth}}
            </div>
            <div class="col-auto">
                <span class="fw-bold"> الجنس:</span>
                {{ $patient->gender === 'male' ? 'ذكر' : 'أنثى' }}
            </div>
        </div>
        <hr class="mb-4 border-success opacity-10">
        <div class="row justify-content-center">
            <div class="col-auto">
                <span class="fw-bold"> امراض مزمنة:</span>
                {{ $patient->chronic_diseases ?? 'لا يوجد' }}
            </div>
            <div class="col-auto">
                <span class="fw-bold"> حساسية:</span>
                {{ $patient->allergies  ?? 'لا يوجد' }}
            </div>
            <div class="col-auto">
                <span class="fw-bold">من اين عرف العيادة ؟</span>
                {{ $patient->clinic_source  ?? 'غير محدد' }}
            </div>
        </div>
        </div>
        <hr class="mb-4 border-success opacity-10">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">جلسات المريض</h5>
            </div>
            <div class="card-body"> 
                @if($patient->clinicSessions->isEmpty())
                    <div class="alert alert-info">لا توجد جلسات مسجلة لهذا المريض.</div>
                @else
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الطبيب</th>
                                <th>التاريخ</th>
                                <th>من الساعة</th>
                                <th>الي الساعة</th>
                                <th>الخدمة</th>
                                <th>رقم السن</th>
                                <th>التكلفة</th>
                                <th>ملاحظة</th>
                            </tr>
                        </thead>
                        <tbody>
                                @foreach($sessions as $session)
                                    <tr>
                                        <td>{{ $loop->iteration + ($sessions->currentPage() - 1) * $sessions->perPage() }}</td>
                                        <td>{{ $session->doctor->name ?? 'غير معروف' }}</td>
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
                                        <td>{{ $session->required_amount ?? 'غير محددة' }}</td>
                                        <td>{{ $session->description ?? '-' }}</td>
                                    </tr>
                                @endforeach
                        </tbody>
                    </table>
                    {{-- روابط الترقيم --}}
                    <div class="d-flex justify-content-center">
                        {{ $sessions->links() }}
                    </div>
                @endif
            </div> 
            {{-- ///////////////المواعيد --}}
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">مواعيد المريض</h5>
            </div>
            <div class="card-body"> 
                @if($patient->appointments->isEmpty())
                    <div class="alert alert-info">لا توجد مواعيد مسجلة لهذا المريض.</div>
                @else
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الطبيب</th>                                
                                <th>التاريخ</th>
                                <th>من الساعة</th>
                                <th>الي الساعة</th>
                                <th>حالة الموعد</th>
                                <th>ملاحظة</th>
                            </tr>
                        </thead>
                        <tbody>
                                @foreach($appointments as $appointment)
                                    <tr>
                                        <td>{{ $loop->iteration + ($appointments->currentPage() - 1) * $appointments->perPage() }}</td>
                                        <td>{{ $appointment->doctor->name ?? 'غير معروف' }}</td>
                                        <td>{{ $appointment->appointment_date ?? null}}</td>
                                        <td>{{ $appointment->start_time ?? null}}</td>
                                        <td>{{ $appointment->end_time ?? null}}</td>
                                        <td>
                                                @if ($appointment->status === 'pending')
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="bi bi-hourglass-split me-1"></i> انتظار
                                                    </span>
                                                @elseif ($appointment->status === 'confirmed')
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle-fill me-1"></i> مكتمل
                                                    </span>
                                                @elseif ($appointment->status === 'cancelled')
                                                    <span class="badge bg-danger">
                                                        <i class="bi bi-x-circle-fill me-1"></i> ملغي
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        <i class="bi bi-question-circle-fill me-1"></i> غير محددة
                                                    </span>
                                                @endif
                                        </td>
                                        <td>{{ $appointment->note ?? '-' }}</td>
                                    </tr>
                                @endforeach
                        </tbody>
                    </table>
                    {{-- روابط الترقيم --}}
                    <div class="d-flex justify-content-center">
                        {{ $sessions->links() }}
                    </div>
                @endif
            </div> 
            {{-- ///////////////المالية --}}
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">بيانات مالية</h5>
            </div>
            <div class="card-body"> 
                @if($payments->isEmpty())
                    <div class="alert alert-info">لا توجد دفعات مسجلة لهذا المريض.</div>
                @else
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>التاريخ</th>  
                                <th>المدفوع كاش</th>                              
                                <th>المدفوع تطبيق</th>                              
                                <th>اسم صاحب الحساب</th>                              
                                <th>الاجمالي</th>                              
                                <th>ملاحظة</th>
                            </tr>
                        </thead>
                        <tbody>
                                @foreach($payments as $payment)
                                    <tr>
                                        <td>{{ $loop->iteration + ($payments->currentPage() - 1) * $payments->perPage() }}</td>
                                        <td>{{ $payment->payment_date ?? 'غير معروف' }}</td>
                                        <td>{{ $payment->paid_cash ?? null}}</td>
                                        <td>{{ $payment->paid_card ?? null}}</td>
                                        <td>{{ $payment->name_of_bank_account ?? null}}</td>
                                        <td>{{ $payment->total ?? null}}</td>
                                        <td>{{ $payment->note ?? '-' }}</td>
                                    </tr>
                                @endforeach
                        </tbody>
                    </table>
                    {{-- روابط الترقيم --}}
                    {{-- <div class="d-flex justify-content-center">
                        {{ $sessions->links() }}
                    </div> --}}
                @endif
            </div>
        </div>         
    </div>
</div>
@endsection