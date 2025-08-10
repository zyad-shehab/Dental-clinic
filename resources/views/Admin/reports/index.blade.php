@extends('admin.layouts.app') {{-- تأكد من وجود ملف layout المناسب --}}

@section('title', 'التقرير المالي')

@section('content')
<div class="container mt-4">
    <h4 class="mb-4">التقرير المالي من {{ request('from', date('Y-m-d')) }} إلى {{ request('to', date('Y-m-d')) }}</h4>

    {{-- نموذج اختيار التاريخ --}}
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

    {{-- جدول التقرير المالي --}}
    <div class="card shadow">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">ملخص التقرير المالي</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered m-0">
                <thead class="thead-light">
                    <tr>
                        <th>البند</th>
                        <th>المبلغ</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>إيرادات نقدية</td>
                        <td>{{ number_format($cash_revenue, 2) }}</td>
                    </tr>
                    <tr>
                        <td>إيرادات بنكي</td>
                        <td>{{ number_format($card_revenue, 2) }}</td>
                    </tr>
                    <tr class="table-success">
                        <td><strong>إجمالي الإيرادات</strong></td>
                        <td><strong>{{ number_format($cash_revenue + $card_revenue, 2) }}</strong></td>
                    </tr>
                    <tr>
                        <td>مصروفات نقدية</td>
                        <td>{{ number_format($cash_expenses, 2) }}</td>
                    </tr>
                    <tr>
                        <td>مصروفات بنكي</td>
                        <td>{{ number_format($card_expenses, 2) }}</td>
                    </tr>
                    <tr class="table-danger">
                        <td><strong>إجمالي المصروفات</strong></td>
                        <td><strong>{{ number_format($cash_expenses + $card_expenses, 2) }}</strong></td>
                    </tr>
                    <tr class="table-success">
                     <tr>
                        <td>ديون لنا (على المرضى)</td>
                        <td>{{ number_format($debts_to_us, 2) }}</td>
                    </tr>
                    <tr>
                        <td>ديون علينا (للموردين)</td>
                        <td>{{ number_format($debts_to_have, 2) }}</td>
                    </tr>
                    <tr class="table-info">
                        <td> الصندوق النقدي</td>
                        <td>{{ number_format($cash_box, 2) }}</td>
                    </tr>
                    <tr class="table-info">
                        <td>رصيد البنك</td>
                        <td>{{ number_format($card_box, 2) }}</td>
                    </tr>
                   
    {{-- <td><strong>الربح من النقدي</strong></td>
    <td><strong>{{ number_format($cash_revenue - $cash_expenses, 2) }} ₪</strong></td>
</tr>
<tr class="table-success">
    <td><strong>الربح من البطاقة</strong></td>
    <td><strong>{{ number_format($card_revenue - $card_expenses, 2) }} ₪</strong></td>
</tr> --}}
<tr class="table-primary">
    <td><strong>إجمالي الربح مع تحصيل الديون</strong></td>
    <td><strong>{{number_format($true_total_profit,2) }}</strong></td>
</tr>

                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
