@props([
    'label',
    'name',
    'options' => [],
    'selected' => null,
    'placeholder' => 'Pilih salah satu',
    'error' => false,
    'class' => '',
    'labelClass' => '',
    'optionClass' => '',
    'withRequest' => false, // Tambahkan prop baru untuk handle request
    'allOption' => false, // Tambahkan prop untuk opsi "All"
    'allOptionText' => 'Semua', // Text untuk opsi "All"
])

@if ($label)
    <label for="{{ $name }}"
        {{ $attributes->merge(['class' => 'block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 ' . $labelClass]) }}>
        {{ $label }}
    </label>
@endif

<select id="{{ $name }}" name="{{ $name }}"
    {{ $attributes->merge(['class' => 'w-full px-4 py-2 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent ' . $class]) }}>

    @if ($allOption)
        <option value="" {{ (!$withRequest && is_null($selected)) || ($withRequest && !request($name)) ? 'selected' : '' }}>
            {{ $allOptionText }}
        </option>
    @elseif ($placeholder)
        <option value="" disabled {{ !$withRequest && is_null($selected) ? 'selected' : '' }}>
            {{ $placeholder }}
        </option>
    @endif

    @foreach ($options as $value => $text)
        <option value="{{ $value }}"
            @if($withRequest)
                {{ request($name) === (string)$value ? 'selected' : '' }}
            @else
                {{ $selected == $value ? 'selected' : '' }}
            @endif>
            {{ $text }}
        </option>
    @endforeach
</select>

@error($name)
    <span class="text-red-500">{{ $message }}</span>
@enderror
