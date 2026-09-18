<nav id="sidebar">
    <div class="sidebar-header d-flex align-items-center justify-content-center">
        <h3 class="m-0 fs-5 fw-bold"><i class="fas fa-coins me-2"></i>Sistem Payroll</h3>
    </div>

    <ul class="list-unstyled components">
        @hasanyrole('super_admin|hrd|finance|manager')
        <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') ?? '#' }}">
                <i class="fas fa-chart-line me-2 fa-fw"></i> Dashboard Admin
            </a>
        </li>
        @endhasanyrole

        @can('employee.view')
        <li class="{{ request()->routeIs('employees.*', 'departments.*', 'shifts.*', 'positions.*', 'employment-statuses.*') ? 'active' : '' }}">
            <a href="#employeeSubmenu" data-bs-toggle="collapse" aria-expanded="{{ request()->routeIs('employees.*', 'departments.*', 'shifts.*', 'positions.*', 'employment-statuses.*') ? 'true' : 'false' }}" class="dropdown-toggle d-flex justify-content-between align-items-center">
                <span><i class="fas fa-users me-2 fa-fw"></i> Karyawan</span>
            </a>
            <ul class="collapse list-unstyled {{ request()->routeIs('employees.*', 'departments.*', 'shifts.*', 'positions.*', 'employment-statuses.*') ? 'show' : '' }}" id="employeeSubmenu">
                <li class="{{ request()->routeIs('employees.index') ? 'active' : '' }}">
                    <a href="{{ route('employees.index') ?? '#' }}">Daftar Karyawan</a>
                </li>
                <li class="{{ request()->routeIs('departments.index') ? 'active' : '' }}">
                    <a href="{{ route('departments.index') ?? '#' }}">Departemen</a>
                </li>
                <li class="{{ request()->routeIs('shifts.index') ? 'active' : '' }}">
                    <a href="{{ route('shifts.index') ?? '#' }}">Shift</a>
                </li>
                <li class="{{ request()->routeIs('positions.index') ? 'active' : '' }}">
                    <a href="{{ route('positions.index') ?? '#' }}">Jabatan</a>
                </li>
                <li class="{{ request()->routeIs('employment-statuses.index') ? 'active' : '' }}">
                    <a href="{{ route('employment-statuses.index') ?? '#' }}">Status Karyawan</a>
                </li>
            </ul>
        </li>
        @endcan

        @can('attendance.view')
        <li class="{{ request()->routeIs('attendances.*', 'overtimes.*', 'leaves.*') ? 'active' : '' }}">
            <a href="#attendanceSubmenu" data-bs-toggle="collapse" aria-expanded="{{ request()->routeIs('attendances.*', 'overtimes.*', 'leaves.*') ? 'true' : 'false' }}" class="dropdown-toggle d-flex justify-content-between align-items-center">
                <span><i class="fas fa-clipboard-list me-2 fa-fw"></i> Absensi</span>
            </a>
            <ul class="collapse list-unstyled {{ request()->routeIs('attendances.*', 'overtimes.*', 'leaves.*') ? 'show' : '' }}" id="attendanceSubmenu">
                <li class="{{ request()->routeIs('attendances.index') ? 'active' : '' }}">
                    <a href="{{ route('attendances.index') ?? '#' }}">Data Absensi</a>
                </li>
                <li class="{{ request()->routeIs('attendances.import') ? 'active' : '' }}">
                    <a href="{{ route('attendances.import') ?? '#' }}">Import Absensi</a>
                </li>
                <li class="{{ request()->routeIs('overtimes.index') ? 'active' : '' }}">
                    <a href="{{ route('overtimes.index') ?? '#' }}">Lembur</a>
                </li>
                <li class="{{ request()->routeIs('leaves.index') ? 'active' : '' }}">
                    <a href="{{ route('leaves.index') ?? '#' }}">Cuti & Izin</a>
                </li>
            </ul>
        </li>
        @endcan

        @can('payroll.view')
        <li class="{{ request()->routeIs('payroll-periods.*', 'salary-components.*', 'employee-salaries.*', 'payrolls.*', 'payslips.*') ? 'active' : '' }}">
            <a href="#payrollSubmenu" data-bs-toggle="collapse" aria-expanded="{{ request()->routeIs('payroll-periods.*', 'salary-components.*', 'employee-salaries.*', 'payrolls.*', 'payslips.*') ? 'true' : 'false' }}" class="dropdown-toggle d-flex justify-content-between align-items-center">
                <span><i class="fas fa-money-check-alt me-2 fa-fw"></i> Penggajian</span>
            </a>
            <ul class="collapse list-unstyled {{ request()->routeIs('payroll-periods.*', 'salary-components.*', 'employee-salaries.*', 'payrolls.*', 'payslips.*') ? 'show' : '' }}" id="payrollSubmenu">
                <li class="{{ request()->routeIs('payroll-periods.index') ? 'active' : '' }}">
                    <a href="{{ route('payroll-periods.index') ?? '#' }}">Periode Payroll</a>
                </li>
                <li class="{{ request()->routeIs('salary-components.index') ? 'active' : '' }}">
                    <a href="{{ route('salary-components.index') ?? '#' }}">Komponen Gaji</a>
                </li>
                <li class="{{ request()->routeIs('employee-salaries.index') ? 'active' : '' }}">
                    <a href="{{ route('employee-salaries.index') ?? '#' }}">Gaji Karyawan</a>
                </li>
                <li class="{{ request()->routeIs('payrolls.index') ? 'active' : '' }}">
                    <a href="{{ route('payrolls.index') ?? '#' }}">Proses Payroll</a>
                </li>
                <li class="{{ request()->routeIs('payslips.index') ? 'active' : '' }}">
                    <a href="{{ route('payslips.index') ?? '#' }}">Slip Gaji</a>
                </li>
            </ul>
        </li>
        @endcan

        @can('loan.view')
        <li class="{{ request()->routeIs('employee-loans.*') ? 'active' : '' }}">
            <a href="#loanSubmenu" data-bs-toggle="collapse" aria-expanded="{{ request()->routeIs('employee-loans.*') ? 'true' : 'false' }}" class="dropdown-toggle d-flex justify-content-between align-items-center">
                <span><i class="fas fa-credit-card me-2 fa-fw"></i> Keuangan</span>
            </a>
            <ul class="collapse list-unstyled {{ request()->routeIs('employee-loans.*') ? 'show' : '' }}" id="loanSubmenu">
                <li class="{{ request()->routeIs('employee-loans.index') ? 'active' : '' }}">
                    <a href="{{ route('employee-loans.index') ?? '#' }}">Pinjaman Karyawan</a>
                </li>
            </ul>
        </li>
        @endcan

        @can('report.view')
        <li class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <a href="#reportSubmenu" data-bs-toggle="collapse" aria-expanded="{{ request()->routeIs('reports.*') ? 'true' : 'false' }}" class="dropdown-toggle d-flex justify-content-between align-items-center">
                <span><i class="fas fa-file-alt me-2 fa-fw"></i> Laporan</span>
            </a>
            <ul class="collapse list-unstyled {{ request()->routeIs('reports.*') ? 'show' : '' }}" id="reportSubmenu">
                <li class="{{ request()->routeIs('reports.payroll-summary') ? 'active' : '' }}">
                    <a href="{{ route('reports.payroll-summary') ?? '#' }}">Laporan Payroll</a>
                </li>
                <li class="{{ request()->routeIs('reports.attendance') ? 'active' : '' }}">
                    <a href="{{ route('reports.attendance') ?? '#' }}">Laporan Absensi</a>
                </li>
                <li class="{{ request()->routeIs('reports.overtime') ? 'active' : '' }}">
                    <a href="{{ route('reports.overtime') ?? '#' }}">Laporan Lembur</a>
                </li>
                <li class="{{ request()->routeIs('reports.employee') ? 'active' : '' }}">
                    <a href="{{ route('reports.employee') ?? '#' }}">Laporan Karyawan</a>
                </li>
            </ul>
        </li>
        @endcan

        @can('setting.view')
        <li class="{{ request()->routeIs('settings.*', 'users.*', 'roles.*') ? 'active' : '' }}">
            <a href="#settingSubmenu" data-bs-toggle="collapse" aria-expanded="{{ request()->routeIs('settings.*', 'users.*', 'roles.*') ? 'true' : 'false' }}" class="dropdown-toggle d-flex justify-content-between align-items-center">
                <span><i class="fas fa-cog me-2 fa-fw"></i> Pengaturan</span>
            </a>
            <ul class="collapse list-unstyled {{ request()->routeIs('settings.*', 'users.*', 'roles.*') ? 'show' : '' }}" id="settingSubmenu">
                <li class="{{ request()->routeIs('settings.company') ? 'active' : '' }}">
                    <a href="{{ route('settings.company') ?? '#' }}">Perusahaan</a>
                </li>
                <li class="{{ request()->routeIs('settings.payroll') ? 'active' : '' }}">
                    <a href="{{ route('settings.payroll') ?? '#' }}">Pengaturan Payroll</a>
                </li>
                <li class="{{ request()->routeIs('users.index') ? 'active' : '' }}">
                    <a href="{{ route('users.index') ?? '#' }}">Pengguna</a>
                </li>
                <li class="{{ request()->routeIs('roles.index') ? 'active' : '' }}">
                    <a href="{{ route('roles.index') ?? '#' }}">Peran & Hak Akses</a>
                </li>
            </ul>
        </li>
        @endcan
    </ul>
</nav>
