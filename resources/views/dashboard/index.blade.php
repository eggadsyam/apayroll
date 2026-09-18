@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800 fw-bold">Dashboard</h1>
</div>

<div class="row">
    <!-- Total Karyawan -->
    <div class="col-xl-3 col-md-6 mb-4">
        <x-stat-card 
            title="Total Karyawan" 
            value="{{ $totalEmployees }}" 
            icon="fa-users" 
            color="primary" 
        />
    </div>

    <!-- Hadir Hari Ini -->
    <div class="col-xl-3 col-md-6 mb-4">
        <x-stat-card 
            title="Hadir Hari Ini" 
            value="{{ $presentToday }}" 
            icon="fa-user-check" 
            color="success" 
        />
    </div>

    <!-- Tidak Hadir -->
    <div class="col-xl-3 col-md-6 mb-4">
        <x-stat-card 
            title="Tidak Hadir" 
            value="{{ $absentToday }}" 
            icon="fa-user-times" 
            color="danger" 
        />
    </div>

    <!-- Cuti -->
    <div class="col-xl-3 col-md-6 mb-4">
        <x-stat-card 
            title="Sedang Cuti" 
            value="{{ $onLeave }}" 
            icon="fa-calendar-minus" 
            color="warning" 
        />
    </div>
</div>

<div class="row mt-3">
    <!-- Total Payroll -->
    <div class="col-xl-6 col-md-6 mb-4">
        <x-stat-card 
            title="Total Payroll Bulan Ini" 
            value="Rp{{ number_format($totalPayroll, 0, ',', '.') }}" 
            icon="fa-money-bill-wave" 
            color="info" 
            subtitle="Periode: {{ $currentPeriod ? $currentPeriod->formatted_period : '-' }}"
        />
    </div>

    <!-- Status Payroll -->
    <div class="col-xl-6 col-md-6 mb-4">
        <x-stat-card 
            title="Status Payroll" 
            value="{{ $currentPeriod ? ucfirst($currentPeriod->status) : 'Belum Ada' }}" 
            icon="fa-clipboard-check" 
            color="secondary" 
        />
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-primary fw-bold">Tren Pengeluaran Payroll (6 Bulan Terakhir)</h6>
            </div>
            <div class="card-body">
                <canvas id="payrollChart" height="100"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-primary fw-bold">Distribusi Kehadiran Hari Ini</h6>
            </div>
            <div class="card-body">
                <canvas id="attendanceChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

@php
    // Fetch real data for charts directly here to avoid touching DashboardController
    $last6Periods = \App\Models\PayrollPeriod::latest('start_date')->take(6)->get()->reverse();
    $labels = $last6Periods->map(fn($p) => $p->month . '/' . $p->year)->values()->toArray();
    $dataPayroll = $last6Periods->map(fn($p) => \App\Models\Payroll::where('payroll_period_id', $p->id)->sum('net_salary'))->values()->toArray();
    
    $attendanceLabels = ['Hadir', 'Tidak Hadir', 'Cuti'];
    $attendanceData = [$presentToday, $absentToday, $onLeave];
@endphp

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Payroll Line Chart
    new Chart(document.getElementById('payrollChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                label: 'Total Net Salary (Rp)',
                data: {!! json_encode($dataPayroll) !!},
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: false } }
        }
    });

    // Attendance Doughnut Chart
    new Chart(document.getElementById('attendanceChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($attendanceLabels) !!},
            datasets: [{
                data: {!! json_encode($attendanceData) !!},
                backgroundColor: ['#1cc88a', '#e74a3b', '#f6c23e']
            }]
        },
        options: { responsive: true }
    });
});
</script>
</div>
@endsection
