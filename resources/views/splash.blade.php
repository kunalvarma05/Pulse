@extends('layouts.main')
@section('pageTitle', 'Dashboard')

@section('body')
<div class="site">
    @include('partials.navbar')
    @include('partials.sidemenu')
    @include('partials.explorer')
    @include('partials.sidebar')
    @include('partials.modals.connect-account')
</div>
@endsection