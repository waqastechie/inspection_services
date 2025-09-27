@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'options' => null,
    'help' => null,
    'class' => '',
    'rows' => 3
])

@php
    $fieldId = 'field_' . $name;
    // Don't add is-invalid class immediately - let Alpine.js handle validation timing
    $fieldClass = 'form-control ' . $class;
@endphp

<div class="mb-3">
    @if($label)
        <label for="{{ $fieldId }}" class="form-label">
            {{ $label }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    @if($type === 'select')
        <select 
            name="{{ $name }}" 
            id="{{ $fieldId }}" 
            class="{{ $fieldClass }}"
            :class="{ 'is-invalid-alpine': shouldShowError('{{ $name }}') && errors.{{ $name }} }"
            @change="validateField('{{ $name }}', $event.target.value)"
            {{ $required ? 'required' : '' }}
            {{ $attributes }}
        >
            <option value="">Select {{ $label ?? $name }}</option>
            @if($options)
                @foreach($options as $optionValue => $optionLabel)
                    <option value="{{ $optionValue }}" {{ old($name, $value) == $optionValue ? 'selected' : '' }}>
                        {{ $optionLabel }}
                    </option>
                @endforeach
            @endif
        </select>
    @elseif($type === 'textarea')
        <textarea 
            name="{{ $name }}" 
            id="{{ $fieldId }}" 
            class="{{ $fieldClass }}"
            :class="{ 'is-invalid-alpine': shouldShowError('{{ $name }}') && errors.{{ $name }} }"
            @input="validateField('{{ $name }}', $event.target.value)"
            @blur="validateField('{{ $name }}', $event.target.value)"
            placeholder="{{ $placeholder }}"
            rows="{{ $rows }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes }}
        >{{ old($name, $value) }}</textarea>
    @elseif($type === 'file')
        <input 
            type="{{ $type }}" 
            name="{{ $name }}" 
            id="{{ $fieldId }}" 
            class="{{ $fieldClass }}"
            :class="{ 'is-invalid-alpine': shouldShowError('{{ $name }}') && errors.{{ $name }} }"
            @change="validateField('{{ $name }}', $event.target.value)"
            {{ $required ? 'required' : '' }}
            {{ $attributes }}
        />
    @else
        <input 
            type="{{ $type }}" 
            name="{{ $name }}" 
            id="{{ $fieldId }}" 
            class="{{ $fieldClass }}"
            :class="{ 'is-invalid-alpine': shouldShowError('{{ $name }}') && errors.{{ $name }} }"
            @input="validateField('{{ $name }}', $event.target.value)"
            @blur="validateField('{{ $name }}', $event.target.value)"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes }}
        />
    @endif

    <!-- Alpine.js Error Display (only show if field has been touched or submit attempted) -->
    <div x-show="shouldShowError('{{ $name }}') && errors.{{ $name }}" class="invalid-feedback-alpine" x-text="errors.{{ $name }}"></div>

    <!-- Laravel Backend Error Display (only show when user has attempted submit) -->
    @error($name)
        <div x-show="hasAttemptedSubmit" class="invalid-feedback d-block">
            {{ $message }}
        </div>
    @enderror

    @if($help)
        <div class="form-text">{{ $help }}</div>
    @endif
</div>
