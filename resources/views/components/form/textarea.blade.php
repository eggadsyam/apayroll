@props(['name', 'label', 'value' => '', 'rows' => 3, 'required' => false, 'placeholder' => ''])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label fw-bold">
        {{ $label }} @if($required) <span class="text-danger">*</span> @endif
    </label>
    <textarea 
        class="form-control @error($name) is-invalid @enderror" 
        id="{{ $name }}" 
        name="{{ $name }}" 
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
    >{{ old($name, $value) }}</textarea>
    @error($name)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>
