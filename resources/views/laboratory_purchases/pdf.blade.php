<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>فاتورة مشتريات</title>
    <style>
        @font-face {
            font-family: 'DejaVuSans';
            src: url('{{ storage_path("fonts/DejaVuSans.ttf") }}');
        }

        body {
            font-family: 'DejaVuSans', sans-serif;
            direction: rtl;
            text-align: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 14px;
        }

        .header {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .note-box {
            border: 1px dashed #aaa;
            padding: 10px;
            margin-top: 20px;
        }

        .total-box {
            margin-top: 10px;
            font-size: 18px;
            text-align: left;
        }
    </style>
</head>
<body>

    <div class="header">فاتورة مشتريات رقم #{{ $purchase->id }}</div>

    <div>المعمل: {{ $purchase->laboratory->name ?? '-' }}</div>
    <div>المريض: {{ $purchase->patient->name ?? '-' }}</div>
    <div>تاريخ الشراء: {{ $purchase->purchase_date }}</div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>اسم الصنف</th>
                <th>رقم الأسنان</th>
                <th>السعر</th>
                <th>الكمية</th>
                <th>الإجمالي</th>
                <th>ملاحظات</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchase->items as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->item_name }}</td>
                    <td>{{ $item->number_of_teeth }}</td>
                    <td>{{ number_format($item->price, 2) }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->total, 2) }}</td>
                    <td>{{ $item->notes }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="note-box">
        <strong>ملاحظات:</strong> {{ $purchase->notes ?? 'لا توجد' }}
    </div>

    <div class="total-box">
        <strong>الإجمالي الكلي:</strong> {{ number_format($purchase->total_amount, 2) }} شيكل
    </div>

</body>
</html>
