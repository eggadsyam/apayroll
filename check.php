<?php

use App\Models\Payroll;
use App\Models\PayrollPeriod;

$periods = PayrollPeriod::latest('start_date')->take(6)->get()->reverse();
$res = [];
foreach ($periods as $p) {
    $res[] = [
        'id' => $p->id,
        'label' => $p->month.'/'.$p->year,
        'net' => Payroll::where('payroll_period_id', $p->id)->sum('net_salary'),
    ];
}
echo json_encode($res, JSON_PRETTY_PRINT);
