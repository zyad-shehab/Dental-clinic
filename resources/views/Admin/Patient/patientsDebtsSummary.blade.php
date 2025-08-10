@extends('admin.layouts.app')

@section('title', 'رصيد ديون المرضى')

@section('content')
<div class="container mt-4">
    <h4 class="mb-4">رصيد ديون جميع المرضى</h4>

    {{-- <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>اسم المريض</th>
                <th>الرصيد (الديون)</th>
                <th>آخر تاريخ تعامل</th>
            </tr>
        </thead>
        <tbody>
            @forelse($debts as $debt)
            <tr>
                <td>{{ $debt->patient_name }}</td>
                <td>{{ number_format($debt->balance, 2) }} ₪</td>
                <td>{{ $debt->last_date }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center">لا توجد ديون للمرضى</td>
            </tr>
            @endforelse
        </tbody>
    </table> --}}
    <form method="GET" action="{{ route('Report.patientsDebtsSummary') }}" class="mb-4">
    <div class="row">
        <div class="col-md-4">
            <label>ترتيب حسب:</label>
            <select name="sort" class="form-control" onchange="this.form.submit()">
                <option value="last_date_asc" {{ request('sort') == 'last_date_asc' ? 'selected' : '' }}>
                    التاريخ - تصاعدي
                </option>
                <option value="last_date_desc" {{ request('sort', 'last_date_desc') == 'last_date_desc' ? 'selected' : '' }}>
                    التاريخ - تنازلي
                </option>
                <option value="balance_asc" {{ request('sort') == 'balance_asc' ? 'selected' : '' }}>
                    المبلغ - تصاعدي
                </option>
                <option value="balance_desc" {{ request('sort') == 'balance_desc' ? 'selected' : '' }}>
                    المبلغ - تنازلي
                </option>
                <option value="age_asc" {{ request('sort') == 'age_asc' ? 'selected' : '' }}>
                    العمر - تصاعدي
                </option>
                <option value="age_desc" {{ request('sort') == 'age_desc' ? 'selected' : '' }}>
                    العمر - تنازلي
                </option>
            </select>
        </div>
    </div>
</form>


    <table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>آخر تاريخ تعامل</th>
            <th>الاسم</th>
            <th>الرصيد</th>
            <th>العنوان</th>
            <th>الهاتف</th>
            <th>العمر</th>            
        </tr>
    </thead>
    <tbody>
        @forelse($debts as $debt)
        <tr>
<td>{{ $debt->last_date ? date('Y-m-d', strtotime($debt->last_date)) : '-' }}</td>
            <td>{{ $debt->patient_name }}</td>
            <td>{{ number_format($debt->balance, 2) }}</td>
            <td>{{ $debt->address }}</td>
            <td>{{ $debt->phone }}</td>
            {{-- <td>{{ $debt->dob }}</td> --}}
            <td>{{ $debt->age }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center">لا توجد ديون للمرضى</td>
        </tr>
        @endforelse
    </tbody>
</table>
<div class="alert alert-info mt-3">
    <strong>إجمالي  الديون على المرضى: {{ number_format($total_debts, 2) }}</strong>
     
</div>

</div>
@endsection
