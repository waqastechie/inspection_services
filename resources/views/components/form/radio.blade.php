@props([
    'name',
    'label' => null,
    'items' => [],
    'selectedValue' => '',
    'inline' => false,
    'required' => false,
    'class' => ''
])

@php
    $fieldId = 'field_' . $name;
    $hasError = $errors->has($name);
    $containerClass = $inline ? 'd-flex flex-wrap gap-3' : '';
@endphp

<div class="mb-3">
    @if($label)
        <label class="form-label">
            {{ $label }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <div class="{{ $containerClass }}">
        @foreach($items as $value => $labelText)
            <div class="form-check {{ $inline ? '' : 'mb-2' }}">
                <input 
                    class="form-check-input {{ $class }}" 
                    type="radio" 
                    name="{{ $name }}" 
                    id="{{ $fieldId }}_{{ $loop->index }}" 
                    value="{{ $value }}"
                    :class="{ 'is-invalid-alpine': shouldShowError('{{ $name }}') && errors.{{ $name }} }"
                    @change="validateField('{{ $name }}', $event.target.value)"
                    {{ old($name, $selectedValue) == $value ? 'checked' : '' }}
                    {{ $required ? 'required' : '' }}
                    {{ $attributes }}
                />
                <label class="form-check-label" for="{{ $fieldId }}_{{ $loop->index }}">
                    {{ $labelText }}
                </label>
            </div>
        @endforeach
    </div>

    <!-- Alpine.js Error Display (only show if field has been touched or submit attempted) -->
    <div x-show="shouldShowError('{{ $name }}') && errors.{{ $name }}" class="invalid-feedback-alpine" x-text="errors.{{ $name }}"></div>

    <!-- Laravel Backend Error Display (only show when user has attempted submit) -->
    @error($name)
        <div x-show="hasAttemptedSubmit" class="invalid-feedback d-block">
            {{ $message }}
        </div>
    @enderror
</div>
