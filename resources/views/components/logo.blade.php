@props([
    'width' => '35px',
    'height' => '35px',
    'fill' => '#01d676',
])

<a href="{{ url('/') }}" class="no-hover"> 
{{--    <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" width="200px"/>--}}
    <span class="text-center">
        <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" width="200px" class="app-brand-img">
        <img src="{{ asset('assets/img/icon-light.png') }}" alt="Logo" width="45px" class="app-brand-img-collapsed">
    </span>
</a>
