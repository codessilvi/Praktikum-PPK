@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <h2>Login</h2>

    @if (session('error'))
        <div class="error">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('login.store') }}">
        @csrf

        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}">
        @error('email') <div class="error">{{ $message }}</div> @enderror

        <label>Password</label>
        <input type="password" name="password">
        @error('password') <div class="error">{{ $message }}</div> @enderror

        <button type="submit">Login</button>
    </form>

    <p>Belum punya akun? <a href="{{ route('register') }}">Register di sini</a></p>
@endsection
