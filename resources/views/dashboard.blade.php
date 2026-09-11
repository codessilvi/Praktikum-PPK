@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <nav>
        <strong>Halo, {{ auth()->user()->name }}</strong>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="width:auto;">Logout</button>
        </form>
    </nav>

    <h2>Daftar Tugas</h2>

    <a href="#">+ Tambah Tugas</a> {{-- [SESUAIKAN] --}}

    @forelse ($tasks as $task)
        <div class="task">
            <span class="{{ $task['status'] === 'selesai' ? 'status-done' : '' }}">
                {{ $task['title'] }}
            </span>
            — <em>{{ $task['status'] }}</em>
        </div>
    @empty
        <p>Belum ada tugas.</p>
    @endforelse
@endsection
