<div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)">
    @if(session('success'))
        <div x-show="show" x-transition.duration.500ms class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" @click="show = false" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div x-show="show" x-transition.duration.500ms class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" @click="show = false" aria-label="Close"></button>
        </div>
    @endif

    @if(session('warning'))
        <div x-show="show" x-transition.duration.500ms class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('warning') }}
            <button type="button" class="btn-close" @click="show = false" aria-label="Close"></button>
        </div>
    @endif

    @if(session('info'))
        <div x-show="show" x-transition.duration.500ms class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle me-2"></i> {{ session('info') }}
            <button type="button" class="btn-close" @click="show = false" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div x-show="show" x-transition.duration.500ms class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> <strong>Mohon periksa input Anda:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" @click="show = false" aria-label="Close"></button>
        </div>
    @endif
</div>
