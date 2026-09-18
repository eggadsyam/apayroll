<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Slip Gaji - {{ $payroll->employee->name }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; line-height: 1.4; color: #333; }
        table { width: 100%; border-collapse: collapse; }
        .header { border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .company-name { font-size: 18px; font-weight: bold; margin: 0; }
        .title { text-align: right; font-size: 16px; font-weight: bold; }
        .info-table { margin-bottom: 20px; }
        .info-table td { padding: 3px 0; }
        .box { border: 1px solid #ddd; padding: 10px; background: #f9f9f9; }
        .box-title { font-weight: bold; border-bottom: 1px solid #ddd; padding-bottom: 5px; margin-bottom: 10px; }
        .detail-table td { padding: 3px 0; }
        .text-right { text-align: right; }
        .total-row { font-weight: bold; border-top: 1px solid #ddd; margin-top: 5px; padding-top: 5px; }
        .thp-box { background: #eef; border: 1px solid #ccd; padding: 10px; text-align: center; margin-top: 20px; font-size: 14px; }
        .signature { margin-top: 40px; width: 100%; }
        .sig-col { text-align: center; }
        .sig-space { height: 80px; }
    </style>
</head>
<body>
    @php
    $logoBase64 = null;
    if (!empty($company->logo)) {
        $path = storage_path('app/public/' . $company->logo);
        if (file_exists($path)) {
            $logoBase64 = 'data:image/' . pathinfo($path, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($path));
        }
    }
    @endphp
    
    <div class="header">
        <table style="border: none;">
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    @if($logoBase64)
                        <img src="{{ $logoBase64 }}" style="max-height: 50px; margin-bottom: 5px;">
                    @endif
                    <h2 class="company-name">{{ $company->company_name ?? 'Nama Perusahaan' }}</h2>
                    <div style="font-size: 11px; margin-top: 5px;">{{ $company->address ?? '-' }}</div>
                </td>
                <td style="width: 50%;" class="text-right">
                    <div class="title">SLIP GAJI</div>
                    <div>Periode: {{ $payroll->payrollPeriod->name }}</div>
                </td>
            </tr>
        </table>
    </div>

    <table class="info-table">
        <tr>
            <td width="15%"><strong>NIK</strong></td><td width="35%">: {{ $payroll->employee->nik }}</td>
            <td width="15%"><strong>Departemen</strong></td><td width="35%">: {{ $payroll->employee->department->name ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Nama</strong></td><td>: {{ $payroll->employee->name }}</td>
            <td><strong>Jabatan</strong></td><td>: {{ $payroll->employee->position->name ?? '-' }}</td>
        </tr>
    </table>

    <table style="border: none;">
        <tr>
            <td style="width: 48%; vertical-align: top;" class="box">
                <div class="box-title">PENGHASILAN</div>
                <table class="detail-table">
                    @foreach($payroll->details->where('component_type', 'earning') as $d)
                    <tr><td>{{ $d->component_name }}</td><td class="text-right">Rp{{ number_format($d->amount, 0, ',', '.') }}</td></tr>
                    @endforeach
                </table>
                <div class="total-row">
                    <table style="border:none;"><tr><td>Total Penghasilan</td><td class="text-right">Rp{{ number_format($payroll->gross_salary, 0, ',', '.') }}</td></tr></table>
                </div>
            </td>
            <td style="width: 4%;"></td>
            <td style="width: 48%; vertical-align: top;" class="box">
                <div class="box-title">POTONGAN</div>
                <table class="detail-table">
                    @foreach($payroll->details->where('component_type', 'deduction') as $d)
                    <tr><td>{{ $d->component_name }}</td><td class="text-right">Rp{{ number_format($d->amount, 0, ',', '.') }}</td></tr>
                    @endforeach
                </table>
                <div class="total-row">
                    <table style="border:none;"><tr><td>Total Potongan</td><td class="text-right">Rp{{ number_format($payroll->total_deduction, 0, ',', '.') }}</td></tr></table>
                </div>
            </td>
        </tr>
    </table>

    <div class="thp-box">
        <strong>TAKE HOME PAY : Rp{{ number_format($payroll->net_salary, 0, ',', '.') }}</strong>
    </div>

    <table class="signature">
        <tr>
            <td class="sig-col" style="width: 50%;">
                <div>Penerima,</div>
                <div class="sig-space"></div>
                <div><strong>{{ $payroll->employee->name }}</strong></div>
            </td>
            <td class="sig-col" style="width: 50%;">
                <div>Mengetahui,</div>
                <div class="sig-space"></div>
                <div><strong>HR / Finance</strong></div>
            </td>
        </tr>
    </table>
</body>
</html>
