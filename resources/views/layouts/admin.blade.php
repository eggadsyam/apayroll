<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - Sistem Payroll</title>

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
            background: #212529;
            color: #fff;
            transition: all 0.3s;
            overflow-y: auto;
        }
        #sidebar .sidebar-header {
            padding: 20px;
            background: #1a1e21;
        }
        #sidebar ul.components {
            padding: 20px 0;
        }
        #sidebar ul li a {
            padding: 10px 20px;
            font-size: 1em;
            display: block;
            color: #c2c7d0;
            text-decoration: none;
            transition: 0.2s;
        }
        #sidebar ul li a:hover {
            color: #fff;
            background: #343a40;
        }
        #sidebar ul li.active > a,
        #sidebar ul li a[aria-expanded="true"] {
            color: #fff;
            background: #0d6efd;
        }
        #sidebar ul li ul.collapse a {
            font-size: 0.9em;
            padding-left: 40px;
            background: #2c3136;
        }
        #sidebar ul li ul.collapse li.active > a {
            background: #343a40;
            color: #fff;
            font-weight: bold;
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
        <x-sidebar />

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
