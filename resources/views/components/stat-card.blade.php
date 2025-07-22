@props(['title', 'value', 'color' => 'primary'])

<div class="col-md-4 mb-3">
    <div class="card text-bg-{{ $color }} shadow-sm">
        <div class="card-body">
            <h6 class="card-title">{{ $title }}</h6>
            <h2 class="card-text">{{ $value }}</h2>
        </div>
    </div>
</div>
