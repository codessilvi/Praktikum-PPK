<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tugas</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 30px;
            background: white;
            color: #222;
        }

        .container {
            width: 500px;
            margin: 0 auto;
        }

        h1 {
            margin-bottom: 25px;
        }

        a {
            color: #333;
        }

        .add-link {
            display: inline-block;
            margin-bottom: 20px;
        }

    .form-box {
        margin-bottom: 25px;
    }

        .form-box input,
        .form-box textarea,
        .form-box select {
            width: 100%;
            box-sizing: border-box;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #999;
            font-family: Arial, sans-serif;
        }

        button {
            padding: 7px 14px;
            border: 1px solid #777;
            background: #333;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background: #222;
        }

        .cancel-button {
            background: white;
            color: #333;
            border: none;
            margin-left: 5px;
        }

        .task {
            border: 1px solid #ccc;
            padding: 18px;
            margin-bottom: 15px;
        }

        .task-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .task-description {
            margin-bottom: 8px;
        }

        .task-info {
            margin-bottom: 5px;
            color: #555;
        }

        .task-actions {
            margin-top: 12px;
        }

        .edit-form {
            display: none;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
        }

        .edit-form input,
        .edit-form textarea,
        .edit-form select {
            width: 100%;
            box-sizing: border-box;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #999;
            font-family: Arial, sans-serif;
        }

        .success {
            color: green;
            margin-bottom: 15px;
        }

        .error {
            color: red;
            margin-bottom: 15px;
        }

        .back {
            display: inline-block;
            margin-top: 10px;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Daftar Tugas</h1>

    @php
        $totalTasks = $tasks->count();
        $completedTasks = $tasks->where('is_completed', true)->count();
        $percentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
    @endphp

    <div style="margin-bottom: 20px; padding: 10px; background: #f4f4f4; border: 1px solid #ddd;">
        <strong>Progress:</strong> {{ $completedTasks }} dari {{ $totalTasks }} tugas selesai ({{ $percentage }}%)
    </div>

    {{-- Pesan berhasil --}}
    @if (session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Pesan error validasi --}}
    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Tombol Tambah --}}
    <a href="#" class="add-link"
       onclick="document.getElementById('form-tambah').style.display='block'; return false;">
        + Tambah Tugas
    </a>

    {{-- FORM TAMBAH --}}
    <div id="form-tambah" class="form-box">

        <form action="{{ route('tasks.store') }}" method="POST">
            @csrf

            <label>Judul Tugas:</label>
            <input
                type="text"
                name="title"
                placeholder="Masukkan judul tugas..."
                required
            >

            <label>Deskripsi:</label>
            <textarea
                name="description"
                rows="3"
                placeholder="Deskripsi tugas..."
            ></textarea>

            <label>Prioritas:</label>
            <select name="priority" required>
                <option value="Low">Low</option>
                <option value="Medium" selected>Medium</option>
                <option value="High">High</option>
            </select>

            <label>Deadline:</label>
            <input
                type="datetime-local"
                name="deadline"
            >

            <button type="submit">
                Simpan Tugas
            </button>

            <button
                type="button"
                class="cancel-button"
                onclick="document.getElementById('form-tambah').style.display='none';"
            >
                Batal
            </button>
        </form>

    </div>

    {{-- DAFTAR TUGAS --}}
    @forelse ($tasks as $task)

        <div class="task">

            <div class="task-title" style="{{ $task->is_completed ? 'text-decoration: line-through; color: #888;' : '' }}">
                {{ $task->title }} @if($task->is_completed) (Selesai) @endif
            </div>

            {{-- Tombol Status Selesai / Belum --}}
            <form action="{{ route('tasks.toggleStatus', $task->id) }}" method="POST" style="margin-top: 8px;">
                @csrf
                @method('PATCH')
                <button type="submit" style="background: {{ $task->is_completed ? '#6c757d' : '#28a745' }}; color: white;">
                    {{ $task->is_completed ? 'Batalkan Selesai' : 'Tandai Selesai' }}
                </button>
            </form>

            @if ($task->description)
                <div class="task-description">
                    {{ $task->description }}
                </div>
            @endif

            <div class="task-info">
                Prioritas: {{ $task->priority }}
            </div>

            @if ($task->deadline)
                <div class="task-info">
                    Deadline:
                    {{ \Carbon\Carbon::parse($task->deadline)->format('d/m/Y H:i') }}
                </div>
            @else
                <div class="task-info">
                    Deadline: -
                </div>
            @endif

