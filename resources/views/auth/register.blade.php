@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <h2>Register</h2>

    <form method="POST" action="{{ route('register.store') }}">
        {{-- CSRF token: WAJIB ada di setiap form POST Laravel, ini yang mencegah serangan CSRF --}}
        @csrf

        <label>Nama</label>
        <input type="text" name="name" value="{{ old('name') }}">
        @error('name') <div class="error">{{ $message }}</div> @enderror

        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}">
        @error('email') <div class="error">{{ $message }}</div> @enderror

        <label>Password</label>
        <input type="password" name="password">
        @error('password') <div class="error">{{ $message }}</div> @enderror

        <label>Konfirmasi Password</label>
        <input type="password" name="password_confirmation">

        <button type="submit">Register</button>
    </form>

    <p>Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a></p>
@endsection
