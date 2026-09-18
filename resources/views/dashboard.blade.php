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

    <h2>Dashboard</h2>

    {{-- BAGIAN PROGRESS (SRS-006) --}}
    @php
        $totalTasks = $tasks->count();
        $completedTasks = $tasks->where('status', 'selesai')->count();
        $percentage = $totalTasks > 0
            ? round(($completedTasks / $totalTasks) * 100)
            : 0;
    @endphp

    <div style="margin-bottom: 20px; padding: 10px; background: #f4f4f4; border: 1px solid #ddd;">
        <strong>Progress:</strong>
        {{ $completedTasks }} dari {{ $totalTasks }} tugas selesai
        ({{ $percentage }}%)
    </div>

    {{-- TASK LIST --}}
    <h2>Daftar Task List</h2>

    @forelse ($taskLists as $taskList)
        <div style="
            border: 1px solid #aaa;
            padding: 15px;
            margin-top: 15px;
            border-radius: 5px;
        ">

            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <strong style="font-size: 18px;">
                        {{ $taskList->name }}
                    </strong>

                    @if ($taskList->description)
                        <p style="margin: 5px 0;">
                            {{ $taskList->description }}
                        </p>
                    @endif
                </div>

                {{-- HAPUS TASK LIST --}}
                <form
                    method="POST"
                    action="{{ route('task-lists.destroy', $taskList->id) }}"
                    onsubmit="return confirm('Yakin ingin menghapus task list ini beserta semua task di dalamnya?');"
                >
                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        style="
                            background: #dc3545;
                            color: white;
                            border: none;
                            padding: 6px 10px;
                            border-radius: 4px;
                            cursor: pointer;
                        "
                    >
                        Hapus Task List
                    </button>
                </form>
            </div>

            {{-- TASK DI DALAM LIST --}}
            <div style="margin-top: 15px;">
                <strong>Task dalam list:</strong>

                @forelse ($taskList->tasks as $task)
                    <div style="
                        border-top: 1px dashed #ccc;
                        padding: 10px 0;
                    ">

                        @php
                            $isCompleted = $task->status === 'selesai';
                        @endphp

                        <div style="
                            {{ $isCompleted
                                ? 'text-decoration: line-through; color: #888;'
                                : '' }}
                        ">
                            <strong>{{ $task->title }}</strong>

                            @if ($isCompleted)
                                (Selesai)
                            @endif
                        </div>

                        {{-- TOMBOL STATUS --}}
                        <form
                            action="{{ route('tasks.toggleStatus', $task->id) }}"
                            method="POST"
                            style="margin-top: 8px;"
                        >
                            @csrf
                            @method('PATCH')

                            <button
                                type="submit"
                                style="
                                    background: {{ $isCompleted ? '#6c757d' : '#28a745' }};
                                    color: white;
                                    padding: 3px 8px;
                                    font-size: 12px;
                                    border: none;
                                    cursor: pointer;
                                "
                            >
                                {{ $isCompleted
                                    ? 'Batalkan Selesai'
                                    : 'Tandai Selesai' }}
                            </button>
                        </form>

                        {{-- PRIORITAS --}}
                        <div style="margin-top: 5px;">
                            Prioritas: {{ $task->priority }}
                        </div>

                        {{-- DEADLINE --}}
                        @if ($task->deadline)
                            <div>
                                Deadline:
                                {{ \Carbon\Carbon::parse($task->deadline)->format('d/m/Y H:i') }}
                            </div>
                        @endif

                        {{-- COLLABORATOR --}}
                        <div
                            class="task-info"
                            style="
                                margin-top: 10px;
                                border-top: 1px dashed #ddd;
                                padding-top: 8px;
                            "
                        >
                            <strong>Anggota Kolaborasi:</strong>

                            @if ($task->collaborators->count() > 0)
                                <ul>
                                    @foreach ($task->collaborators as $collab)
                                        <li>{{ $collab->name }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <span> Tidak ada</span>
                            @endif
                        </div>

                        {{-- EDIT TASK --}}
                        <div style="margin-top: 12px;">
                            <a
                                href="{{ route('tasks.edit', $task->id) }}"
                                style="
                                    padding: 4px 10px;
                                    background: #ffc107;
                                    color: black;
                                    text-decoration: none;
                                    font-size: 12px;
                                    border-radius: 3px;
                                "
                            >
                                Edit
                            </a>
                        </div>

                    </div>
                @empty
                    <p style="margin-top: 10px;">
                        Belum ada task dalam list ini.
                    </p>
                @endforelse
            </div>

        </div>
    @empty
        <p>Belum ada task list.</p>
    @endforelse

    <br>

    <a href="{{ route('tasks.create') }}">+ Tambah Tugas</a>

    {{-- TASK YANG TIDAK MASUK LIST --}}
    <h2 style="margin-top: 30px;">Tugas Lainnya</h2>

    @php
        $unlistedTasks = $tasks->whereNull('task_list_id');
    @endphp

    @forelse ($unlistedTasks as $task)
        @php
            $isCompleted = $task->status === 'selesai';
        @endphp

        <div
            class="task"
            style="
                border: 1px solid #ccc;
                padding: 15px;
                margin-top: 15px;
                border-radius: 5px;
            "
        >
            <div style="
                {{ $isCompleted
                    ? 'text-decoration: line-through; color: #888;'
                    : '' }}
            ">
                <strong>{{ $task->title }}</strong>

                @if ($isCompleted)
                    (Selesai)
                @endif
            </div>

            <div style="margin-top: 5px;">
                Prioritas: {{ $task->priority }}
            </div>

            @if ($task->deadline)
                <div>
                    Deadline:
                    {{ \Carbon\Carbon::parse($task->deadline)->format('d/m/Y H:i') }}
                </div>
            @endif

            <div style="margin-top: 12px;">
                <a
                    href="{{ route('tasks.edit', $task->id) }}"
                    style="
                        padding: 4px 10px;
                        background: #ffc107;
                        color: black;
                        text-decoration: none;
                        font-size: 12px;
                        border-radius: 3px;
                    "
                >
                    Edit
                </a>
            </div>
        </div>
    @empty
        <p>Tidak ada tugas di luar task list.</p>
    @endforelse

@endsection