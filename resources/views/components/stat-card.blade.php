@props(['title', 'value', 'icon', 'color' => 'primary', 'subtitle' => null])

<div class="card shadow-sm border-0 border-start border-{{ $color }} border-4 h-100 py-2">
    <div class="card-body">
        <div class="row no-gutters align-items-center">
            <div class="col mr-2">
                <div class="text-xs font-weight-bold text-{{ $color }} text-uppercase mb-1" style="font-size: 0.8rem; font-weight: 700;">
                    {{ $title }}
                </div>
                <div class="h5 mb-0 font-weight-bold text-dark fw-bold fs-4">{{ $value }}</div>
                @if($subtitle)
                    <div class="mt-2 mb-0 text-muted text-xs" style="font-size: 0.85rem;">
                        {{ $subtitle }}
                    </div>
                @endif
            </div>
            <div class="col-auto">
                <i class="fas {{ $icon }} fa-2x text-gray-300" style="color: #dddfeb;"></i>
            </div>
        </div>
    </div>
</div>
