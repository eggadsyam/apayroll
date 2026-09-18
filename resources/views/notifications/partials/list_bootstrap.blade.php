@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="list-group list-group-flush mb-4">
    @forelse($notifications as $notification)
        <div class="list-group-item d-flex justify-content-between align-items-start py-3 {{ $notification->read_at ? '' : 'list-group-item-primary' }}">
            <div class="ms-2 me-auto">
                <div class="fw-bold mb-1 {{ $notification->read_at ? 'text-dark' : 'text-primary' }}">
                    {{ $notification->data['title'] ?? 'Notification' }}
                </div>
                <div class="mb-1 text-secondary" style="font-size: 0.9rem;">
                    {{ $notification->data['message'] ?? '' }}
                </div>
                <div class="text-muted" style="font-size: 0.8rem;">
                    <i class="far fa-clock me-1"></i> {{ $notification->created_at->format('d M Y H:i') }} ({{ $notification->created_at->diffForHumans() }})
                </div>
                
                @if(isset($notification->data['action_url']))
                    <a href="{{ $notification->data['action_url'] }}" class="btn btn-sm btn-link text-decoration-none mt-2 p-0 text-primary fw-bold">
                        View Details <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                @endif
            </div>
            
            @if(!$notification->read_at)
                <form method="POST" action="{{ route('notifications.mark-read', $notification->id) }}" class="ms-3">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-primary" title="Tandai dibaca">
                        <i class="fas fa-check"></i>
                    </button>
                </form>
            @endif
        </div>
    @empty
        <div class="text-center py-5 text-muted">
            <i class="fas fa-bell-slash fs-1 mb-3 text-light"></i>
            <p>Belum ada notifikasi.</p>
        </div>
    @endforelse
</div>

<div class="d-flex justify-content-center">
    {{ $notifications->links('pagination::bootstrap-5') }}
</div>
