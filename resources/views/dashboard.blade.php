@extends('layouts.app')

@section('title', 'Daftar Tugas')

@section('content')
<div class="container" style="max-width: 600px; margin: 0 auto; padding: 20px;">
    <h1>Daftar Tugas</h1>

    {{-- SRS-006: Indicator Progress --}}
    @php
        $totalTasks = $tasks->count();
        $completedTasks = $tasks->where('is_completed', true)->count();
        $percentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
    @endphp

    <div style="margin-bottom: 20px; padding: 12px; background: #f4f4f4; border: 1px solid #ddd; border-radius: 4px;">
        <strong>Progress:</strong> {{ $completedTasks }} dari {{ $totalTasks }} tugas selesai ({{ $percentage }}%)
    </div>

    @if (session('success'))
        <div style="color: green; margin-bottom: 15px;">{{ session('success') }}</div>
    @endif

    <a href="{{ route('tasks.create') }}" class="add-link" style="display: inline-block; margin-bottom: 15px;">+ Tambah Tugas Baru</a>

    {{-- DAFTAR TUGAS --}}
    @forelse ($tasks as $task)
        @php
            $priorityColor = 'green';
            if ($task->priority === 'High') $priorityColor = 'red';
            if ($task->priority === 'Medium') $priorityColor = 'orange';

            $isOwner = (int)$task->user_id === (int)auth()->id();
        @endphp

        <div class="task" style="border: 1px solid #ccc; padding: 15px; margin-bottom: 15px; border-radius: 4px; background: #fff;">
            {{-- Judul & Status --}}
            <div class="task-title" style="font-size: 18px; font-weight: bold; {{ $task->is_completed ? 'text-decoration: line-through; color: #888;' : '' }}">
                {{ $task->title }} @if($task->is_completed) (Selesai) @endif
            </div>

            {{-- Toggle Status --}}
            <form action="{{ route('tasks.toggleStatus', $task->id) }}" method="POST" style="margin: 8px 0;">
                @csrf
                @method('PATCH')
                <button type="submit" style="background: {{ $task->is_completed ? '#6c757d' : '#28a745' }}; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer;">
                    {{ $task->is_completed ? 'Batalkan Selesai' : 'Tandai Selesai' }}
                </button>
            </form>

            @if ($task->description)
                <p style="margin: 8px 0; color: #333;">{{ $task->description }}</p>
            @endif

            {{-- Prioritas & Deadline --}}
            <div style="font-size: 13px; color: #555; margin-top: 5px;">
                <strong>Prioritas:</strong> 
                <span style="font-weight: bold; color: {{ $priorityColor }};">
                    {{ $task->priority }}
                </span>
            </div>
            <div style="font-size: 13px; color: #555; margin-top: 3px;">
                <strong>Deadline:</strong> {{ $task->deadline ? \Carbon\Carbon::parse($task->deadline)->format('d/m/Y H:i') : '-' }}
            </div>

            {{-- Kolaborator --}}
            <div style="margin-top: 10px; padding-top: 10px; border-top: 1px dashed #ddd; font-size: 13px;">
                <strong>Anggota Kolaborasi:</strong>
                <ul style="margin: 5px 0; padding-left: 20px;">
                    @forelse($task->collaborators as $collab)
                        <li>{{ $collab->name }}</li>
                    @empty
                        <li style="color: #888;">Tidak ada kolaborator</li>
                    @endforelse
                </ul>
            </div>

            {{-- Tombol Aksi --}}
            <div style="margin-top: 15px; display: flex; gap: 10px;">
                <a href="{{ route('tasks.edit', $task->id) }}" style="padding: 6px 12px; background: #ffc107; color: black; text-decoration: none; font-size: 13px; border-radius: 4px; font-weight: bold;">
                    Edit
                </a>

                @if($isOwner)
                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="padding: 6px 12px; background: #dc3545; color: white; border: none; font-size: 13px; border-radius: 4px; cursor: pointer; font-weight: bold;" onclick="return confirm('Yakin ingin menghapus tugas ini?')">
                            Hapus
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <p>Belum ada task list.</p>
    @endforelse

    <a href="{{ route('dashboard') }}" style="display: inline-block; margin-top: 15px; color: #007bff; text-decoration: none;">← Kembali ke Dashboard</a>
</div>
@endsection