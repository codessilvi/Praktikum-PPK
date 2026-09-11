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

    {{-- BAGIAN PROGRESS (SRS-006) --}}
    @php
        $totalTasks = $tasks->count();
        $completedTasks = $tasks->where('is_completed', true)->count();
        $percentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
    @endphp

    <div style="margin-bottom: 20px; padding: 10px; background: #f4f4f4; border: 1px solid #ddd;">
        <strong>Progress:</strong> {{ $completedTasks }} dari {{ $totalTasks }} tugas selesai ({{ $percentage }}%)
    </div>

    <a href="{{ route('tasks.create') }}">+ Tambah Tugas</a>

    @forelse ($tasks as $task)
        <div class="task" style="border: 1px solid #ccc; padding: 15px; margin-top: 15px; border-radius: 5px;">
            
            {{-- JUDUL TUGAS & INDIKATOR SELESAI --}}
            <div style="{{ $task->is_completed ? 'text-decoration: line-through; color: #888;' : '' }}">
                <strong>{{ $task->title }}</strong> @if($task->is_completed) (Selesai) @endif
            </div>

            {{-- TOMBOL TANDAI SELESAI (SRS-006) --}}
            <form action="{{ route('tasks.toggleStatus', $task->id) }}" method="POST" style="margin-top: 8px;">
                @csrf
                @method('PATCH')
                <button type="submit" style="background: {{ $task->is_completed ? '#6c757d' : '#28a745' }}; color: white; padding: 3px 8px; font-size: 12px; border: none; cursor: pointer;">
                    {{ $task->is_completed ? 'Batalkan Selesai' : 'Tandai Selesai' }}
                </button>
            </form>

            <div style="margin-top: 5px;">
                Prioritas: {{ $task->priority }}
            </div>

            @if ($task->deadline)
                <div>
                    Deadline:
                    {{ \Carbon\Carbon::parse($task->deadline)->format('d/m/Y H:i') }}
                </div>
            @endif

            {{-- BAGIAN KOLABORASI (SRS-005) --}}
            <div class="task-info" style="margin-top: 10px; border-top: 1px dashed #ddd; padding-top: 8px;">
                <strong>Anggota Kolaborasi:</strong>
                <ul>
                    @foreach($task->collaborators as $collab)
                        <li>
                            {{ $collab->name }}
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- TOMBOL EDIT --}}
            <div style="margin-top: 12px;">
                <a href="{{ route('tasks.edit', $task->id) }}" style="padding: 4px 10px; background: #ffc107; color: black; text-decoration: none; font-size: 12px; border-radius: 3px;">Edit</a>
            </div>

        </div>
    @empty
        <p style="margin-top: 15px;">Belum ada tugas.</p>
    @endforelse
@endsection