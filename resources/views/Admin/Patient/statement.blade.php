@extends('admin.layouts.app')
@section('title', 'كشف حساب المريض')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">كشف حساب المريض</h5>
            <small>{{ \Carbon\Carbon::now()->format('Y-m-d') }}</small>
        </div>
        <div class="card-body">

            <p><strong>اسم المريض:</strong> {{ $patient->name }}</p>
            <p><strong>الرقم التعريفي:</strong> {{ $patient->id }}</p>

            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>التاريخ</th>
                        <th>النوع</th>
                        <th>المبلغ</th>
                        <th>الرصيد التراكمي</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($statement as $item)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($item->date)->format('Y-m-d') }}</td>
                            <td>{{ $item->type }}</td>
                            <td>{{ number_format($item->amount, 2) }}</td>
                            <td>{{ number_format($item->balance, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">لا توجد بيانات.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="table-info">
                        <td colspan="3" class="text-end"><strong>الرصيد النهائي:</strong></td>
                        <td><strong>{{ number_format($balance, 2) }}</strong></td>
                    </tr>
                </tfoot>
            </table>
            <a href="#" onclick="window.print()" class="btn btn-light btn-sm on-print">
                <i class="fas fa-print"></i> طباعة
            </a>
        </div>
    </div>
</div>


@endsection
