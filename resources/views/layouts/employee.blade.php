<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Portal Karyawan') - Sistem Payroll</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fa;
            overflow-x: hidden;
        }
        #sidebar {
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 1000;
            background: #1a3a5c; /* Dark Blue Theme */
            color: #fff;
            transition: all 0.3s;
            overflow-y: auto;
        }
        #sidebar .sidebar-header {
            padding: 20px;
            background: #122b46;
        }
        #sidebar ul.components {
            padding: 20px 0;
        }
        #sidebar ul li a {
            padding: 12px 20px;
            font-size: 1.05em;
            display: block;
            color: #d1e0f0;
            text-decoration: none;
            transition: 0.2s;
        }
        #sidebar ul li a:hover {
            color: #fff;
            background: #234c7a;
        }
        #sidebar ul li.active > a {
            color: #fff;
            background: #0d6efd;
            border-left: 4px solid #fff;
        }
        
        #content {
            width: calc(100% - 250px);
            margin-left: 250px;
            min-height: 100vh;
            transition: all 0.3s;
        }
        
        @media (max-width: 768px) {
            #sidebar {
                margin-left: -250px;
            }
            #sidebar.active {
                margin-left: 0;
            }
            #content {
                width: 100%;
                margin-left: 0;
            }
            #content.active {
                margin-left: 250px;
                width: calc(100% - 250px);
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="wrapper d-flex">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header d-flex align-items-center justify-content-center">
                <h3 class="m-0 fs-5 fw-bold"><i class="fas fa-coins me-2"></i>Portal Karyawan</h3>
            </div>

            <ul class="list-unstyled components">
                <li class="{{ request()->routeIs('portal.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('portal.dashboard') ?? '#' }}">
                        <i class="fas fa-chart-line me-2 fa-fw"></i> Dashboard
                    </a>
                </li>
                <li class="{{ request()->routeIs('portal.profile') ? 'active' : '' }}">
                    <a href="{{ route('portal.profile') ?? '#' }}">
                        <i class="fas fa-user me-2 fa-fw"></i> Profil Saya
                    </a>
                </li>
                <li class="{{ request()->routeIs('portal.attendance') ? 'active' : '' }}">
                    <a href="{{ route('portal.attendance') ?? '#' }}">
                        <i class="fas fa-clipboard-check me-2 fa-fw"></i> Absensi Saya
                    </a>
                </li>
                <li class="{{ request()->routeIs('portal.leave') ? 'active' : '' }}">
                    <a href="{{ route('portal.leave') ?? '#' }}">
                        <i class="fas fa-umbrella-beach me-2 fa-fw"></i> Cuti Saya
                    </a>
                </li>
                <li class="{{ request()->routeIs('portal.overtime') ? 'active' : '' }}">
                    <a href="{{ route('portal.overtime') ?? '#' }}">
                        <i class="fas fa-clock me-2 fa-fw"></i> Lembur Saya
                    </a>
                </li>
                <li class="{{ request()->routeIs('portal.payslip') ? 'active' : '' }}">
                    <a href="{{ route('portal.payslip') ?? '#' }}">
                        <i class="fas fa-file-invoice-dollar me-2 fa-fw"></i> Slip Gaji
                    </a>
                </li>
                <li class="{{ request()->routeIs('portal.cooperative-loans.*') ? 'active' : '' }}">
                    <a href="{{ route('portal.cooperative-loans.index') ?? '#' }}">
                        <i class="fas fa-hand-holding-usd me-2 fa-fw"></i> Pengajuan Koperasi
                    </a>
                </li>
                

            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <!-- Navbar -->
            <x-navbar />

            <div class="container-fluid p-4">
                <x-alert />
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('sidebarCollapse').addEventListener('click', function () {
                document.getElementById('sidebar').classList.toggle('active');
                document.getElementById('content').classList.toggle('active');
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
