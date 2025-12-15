<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel - BKK SMKN 1 Purwosari')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <style>
        :root {
            --sidebar-width: 260px;
        }
        
        body {
            background-color: #f8f9fa;
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #1e3a8a 0%, #1e40af 100%);
            color: white;
            overflow-y: auto;
            z-index: 1000;
            transition: transform 0.3s;
        }
        
        .sidebar-brand {
            padding: 1.5rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-nav {
            padding: 1rem 0;
        }
        
        .sidebar-nav .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.75rem 1.5rem;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        
        .sidebar-nav .nav-link:hover {
            color: white;
            background: rgba(255,255,255,0.1);
            border-left-color: white;
        }
        
        .sidebar-nav .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.2);
            border-left-color: white;
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }
        
        .topbar {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 1rem 1.5rem;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
        }
        
        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .stat-card {
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
    </style>
    <script src="https://cdn.tiny.cloud/1/p2yrnsfex3ry06c2qtwem35ntso2hnvij8k49w50yxywl851/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-shield-check me-2"></i>
                Admin Panel
            </h5>
            <small class="text-white-50">BKK SMKN 1 Purwosari</small>
        </div>
        
        <nav class="sidebar-nav">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </a>
                </li>
                
                <li class="nav-item mt-3">
                    <div class="px-3 mb-2 text-white-50 small">MANAJEMEN</div>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}" 
                       class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="bi bi-people me-2"></i>User
                        @if(App\Models\User::where('status', 'pending')->count() > 0)
                            <span class="badge bg-danger ms-2">
                                {{ App\Models\User::where('status', 'pending')->count() }}
                            </span>
                        @endif
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.vacancies.index') }}" 
                       class="nav-link {{ request()->routeIs('admin.vacancies.*') ? 'active' : '' }}">
                        <i class="bi bi-briefcase me-2"></i>Lowongan
                        @if(App\Models\JobVacancy::where('status', 'pending')->count() > 0)
                            <span class="badge bg-warning ms-2">
                                {{ App\Models\JobVacancy::where('status', 'pending')->count() }}
                            </span>
                        @endif
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.companies.index') }}" 
                       class="nav-link {{ request()->routeIs('admin.companies.*') ? 'active' : '' }}">
                        <i class="bi bi-building me-2"></i>Perusahaan
                    </a>
                </li>
                
                <li class="nav-item mt-3">
                    <div class="px-3 mb-2 text-white-50 small">KONTEN</div>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.posts.index') }}" 
                       class="nav-link {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
                        <i class="bi bi-newspaper me-2"></i>Postingan
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.about.edit') }}" 
                       class="nav-link {{ request()->routeIs('admin.about.*') ? 'active' : '' }}">
                        <i class="bi bi-info-circle me-2"></i>Tentang BKK
                    </a>
                </li>
                
                <li class="nav-item mt-3">
                    <div class="px-3 mb-2 text-white-50 small">PENGATURAN</div>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.settings.index') }}" 
                       class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <i class="bi bi-gear me-2"></i>Settings
                    </a>
                </li>
                
                <li class="nav-item mt-4 mb-3">
                    <a href="{{ route('home') }}" class="nav-link">
                        <i class="bi bi-house me-2"></i>Ke Website
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <button class="btn btn-link d-md-none" id="sidebarToggle">
                        <i class="bi bi-list fs-3"></i>
                    </button>
                    <h5 class="mb-0 d-none d-md-inline">@yield('page-title', 'Dashboard')</h5>
                </div>
                
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-2"></i>
                        {{ Auth::user()->email }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div class="p-4">
            <!-- SweetAlert Messages -->
            @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: '{{ session('success') }}',
                        confirmButtonColor: '#0d6efd',
                        timer: 3000,
                        timerProgressBar: true,
                    });
                });
            </script>
            @endif
            
            @if(session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: '{{ session('error') }}',
                        confirmButtonColor: '#dc3545',
                    });
                });
            </script>
            @endif
            
            @if($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    let errorList = '<ul class="text-start mb-0">';
                    @foreach($errors->all() as $error)
                        errorList += '<li>{{ $error }}</li>';
                    @endforeach
                    errorList += '</ul>';
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Terdapat Kesalahan',
                        html: errorList,
                        confirmButtonColor: '#dc3545',
                        width: '600px',
                    });
                });
            </script>
            @endif
            
            @yield('content')
        </div>
    </div>

    <script>
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.getElementById('sidebarToggle');
            
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !toggle?.contains(event.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });
        
        // Global confirmation functions
        function confirmDelete(event, message = 'Data yang dihapus tidak dapat dikembalikan!') {
            event.preventDefault();
            const form = event.target.closest('form');
            
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
            return false;
        }
        
        function confirmApprove(event, message = 'Approve data ini?') {
            event.preventDefault();
            const form = event.target.closest('form');
            
            Swal.fire({
                title: 'Konfirmasi',
                text: message,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Approve!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
            return false;
        }
    </script>
    
    @stack('scripts')
</body>
</html>