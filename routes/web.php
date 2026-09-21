<?php

use App\Http\Controllers\Admin\CooperativeLoanRequestController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CooperativeRecordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeePortalController;
use App\Http\Controllers\EmployeeSalaryController;
use App\Http\Controllers\EmploymentStatusController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PayrollPeriodController;
use App\Http\Controllers\PayslipController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalaryComponentController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// ============================================================
// Common Authenticated Routes (All Users)
// ============================================================
Route::middleware(['auth', 'verified'])->group(function () {
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
});

// ============================================================
// Admin Routes (Auth Required)
// ============================================================
Route::middleware(['auth', 'verified', 'role:super_admin|hrd|finance|manager|supervisor'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile (Breeze default)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --------------------------------------------------------
    // Master Data - Karyawan
    // --------------------------------------------------------
    Route::resource('departments', DepartmentController::class);
    Route::resource('shifts', ShiftController::class);
    Route::resource('positions', PositionController::class);
    Route::resource('employment-statuses', EmploymentStatusController::class);
    Route::get('employees/import', [EmployeeController::class, 'importForm'])->name('employees.import');
    Route::post('employees/import', [EmployeeController::class, 'import'])->name('employees.import.process');
    Route::get('employees/template', [EmployeeController::class, 'template'])->name('employees.template');
    Route::resource('employees', EmployeeController::class);

    // --------------------------------------------------------
    // Komponen Gaji
    // --------------------------------------------------------
    Route::resource('salary-components', SalaryComponentController::class);

    // Gaji Karyawan
    Route::get('employee-salaries', [EmployeeSalaryController::class, 'index'])->name('employee-salaries.index');
    Route::get('employee-salaries/{employee}', [EmployeeSalaryController::class, 'show'])->name('employee-salaries.show');
    Route::post('employee-salaries/{employee}', [EmployeeSalaryController::class, 'store'])->name('employee-salaries.store');
    Route::delete('employee-salaries/{employeeSalaryComponent}', [EmployeeSalaryController::class, 'destroy'])->name('employee-salaries.destroy');

    // --------------------------------------------------------
    // Absensi
    // --------------------------------------------------------
    Route::get('attendances/import', [AttendanceController::class, 'importForm'])->name('attendances.import');
    Route::post('attendances/import', [AttendanceController::class, 'import'])->name('attendances.import.process');
    Route::get('attendances/template', [AttendanceController::class, 'template'])->name('attendances.template');
    Route::resource('attendances', AttendanceController::class);

    // --------------------------------------------------------
    // Lembur
    // --------------------------------------------------------
    Route::post('overtimes/{overtime}/approve', [OvertimeController::class, 'approve'])->name('overtimes.approve');
    Route::post('overtimes/{overtime}/reject', [OvertimeController::class, 'reject'])->name('overtimes.reject');
    Route::resource('overtimes', OvertimeController::class);

    // --------------------------------------------------------
    // Cuti & Izin
    // --------------------------------------------------------
    Route::resource('leave-types', LeaveTypeController::class);
    Route::post('leaves/{leaf}/approve', [LeaveController::class, 'approve'])->name('leaves.approve');
    Route::post('leaves/{leaf}/reject', [LeaveController::class, 'reject'])->name('leaves.reject');
    Route::resource('leaves', LeaveController::class);

    // --------------------------------------------------------
    // Pencatatan Koperasi & Pengajuan
    // --------------------------------------------------------
    Route::resource('cooperative-records', CooperativeRecordController::class);
    Route::get('cooperative-loan-requests', [CooperativeLoanRequestController::class, 'index'])->name('cooperative-loan-requests.index');
    Route::post('cooperative-loan-requests/{loanRequest}/approve', [CooperativeLoanRequestController::class, 'approve'])->name('cooperative-loan-requests.approve');
    Route::post('cooperative-loan-requests/{loanRequest}/reject', [CooperativeLoanRequestController::class, 'reject'])->name('cooperative-loan-requests.reject');

    // --------------------------------------------------------
    // Payroll
    // --------------------------------------------------------
    Route::resource('payroll-periods', PayrollPeriodController::class);

    // Payroll Processing Actions
    Route::post('payroll-periods/{period}/generate', [PayrollController::class, 'generate'])->name('payrolls.generate');
    Route::post('payroll-periods/{period}/submit', [PayrollController::class, 'submit'])->name('payrolls.submit');
    Route::post('payroll-periods/{period}/approve', [PayrollController::class, 'approve'])->name('payrolls.approve');
    Route::post('payroll-periods/{period}/reject', [PayrollController::class, 'reject'])->name('payrolls.reject');
    Route::post('payroll-periods/{period}/pay', [PayrollController::class, 'pay'])->name('payrolls.pay');

    // Payroll List & Detail
    Route::get('payrolls', [PayrollController::class, 'index'])->name('payrolls.index');
    Route::get('payrolls/{payroll}', [PayrollController::class, 'show'])->name('payrolls.show');

    // --------------------------------------------------------
    // Slip Gaji
    // --------------------------------------------------------
    Route::get('payslips', [PayslipController::class, 'index'])->name('payslips.index');
    Route::get('payslips/{payroll}', [PayslipController::class, 'show'])->name('payslips.show');
    Route::get('payslips/{payroll}/pdf', [PayslipController::class, 'downloadPdf'])->name('payslips.pdf');
    Route::get('payslips/bulk/{period}/pdf', [PayslipController::class, 'downloadBulkPdf'])->name('payslips.bulk-pdf');

    // --------------------------------------------------------
    // Laporan
    // --------------------------------------------------------
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('payroll-summary', [ReportController::class, 'payrollSummary'])->name('payroll-summary');
        Route::get('payroll-detail', [ReportController::class, 'payrollDetail'])->name('payroll-detail');
        Route::get('attendance', [ReportController::class, 'attendance'])->name('attendance');
        Route::get('overtime', [ReportController::class, 'overtime'])->name('overtime');
        Route::get('employee', [ReportController::class, 'employee'])->name('employee');
    });
    Route::get('reports/{type}/export', [ReportController::class, 'export'])->name('reports.export');

    // --------------------------------------------------------
    // Pengaturan
    // --------------------------------------------------------
    Route::get('settings/company', [SettingController::class, 'company'])->name('settings.company');
    Route::put('settings/company', [SettingController::class, 'updateCompany'])->name('settings.company.update');
    Route::get('settings/payroll', [SettingController::class, 'payroll'])->name('settings.payroll');
    Route::put('settings/payroll', [SettingController::class, 'updatePayroll'])->name('settings.payroll.update');

    // User Management
    Route::resource('users', UserController::class);

    // Role Management
    Route::resource('roles', RoleController::class);
});

