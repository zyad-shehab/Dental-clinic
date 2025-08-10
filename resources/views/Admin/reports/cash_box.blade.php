@extends('admin.layouts.app')

@section('title', 'تفاصيل الصندوق النقدي')

@section('content')
<div class="container mt-4">
    <h4 class="mb-4">تفاصيل الصندوق النقدي من {{ $from }} إلى {{ $to }}</h4>

    {{-- نموذج اختيار التاريخ --}}
    <form method="GET" action="{{ route('Report.getCashBox') }}" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <label>من تاريخ:</label>
                <input type="date" name="from" class="form-control" value="{{ $from }}">
            </div>
            <div class="col-md-4">
                <label>إلى تاريخ:</label>
                <input type="date" name="to" class="form-control" value="{{ $to }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">عرض التفاصيل</button>
            </div>
        </div>
    </form>

    {{-- دفعات الجلسات النقدية --}}
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">دفعات الجلسات (نقداً)</div>
        <div class="card-body p-0">
            <table class="table table-sm table-bordered m-0">
                <thead><tr><th>التاريخ</th><th>اسم المريض</th><th>المبلغ</th><th>ملاحظة</th></tr></thead>
                <tbody>
                    @foreach($session_cash_payments_details as $item)
                        <tr>
                            <td>{{ $item->session_date }}</td>
                            <td>{{ $item->patient->name ?? 'غير معروف' }}</td>
                            <td>{{ number_format($item->cash_payment, 2) }} </td>
                            <td>{{ $item->notes ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- دفعات المرضى (نقداً) --}}
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">دفعات المرضى (نقداً)</div>
        <div class="card-body p-0">
            <table class="table table-sm table-bordered m-0">
                <thead><tr><th>التاريخ</th><th>اسم المريض</th><th>المبلغ</th><th>ملاحظة</th></tr></thead>
                <tbody>
                    @foreach($patient_cash_payments_details as $item)
                        <tr>
                            <td>{{ $item->payment_date }}</td>
                            <td>{{ $item->patient->name ?? 'غير معروف' }}</td>
                            <td>{{ number_format($item->paid_cash, 2) }} </td>
                            <td>{{ $item->note ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- المصروفات --}}
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">المصروفات (نقداً)</div>
        <div class="card-body p-0">
            <table class="table table-sm table-bordered m-0">
                <thead><tr><th>التاريخ</th><th>مدفوع الي</th><th>المبلغ</th><th>ملاحظة</th></tr></thead>
                <tbody>
                    @foreach($expenses_cash_details as $item)
                        <tr>
                            <td>{{ $item->date }}</td>
                            <td>{{ $item->pay_to ?? 'غير معروف' }}</td>
                            <td>{{ number_format($item->paid_cash, 2) }} </td>
                            <td>{{ $item->note ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- دفعات المختبر --}}
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">دفعات المختبر (نقداً)</div>
        <div class="card-body p-0">
            <table class="table table-sm table-bordered m-0">
                <thead><tr><th>التاريخ</th><th>المعمل</th><th>المبلغ</th><th>ملاحظة</th></tr></thead>
                <tbody>
                    @foreach($lab_cash_details as $item)
                        <tr>
                            <td>{{ $item->payment_date }}</td>
                            <td>{{ $item->laboratory->name ?? 'غير معروف' }}</td>
                            <td>{{ number_format($item->paid_cash, 2) }} </td>
                            <td>{{ $item->note ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- دفعات المستودع --}}
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">دفعات المستودع (نقداً)</div>
        <div class="card-body p-0">
            <table class="table table-sm table-bordered m-0">
                <thead><tr><th>التاريخ</th><th>المستودع</th><th>المبلغ</th><th>ملاحظة</th></tr></thead>
                <tbody>
                    @foreach($warehouse_cash_details as $item)
                        <tr>
                            <td>{{ $item->payment_date }}</td>
                            <td>{{ $item->storehouse->name ?? 'غير معروف' }}</td>
                            <td>{{ number_format($item->paid_cash, 2) }}</td>
                            <td>{{ $item->note ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="alert alert-info">
    {{-- <h5> الرصيد الإجمالي للصندوق النقدي:</h5> --}}
    <p>
        
        <strong>الرصيد : ( {{ number_format($cash_balance, 2) }} )</strong>
    </p>
</div>

</div>
@endsection
