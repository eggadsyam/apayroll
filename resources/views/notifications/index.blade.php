@extends('layouts.admin')

@section('title', 'Notifikasi')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800 fw-bold">Notifikasi</h1>
    @if($notifications->count() > 0)
        <form method="POST" action="{{ route('notifications.mark-all-read') }}">
            @csrf
            <button type="submit" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-check-double me-1"></i> Tandai semua dibaca
            </button>
        </form>
    @endif
</div>

<div class="card shadow mb-4 border-0">
    <div class="card-body p-0">
        @include('notifications.partials.list_bootstrap')
    </div>
</div>
@endsection