// ============================================================
// Employee Portal Routes
// ============================================================
Route::middleware(['auth'])->prefix('portal')->name('portal.')->group(function () {
    Route::get('/', [EmployeePortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [EmployeePortalController::class, 'profile'])->name('profile');
    Route::get('/attendance', [EmployeePortalController::class, 'attendance'])->name('attendance');
    Route::get('/leave', [EmployeePortalController::class, 'leave'])->name('leave');
    Route::post('/leave', [EmployeePortalController::class, 'storeLeave'])->name('leave.store');
    Route::get('/overtime', [EmployeePortalController::class, 'overtime'])->name('overtime');
    Route::get('/payslip', [EmployeePortalController::class, 'payslip'])->name('payslip');
    Route::get('/payslip/{payroll}/pdf', [EmployeePortalController::class, 'downloadPayslip'])->name('payslip.pdf');
    Route::get('/notifications', [NotificationController::class, 'portalIndex'])->name('notifications.index');

    // Portal Cooperative Loans
    Route::get('/cooperative-loans', [App\Http\Controllers\Portal\CooperativeLoanRequestController::class, 'index'])->name('cooperative-loans.index');
    Route::post('/cooperative-loans', [App\Http\Controllers\Portal\CooperativeLoanRequestController::class, 'store'])->name('cooperative-loans.store');
});

require __DIR__.'/auth.php';