{{-- BAGIAN SRS-005: KOLABORASI / ANGGOTA --}}
            <div class="task-info" style="margin-top: 15px; border-top: 1px dashed #ddd; padding-top: 10px;">
                <strong>Anggota Kolaborasi:</strong>
                <ul>
                    @foreach($task->collaborators as $collab)
                        <li>
                            {{ $collab->user->name }}
                            <form action="{{ route('collaborators.destroy', $collab->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="padding: 2px 6px; font-size: 11px;" onclick="return confirm('Hapus anggota ini?')">x</button>
                            </form>
                        </li>
                    @endforeach
                </ul>

                <form action="{{ route('collaborators.store', $task->id) }}" method="POST" style="margin-top: 8px;">
                    @csrf
                    <select name="user_id" required style="padding: 4px; font-size: 12px;">
                        <option value="">Pilih Anggota</option>
                        @foreach(\App\Models\User::all() as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" style="padding: 4px 8px; font-size: 12px;">Tambah Anggota</button>
                </form>
            </div>

            {{-- TOMBOL --}}
            <div class="task-actions">

                {{-- EDIT --}}
                <button
                    type="button"
                    onclick="document.getElementById('edit-{{ $task->id }}').style.display='block';"
                >
                    Edit
                </button>

                {{-- HAPUS --}}
                <form
                    action="{{ route('tasks.destroy', $task) }}"
                    method="POST"
                    style="display: inline;"
                >
                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        onclick="return confirm('Yakin ingin menghapus tugas ini?')"
                    >
                        Hapus
                    </button>
                </form>

            </div>

            {{-- FORM EDIT --}}
            <div
                id="edit-{{ $task->id }}"
                class="edit-form"
            >

                <form
                    action="{{ route('tasks.update', $task) }}"
                    method="POST"
                >
                    @csrf
                    @method('PUT')

                    <label>Judul Tugas:</label>
                    <input
                        type="text"
                        name="title"
                        value="{{ $task->title }}"
                        required
                    >

                    <label>Deskripsi:</label>
                    <textarea
                        name="description"
                        rows="3"
                    >{{ $task->description }}</textarea>

                    <label>Prioritas:</label>
                    <select name="priority" required>
                        <option
                            value="Low"
                            {{ $task->priority === 'Low' ? 'selected' : '' }}
                        >
                            Low
                        </option>

                        <option
                            value="Medium"
                            {{ $task->priority === 'Medium' ? 'selected' : '' }}
                        >
                            Medium
                        </option>

                        <option
                            value="High"
                            {{ $task->priority === 'High' ? 'selected' : '' }}
                        >
                            High
                        </option>
                    </select>

                    <label>Deadline:</label>
                    <input
                        type="datetime-local"
                        name="deadline"
                        value="{{ $task->deadline ? \Carbon\Carbon::parse($task->deadline)->format('Y-m-d\TH:i') : '' }}"
                    >

                    <button type="submit">
                        Simpan Perubahan
                    </button>

                    <button
                        type="button"
                        class="cancel-button"
                        onclick="document.getElementById('edit-{{ $task->id }}').style.display='none';"
                    >
                        Batal
                    </button>

                </form>

            </div>

        </div>

    @empty

        <p>Belum ada tugas.</p>

    @endforelse

    <a href="{{ route('dashboard') }}" class="back">
        ← Kembali ke Dashboard
    </a>

</div>

</body>
</html>