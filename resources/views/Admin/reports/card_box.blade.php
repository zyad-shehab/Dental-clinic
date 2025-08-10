@extends('admin.layouts.app')

@section('title', 'تفاصيل الصندوق البنكي')

@section('content')
<div class="container mt-4">
    <h4 class="mb-4">تفاصيل الصندوق البنكي من {{ $from }} إلى {{ $to }}</h4>

    {{-- نموذج اختيار التاريخ --}}
    <form method="GET" action="{{ route('Report.getCardBox') }}" class="mb-4">
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

    

    {{-- دفعات الجلسات (بنكي) --}}
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">دفعات الجلسات (بنكيًا)</div>
        <div class="card-body p-0">
            <table class="table table-sm table-bordered m-0">
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>اسم المريض</th>
                        <th>المبلغ</th>
                        <th>ملاحظة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($session_card_details as $item)
                        <tr>
                            <td>{{ $item->session_date }}</td>
                            <td>{{ $item->patient->name ?? 'غير معروف' }}</td>
                            <td>{{ number_format($item->card_payment, 2) }} ₪</td>
                            <td>{{ $item->notes ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- دفعات المرضى (بنكي) --}}
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">دفعات المرضى (بنكيًا)</div>
        <div class="card-body p-0">
            <table class="table table-sm table-bordered m-0">
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>اسم المريض</th>
                        <th>المبلغ</th>
                        <th>ملاحظة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patient_card_details as $item)
                        <tr>
                            <td>{{ $item->patient->name ?? 'غير معروف' }}</td>
                            <td>{{ number_format($item->paid_card, 2) }} ₪</td>
                            <td>{{ $item->payment_date }}</td>
                            <td>{{ $item->note ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- مصروفات (بنكي) --}}
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">المصروفات الإدارية (بنكيًا)</div>
        <div class="card-body p-0">
            <table class="table table-sm table-bordered m-0">
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>مدفوع الي</th>
                        <th>المبلغ</th>
                        <th>ملاحظة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($expenses_card_details as $item)
                        <tr>
                            <td>{{ $item->date }}</td>
                            <td>{{ $item->pay_to ?? 'غير معروف' }}</td>
                            <td>{{ number_format($item->paid_card, 2) }} ₪</td>
                            <td>{{ $item->note ?? '-' }}</td>
                            </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- دفعات المختبر (بنكي) --}}
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">دفعات المختبر (بنكيًا)</div>
        <div class="card-body p-0">
            <table class="table table-sm table-bordered m-0">
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>المختبر</th>
                        <th>المبلغ</th>
                        <th>ملاحظة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lab_card_details as $item)
                        <tr>
                            <td>{{ $item->payment_date }}</td>
                            <td>{{ $item->laboratory->name ?? 'غير معروف' }}</td>
                            <td>{{ number_format($item->paid_card, 2) }} ₪</td>
                            <td>{{ $item->note ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- دفعات المستودع (بنكي) --}}
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">دفعات المستودع (بنكيًا)</div>
        <div class="card-body p-0">
            <table class="table table-sm table-bordered m-0">
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>المستودع</th>
                        <th>المبلغ</th>
                        <th>ملاحظة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($warehouse_card_details as $item)
                        <tr>
                            <td>{{ $item->payment_date }}</td>
                            <td>{{ $item->storehouse->name ?? 'غير معروف' }}</td>
                            <td>{{ number_format($item->paid_card, 2) }} ₪</td>
                            <td>{{ $item->note ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
    </div>
{{-- ملخص الرصيد --}}
    <div class="alert alert-info">
        {{-- <h5> الرصيد البنكي:</h5> --}}
        <p>
            <strong>الرصيد البنكي :( {{ number_format($card_balance, 2) }} )</strong>
        </p>
    </div>
</div>
@endsection
