@php
    use Illuminate\Support\Arr;
    $sites = Arr::wrap(config('tracking.plausible.sites'));
@endphp
@if(filled($sites))
    <script defer data-domain="{{implode(',', $sites)}}" src="https://plausible.io/js/script.js"></script>
@endif
