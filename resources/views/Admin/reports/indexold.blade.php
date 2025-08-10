@extends('admin.layouts.app') {{-- تأكد أن ملف layout موجود --}}

@section('title', 'التقرير المالي')

@section('content')
<div class="container mt-4">
    {{-- <h4 class="mb-4">التقرير المالي من {{ request('from') }} إلى {{ request('to') }}</h4>
<form method="GET" action="{{ route('Report.getReport') }}" class="mb-4">
    <div class="row">
        <div class="col-md-4">
            <label>من تاريخ:</label>
            <input type="date" name="from" class="form-control" value="{{ request('from') }}">
        </div>
        <div class="col-md-4">
            <label>إلى تاريخ:</label>
            <input type="date" name="to" class="form-control" value="{{ request('to') }}">
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary">عرض التقرير</button>
        </div>
    </div>
</form> --}}
<form method="GET" action="{{ route('Report.getReport') }}" class="mb-4">
    <div class="row">
        <div class="col-md-4">
            <label>من تاريخ:</label>
            <input type="date" name="from" class="form-control"
                value="{{ request('from', date('Y-m-d')) }}">
        </div>
        <div class="col-md-4">
            <label>إلى تاريخ:</label>
            <input type="date" name="to" class="form-control"
                value="{{ request('to', date('Y-m-d')) }}">
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary">عرض التقرير</button>
        </div>
    </div>
</form>


    <div class="row">

        {{-- الإيرادات --}}
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">إيرادات نقدية</div>
                <div class="card-body">
                    <h5 class="card-title">{{ number_format($cash_revenue, 2) }} ₪</h5>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">إيرادات بطاقات</div>
                <div class="card-body">
                    <h5 class="card-title">{{ number_format($card_revenue, 2) }} ₪</h5>
                </div>
            </div>
        </div>

        {{-- المصروفات --}}
        <div class="col-md-3">
            <div class="card text-white bg-danger mb-3">
                <div class="card-header">مصروفات نقدية</div>
                <div class="card-body">
                    <h5 class="card-title">{{ number_format($cash_expenses, 2) }} ₪</h5>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">مصروفات بطاقات</div>
                <div class="card-body">
                    <h5 class="card-title">{{ number_format($card_expenses, 2) }} ₪</h5>
                </div>
            </div>
        </div>

        {{-- الرصيد --}}
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-header">رصيد الصندوق النقدي</div>
                <div class="card-body">
                    <h5 class="card-title">{{ number_format($cash_box, 2) }} ₪</h5>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-secondary mb-3">
                <div class="card-header">رصيد صندوق البطاقة</div>
                <div class="card-body">
                    <h5 class="card-title">{{ number_format($card_box, 2) }} ₪</h5>
                </div>
            </div>
        </div>

        {{-- الديون --}}
        <div class="col-md-3">
            <div class="card text-white bg-dark mb-3">
                <div class="card-header">ديون علينا (للموردين)</div>
                <div class="card-body">
                    <h5 class="card-title">{{ number_format($debts_to_have, 2) }} ₪</h5>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">ديون لنا (على المرضى)</div>
                <div class="card-body">
                    <h5 class="card-title">{{ number_format($debts_to_us, 2) }} ₪</h5>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
