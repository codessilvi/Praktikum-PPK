@extends('layouts.app')

@section('title', 'Edit Tugas')

@section('content')
    <nav>
        <strong>Edit Tugas</strong>
        <a href="{{ route('dashboard') }}">Kembali</a>
    </nav>

    <div style="margin-top: 20px;">
        <form action="{{ route('tasks.update', $task->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div style="margin-bottom: 10px;">
                <label>Judul Tugas:</label><br>
                <input type="text" name="title" value="{{ old('title', $task->title) }}" required style="width: 100%; padding: 8px;">
            </div>

            <div style="margin-bottom: 10px;">
                <label>Deskripsi:</label><br>
                <textarea name="description" style="width: 100%; padding: 8px;">{{ old('description', $task->description) }}</textarea>
            </div>

            <div style="margin-bottom: 10px;">
                <label>Prioritas:</label><br>
                <select name="priority" required style="width: 100%; padding: 8px;">
                    <option value="Low" {{ $task->priority == 'Low' ? 'selected' : '' }}>Low</option>
                    <option value="Medium" {{ $task->priority == 'Medium' ? 'selected' : '' }}>Medium</option>
                    <option value="High" {{ $task->priority == 'High' ? 'selected' : '' }}>High</option>
                </select>
            </div>

            <div style="margin-bottom: 15px;">
                <label>Deadline:</label><br>
                <input type="datetime-local" name="deadline" value="{{ $task->deadline ? \Carbon\Carbon::parse($task->deadline)->format('Y-m-d\TH:i') : '' }}" style="width: 100%; padding: 8px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label>Tambah Anggota Kolaborasi (Email/User):</label><br>
                <select name="collaborators[]" multiple style="width: 100%; padding: 8px; height: 80px;">
                    @foreach(\App\Models\User::where('id', '!=', auth()->id())->get() as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
                <small style="color: #666;">Tahan Ctrl/Cmd untuk memilih lebih dari satu.</small>
            </div>

            <button type="submit" style="padding: 10px 15px; background: #ffc107; color: black; border: none; cursor: pointer;">Perbarui Tugas</button>
        </form>
    </div>
@endsection