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

    <a href="{{ route('tasks.index') }}">+ Tambah Tugas</a>

    @forelse ($tasks as $task)
        <div class="task">
            <strong>{{ $task->title }}</strong>

            <div>
                Prioritas: {{ $task->priority }}
            </div>

            @if ($task->deadline)
                <div>
                    Deadline:
                    {{ \Carbon\Carbon::parse($task->deadline)->format('d/m/Y H:i') }}
                </div>
            @endif
        </div>
    @empty
        <p>Belum ada tugas.</p>
    @endforelse
@endsection