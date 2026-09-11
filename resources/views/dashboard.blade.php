@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <nav>
        <strong>Halo, {{ auth()->user()->name }}</strong>
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button type="submit" style="width:auto;">Logout</button>
        </form>
    </nav>

    <h2>Dashboard Utama</h2>

    {{-- Panggil modul task buatan Orang 2 dari folder tasks/index.blade.php --}}
    @include('tasks.index')

@endsection