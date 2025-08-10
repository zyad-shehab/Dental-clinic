@extends('Admin.layouts.app')
@section('title', 'الرئيسية')
@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-3 mb-4" >
            <div class="admin-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">إجمالي المرضى</h6>
                        <h3 class="mb-0">{{ $patientCount }}</h3>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded">
                        <i class="fas fa-users text-primary fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="admin-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">إجمالي الجلسات</h6>
                        <h3 class="mb-0">{{ $sessionCount }}</h3>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded">
                        <i class="fas fa-school  text-primary fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="admin-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">إجمالي الخدمات</h6>
                        <h3 class="mb-0">{{ $servicesCount }}</h3>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded">
                        <i class="fas fa-store text-primary fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
