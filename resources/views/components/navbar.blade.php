<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container-fluid">
        <button type="button" id="sidebarCollapse" class="btn btn-primary">
            <i class="fas fa-bars"></i>
        </button>

        <div class="ms-auto d-flex align-items-center">
            @auth
            <!-- Notifications Dropdown -->
            <div class="dropdown me-3">
                <button class="btn btn-light border-0 bg-transparent position-relative" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bell fs-5"></i>
                    @if(Auth::user()->unreadNotifications->count() > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                            {{ Auth::user()->unreadNotifications->count() > 99 ? '99+' : Auth::user()->unreadNotifications->count() }}
                        </span>
                    @endif
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="notificationDropdown" style="width: 300px; max-height: 400px; overflow-y: auto;">
                    <li>
                        <div class="dropdown-header d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-dark">Notifikasi</span>
                            @if(Auth::user()->unreadNotifications->count() > 0)
                                <form method="POST" action="{{ route('notifications.mark-all-read') }}" class="m-0 p-0">
                                    @csrf
                                    <button type="submit" class="btn btn-link p-0 text-decoration-none" style="font-size: 0.8rem;">Tandai semua dibaca</button>
                                </form>
                            @endif
                        </div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    
                    @forelse(Auth::user()->unreadNotifications->take(5) as $notification)
                        <li>
                            <div class="dropdown-item d-flex justify-content-between align-items-start text-wrap py-2">
                                <div>
                                    <div class="text-dark small">{{ $notification->data['message'] ?? 'Notifikasi baru' }}</div>
                                    <div class="text-muted" style="font-size: 0.7rem;">{{ $notification->created_at->diffForHumans() }}</div>
                                </div>
                                <form method="POST" action="{{ route('notifications.mark-read', $notification->id) }}" class="ms-2">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-link text-primary p-0" title="Tandai dibaca">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                </form>
                            </div>
                        </li>
                    @empty
                        <li><div class="dropdown-item text-center text-muted small py-3">Tidak ada notifikasi baru</div></li>
                    @endforelse
                    
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-center small text-primary fw-bold" href="{{ request()->routeIs('portal.*') ? route('portal.notifications.index') : route('notifications.index') }}">Lihat Semua</a></li>
                </ul>
            </div>

            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle border-0 bg-transparent" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="{{ route('profile.edit') ?? '#' }}"><i class="fas fa-id-badge me-2 text-secondary"></i> Profil</a></li>
                    
                    <li><hr class="dropdown-divider"></li>
                    
                    @if(request()->routeIs('portal.*'))
                        @hasanyrole('super_admin|hrd|finance|manager')
                        <li><a class="dropdown-item fw-bold text-primary" href="{{ route('dashboard') }}"><i class="fas fa-exchange-alt me-2"></i> Ke Dashboard Admin</a></li>
                        <li><hr class="dropdown-divider"></li>
                        @endhasanyrole
                    @else
                        <li><a class="dropdown-item fw-bold text-primary" href="{{ route('portal.dashboard') }}"><i class="fas fa-exchange-alt me-2"></i> Ke Portal Mandiri</a></li>
                        <li><hr class="dropdown-divider"></li>
                    @endif

                    <li>
                        <form method="POST" action="{{ route('logout') ?? '#' }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
            @endauth
        </div>
    </div>
</nav>
