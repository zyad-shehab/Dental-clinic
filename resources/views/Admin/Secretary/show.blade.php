@extends('admin.layouts.app')
@section('title', 'عرض الموظف')
@section('content')
<div class="container mt-4 mb-5">
    <div class="card shadow-sm border rounded-3">
        <div class="card-body p-4">
                <h1 class="card-title mb-3 text-center text-success fw-bold fs-3">
                    <i class="bi bi-university me-2 text-success"></i> {{$secretary->name}}
                </h1>
                <p class="text-center text-muted mb-4 small">بيانات الموظف</p>
                <hr class="mb-4 border-success opacity-10">

                <div class="row mb-4 justify-content-center">
                    <div class="col-md-7 col-lg-5">
                        <div class="bg-light p-3 rounded-2 shadow-sm d-flex align-items-center justify-content-center">
                            <i class="bi bi-globe me-2 fs-5 text-secondary"></i>
                            <h5 class="mb-0 text-dark fw-semibold">
                                التخصص : <span class="text-primary">{{$secretary->specialization ?? null}}</span>
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="row mb-4 justify-content-center">
                    <div class="col-md-7 col-lg-5">
                        <div class="bg-light p-3 rounded-2 shadow-sm d-flex align-items-center justify-content-center">
                            <i class="bi bi-globe me-2 fs-5 text-secondary"></i>
                            <h5 class="mb-0 text-dark fw-semibold">
                                العنوان : <span class="text-primary">{{$secretary->address ?? null}}</span>
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="row mb-4 justify-content-center">
                    <div class="col-md-7 col-lg-5">
                        <div class="bg-light p-3 rounded-2 shadow-sm d-flex align-items-center justify-content-center">
                            <i class="bi bi-globe me-2 fs-5 text-secondary"></i>
                            <h5 class="mb-0 text-dark fw-semibold">
                                الهاتف : <span class="text-primary">{{$secretary->phone ?? null}}</span>
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="row mb-4 justify-content-center">
                    <div class="col-md-7 col-lg-5">
                        <div class="bg-light p-3 rounded-2 shadow-sm d-flex align-items-center justify-content-center">
                            <i class="bi bi-globe me-2 fs-5 text-secondary"></i>
                            <h5 class="mb-0 text-dark fw-semibold">
                                تاريخ الميلاد : <span class="text-primary">{{$secretary->date_of_birth ?? null}}</span>
                            </h5>
                        </div>
                    </div>
                </div>             
        </div>         
    </div>
</div>
@endsection