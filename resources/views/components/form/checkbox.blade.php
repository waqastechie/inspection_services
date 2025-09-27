@props([
    'name',
    'label' => null,
    'checked' => false,
    'value' => '1',
    'uncheckedValue' => '0',
    'required' => false,
    'class' => '',
    'help' => null
])

@php
    $fieldId = 'field_' . $name;
    $hasError = $errors->has($name);
    $isChecked = old($name) !== null ? old($name) == $value : $checked;
@endphp

<div class="mb-3">
    <div class="form-check">
        <!-- Hidden input for unchecked value -->
        <input type="hidden" name="{{ $name }}" value="{{ $uncheckedValue }}">
        
        <input 
            class="form-check-input {{ $class }}" 
            type="checkbox" 
            name="{{ $name }}" 
            id="{{ $fieldId }}" 
            value="{{ $value }}"
            :class="{ 'is-invalid-alpine': shouldShowError('{{ $name }}') && errors.{{ $name }} }"
            @change="validateField('{{ $name }}', $event.target.checked ? '{{ $value }}' : '{{ $uncheckedValue }}')"
            {{ $isChecked ? 'checked' : '' }}
            {{ $required ? 'required' : '' }}
            {{ $attributes }}
        />
        
        @if($label)
            <label class="form-check-label" for="{{ $fieldId }}">
                {{ $label }}
                @if($required)
                    <span class="text-danger">*</span>
                @endif
            </label>
        @endif
    </div>

    @if($help)
        <div class="form-text">{{ $help }}</div>
    @endif

    <!-- Alpine.js Error Display (only show if field has been touched or submit attempted) -->
    <div x-show="shouldShowError('{{ $name }}') && errors.{{ $name }}" class="invalid-feedback-alpine" x-text="errors.{{ $name }}"></div>

    <!-- Laravel Backend Error Display (only show when user has attempted submit) -->
    @error($name)
        <div x-show="hasAttemptedSubmit" class="invalid-feedback d-block">
            {{ $message }}
        </div>
    @enderror
</div>
