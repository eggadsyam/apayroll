@props(['name', 'label', 'options' => [], 'selected' => '', 'required' => false, 'placeholder' => 'Pilih salah satu...'])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label fw-bold">
        {{ $label }} @if($required) <span class="text-danger">*</span> @endif
    </label>
    <select 
        class="form-select @error($name) is-invalid @enderror" 
        id="{{ $name }}" 
        name="{{ $name }}"
        {{ $required ? 'required' : '' }}
    >
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        
        @foreach($options as $key => $optionLabel)
            <option value="{{ $key }}" {{ old($name, $selected) == $key ? 'selected' : '' }}>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>
    @error($name)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>
