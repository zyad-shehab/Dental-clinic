@extends('admin.layouts.app')
@section('title', 'عرض الطبيب')

@section('content')
<div class="container mt-4 mb-5">
    <div class="card shadow-sm border rounded-3">
        <div class="card-body p-4">
                <h1 class="card-title mb-3 text-center text-success fw-bold fs-3">
                    <i class="bi bi-university me-2 text-success"></i> {{ $doctor->name}}
                </h1>
                <p class="text-center text-muted mb-4 small">نظرة عن الجلسات والمواعد</p>
                <hr class="mb-4 border-success opacity-10">

                <div class="row mb-4 justify-content-center">
                    <div class="col-md-7 col-lg-5">
                        <div class="bg-light p-3 rounded-2 shadow-sm d-flex align-items-center justify-content-center">
                            <i class="bi bi-globe me-2 fs-5 text-secondary"></i>
                            <h5 class="mb-0 text-dark fw-semibold">
                                التخصص : <span class="text-primary">{{ $doctor->specialization ?? null}}</span>
                            </h5>
                        </div>
                    </div>
                </div>
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">جلسات الطبيب</h5>
            </div>
            <div class="card-body"> 
                @if($doctor->clinicSessions->isEmpty())
                    <div class="alert alert-info">لا توجد جلسات مسجلة لهذا الطبيب.</div>
                @else
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>المريض</th>
                                <th>التاريخ</th>
                                <th>من الساعة</th>
                                <th>الي الساعة</th>
                                <th>الخدمة</th>
                                <th>السن</th>
                                <th>التكلفة</th>
                                <th>ملاحظة</th>
                            </tr>
                        </thead>
                        <tbody>
                                @foreach($sessions as $session)
                                    <tr>
                                        <td>{{ $loop->iteration + ($sessions->currentPage() - 1) * $sessions->perPage() }}</td>
                                        <td>{{ $session->patient->name ?? 'غير معروف' }}</td>
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
                <h5 class="mb-0">مواعيد الطبيب</h5>
            </div>
            <div class="card-body"> 
                @if($doctor->appointments->isEmpty())
                    <div class="alert alert-info">لا توجد مواعيد مسجلة لهذا الطبيب.</div>
                @else
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>المريض</th> 
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
                                        <td>{{ $appointment->patient->name ?? 'غير معروف' }}</td>
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
        </div>         
    </div>
</div>
@endsection