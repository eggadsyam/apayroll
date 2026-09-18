<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\Payroll;
use App\Models\PayrollPeriod;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return View
     */
    public function index()
    {
        $totalEmployees = Employee::active()->count();
        $presentToday = Attendance::whereDate('date', today())
            ->where(function ($query) {
                $query->where('status', 'present')
                    ->orWhere('status', 'late');
            })->count();
        $onLeave = Leave::where('status', 'approved')
            ->where('start_date', '<=', today())
            ->where('end_date', '>=', today())
            ->count();

        // Calculate absent based on total active employees minus present and on leave
        // This ensures the math adds up perfectly on the dashboard
        $absentToday = $totalEmployees - $presentToday - $onLeave;
        if ($absentToday < 0) {
            $absentToday = 0;
        }

        $currentPeriod = PayrollPeriod::latest()->first();
        $totalPayroll = $currentPeriod ? Payroll::where('payroll_period_id', $currentPeriod->id)->sum('net_salary') : 0;

        return view('dashboard.index', compact(
            'totalEmployees', 'presentToday', 'absentToday', 'onLeave',
            'currentPeriod', 'totalPayroll'
        ));
    }
}
