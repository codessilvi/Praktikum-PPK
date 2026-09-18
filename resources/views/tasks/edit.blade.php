@extends('layouts.app')

@section('title', 'Edit Tugas')

@section('content')
    <nav style="margin-bottom: 20px;">
        <strong>Edit Tugas</strong> | 
        <a href="{{ route('tasks.index') }}" style="text-decoration: none; color: #007bff;">Kembali ke Daftar</a>
    </nav>

    <div style="background: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 5px; max-width: 600px;">
        <form action="{{ route('tasks.update', $task->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div style="margin-bottom: 15px;">
                <label style="font-weight: bold;">Judul Tugas:</label><br>
                <input type="text" name="title" value="{{ old('title', $task->title) }}" required style="width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="font-weight: bold;">Deskripsi:</label><br>
                <textarea name="description" rows="4" style="width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;">{{ old('description', $task->description) }}</textarea>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="font-weight: bold;">Prioritas:</label><br>
                <select name="priority" required style="width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="Low" {{ $task->priority == 'Low' ? 'selected' : '' }}>Low</option>
                    <option value="Medium" {{ $task->priority == 'Medium' ? 'selected' : '' }}>Medium</option>
                    <option value="High" {{ $task->priority == 'High' ? 'selected' : '' }}>High</option>
                </select>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="font-weight: bold;">Deadline:</label><br>
                <input type="datetime-local" name="deadline" value="{{ $task->deadline ? \Carbon\Carbon::parse($task->deadline)->format('Y-m-d\TH:i') : '' }}" style="width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="font-weight: bold;">Ubah Anggota Kolaborasi:</label><br>
                {{-- Data $users di-pass dari TaskController --}}
                {{-- Data $taskCollaborators adalah array ID user yang sudah menjadi kolaborator tugas ini --}}
                <select name="collaborators[]" multiple style="width: 100%; padding: 10px; margin-top: 5px; height: 100px; border: 1px solid #ccc; border-radius: 4px;">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ in_array($user->id, $taskCollaborators ?? []) ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                <small style="color: #666; display: block; margin-top: 5px;">Tahan Ctrl/Cmd untuk memilih lebih dari satu anggota.</small>
            </div>

            <button type="submit" style="padding: 10px 20px; background: #ffc107; color: black; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Perbarui Tugas</button>
        </form>
    </div>
@endsection
