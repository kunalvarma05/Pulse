@extends('layouts.main')
@section('pageTitle', 'Dashboard')

@section('body')
<div class="site">
    <nav class="navbar navbar-full navbar-light navbar-main">
        <a href="{{ url('/') }}" class="navbar-brand navbar-logo">
            <img src="{{ asset('images/logo-dark.png') }}" alt="pulse logo">
        </a>
        <form class="navbar-search-form">
            <input class="form-control navbar-search-input" type="text" placeholder="Search">
        </form>
        <ul class="nav navbar-nav pull-right">
            <li class="nav-item dropdown notifications-dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                    <span class="label label-primary notification-count animated zoomIn">2</span>
                    <i class="fa fa-bell"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <h6 class="dropdown-header">Notifications</h6>
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <a class="dropdown-item" href="#">Something else here</a>
                </div>
            </li>
            <li class="nav-item dropdown account-dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                    Kunal Varma <span class="caret"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <a class="dropdown-item" href="#">Something else here</a>
                </div>
            </li>
        </ul>
    </nav>
</div>
@endsection