<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
        @if(settings('site_logo'))
            <img src="{{ Storage::url(settings('site_logo')) }}" 
                alt="{{ settings('site_name', 'BKK') }}" 
                style="height: 40px; margin-right: 10px;">
        @endif
        <span>{{ settings('site_name') }}</span>
    </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active fw-bold' : '' }}" href="{{ route('home') }}">
                        <i class="bi bi-house-door me-1"></i>Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('information*') ? 'active fw-bold' : '' }}" href="{{ route('information') }}">
                        <i class="bi bi-info-circle me-1"></i>Informasi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('companies*') ? 'active fw-bold' : '' }}" href="{{ route('companies') }}">
                        <i class="bi bi-building me-1"></i>Perusahaan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('vacancies*') ? 'active fw-bold' : '' }}" href="{{ route('vacancies') }}">
                        <i class="bi bi-briefcase me-1"></i>Lowongan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('about') ? 'active fw-bold' : '' }}" href="{{ route('about') }}">
                        <i class="bi bi-people me-1"></i>Tentang
                    </a>
                </li>
                
                @auth
                    <li class="nav-item dropdown ms-lg-2">
                        <a class="nav-link dropdown-toggle btn btn-outline-primary" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>
                            {{ Str::limit(Auth::user()->email, 20) }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @if(Auth::user()->isStudent())
                                <li>
                                    <a class="dropdown-item" href="{{ route('student.dashboard') }}">
                                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('student.profile') }}">
                                        <i class="bi bi-person me-2"></i>Profile
                                    </a>
                                </li>
                            @elseif(Auth::user()->isCompany())
                                <li>
                                    <a class="dropdown-item" href="{{ route('company.dashboard') }}">
                                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('company.profile') }}">
                                        <i class="bi bi-building me-2"></i>Profile Perusahaan
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('company.vacancies') }}">
                                        <i class="bi bi-briefcase me-2"></i>Kelola Lowongan
                                    </a>
                                </li>
                            @elseif(Auth::user()->isAdmin())
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-shield-check me-2"></i>Admin Panel
                                    </a>
                                </li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item dropdown ms-lg-2">
                        <a class="nav-link dropdown-toggle btn btn-primary text-white" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Login
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('login') }}">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Login
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li class="dropdown-header">Belum punya akun?</li>
                            <li>
                                <a class="dropdown-item" href="{{ route('register.student') }}">
                                    <i class="bi bi-person-plus me-2"></i>Daftar Siswa/Alumni
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('register.company') }}">
                                    <i class="bi bi-building-add me-2"></i>Daftar Perusahaan
                                </a>
                            </li>
                        </ul>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>