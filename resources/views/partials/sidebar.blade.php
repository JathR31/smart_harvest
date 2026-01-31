@php
    $user = Auth::user();
    $isSuperadmin = $user && ($user->is_superadmin || $user->email === 'superadmin@smartharvest.ph');
@endphp

@if($isSuperadmin)
    @include('partials.sidebar_superadmin')
@else
    @include('partials.sidebar_daadmin')
@endif
