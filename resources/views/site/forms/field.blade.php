{{-- Params: $name, $label, $type (text|email|tel|textarea|file|date|select), $required, $options, $placeholder, $accept --}}
@php($type = $type ?? 'text')
@php($fieldId = 'f-' . ($formId ?? 'form') . '-' . $name)
<div class="fui-field {{ $errors->has($name) ? 'fui-error' : '' }}">
    <div class="fui-field-container">
        <label class="fui-label" for="{{ $fieldId }}">{{ $label }}@if ($required ?? false)&nbsp;<span class="fui-required" aria-hidden="true">*</span>@endif</label>
        <div class="fui-input-wrapper">
            @if ($type === 'textarea')
                <textarea id="{{ $fieldId }}" class="fui-input" name="{{ $name }}" rows="4" placeholder="{{ $placeholder ?? '' }}" @required($required ?? false)>{{ old($name) }}</textarea>
            @elseif ($type === 'select')
                <select id="{{ $fieldId }}" class="fui-select" name="{{ $name }}" @required($required ?? false)>
                    <option value=""></option>
                    @foreach ($options as $option)
                        <option value="{{ $option }}" @selected(old($name) === $option)>{{ $option }}</option>
                    @endforeach
                </select>
            @elseif ($type === 'file')
                <input id="{{ $fieldId }}" class="fui-input" type="file" name="{{ $name }}" accept="{{ $accept ?? '.pdf,.doc,.docx' }}" @required($required ?? false)>
            @else
                <input id="{{ $fieldId }}" class="fui-input" type="{{ $type }}" name="{{ $name }}" value="{{ old($name) }}" placeholder="{{ $placeholder ?? '' }}" @required($required ?? false)>
            @endif
        </div>
        @error($name)<div class="fui-errors"><p class="fui-error-message">{{ $message }}</p></div>@enderror
    </div>
</div>
