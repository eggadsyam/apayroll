@props(['items' => []])

<nav aria-label="breadcrumb" class="bg-light p-3 rounded mb-4 shadow-sm">
    <ol class="breadcrumb mb-0">
        @foreach($items as $label => $url)
            @if($loop->last)
                <li class="breadcrumb-item active" aria-current="page">{{ $label }}</li>
            @else
                <li class="breadcrumb-item"><a href="{{ $url ?? '#' }}" class="text-decoration-none">{{ $label }}</a></li>
            @endif
        @endforeach
    </ol>
</nav>
