@extends('layouts.app')
@section('title', $title)
@section('sidebar')
<a class="sidebar-link" href="{{ route(auth()->user()->role.'.dashboard') }}"><i class="fa-solid fa-house"></i><span x-show="sidebarOpen">Dashboard</span></a>
@endsection
@section('content')
<div class="dashboard-page"><section class="dashboard-welcome p-5"><h2 class="text-xl font-bold">{{ $title }}</h2><p class="mt-2">{{ $description ?? '' }}</p></section>
@include($body)
</div>
@endsection
