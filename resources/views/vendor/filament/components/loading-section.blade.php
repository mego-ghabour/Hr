@props([
    'columnSpan' => [],
    'columnStart' => [],
    'height' => null,
    'loadingLabel' => null,
    'pageFilters' => null,
    'widgetData' => null,
])

@php
    use Filament\Support\View\ComponentAttributeBag;
@endphp

<div
    role="status"
    aria-busy="true"
    {{
        ($attributes ?? new ComponentAttributeBag)
            ->filter(fn ($value) => !is_array($value))
            ->gridColumn($columnSpan, $columnStart)
            ->class(['fi-section fi-loading-section'])
            ->style(['height: ' . e($height ?? '8rem')])
    }}
>
    <span class="fi-sr-only">
        {{ $loadingLabel ?? __('filament::components/loading-section.label') }}
    </span>
</div>
