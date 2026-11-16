@props(['label', 'value', 'color' => 'text-primary'])
<div class="card shadow-sm h-100">
    <div class="card-body">
        <div class="small text-muted text-uppercase">{{ $label }}</div>
        <div class="display-6 fw-bold {{ $color }}">{{ $value }}</div>
    </div>
</div>