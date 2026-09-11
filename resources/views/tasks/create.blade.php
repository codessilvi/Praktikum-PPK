@extends('layouts.app')

@section('title', 'Tambah Tugas')

@section('content')
    <nav>
        <strong>Tambah Tugas Baru</strong>
        <a href="{{ route('dashboard') }}">Kembali</a>
    </nav>

    <div style="margin-top: 20px;">
        <form action="{{ route('tasks.store') }}" method="POST">
            @csrf
            
            <div style="margin-bottom: 10px;">
                <label>Judul Tugas:</label><br>
                <input type="text" name="title" required style="width: 100%; padding: 8px;">
            </div>

            <div style="margin-bottom: 10px;">
                <label>Deskripsi:</label><br>
                <textarea name="description" style="width: 100%; padding: 8px;"></textarea>
            </div>

            <div style="margin-bottom: 10px;">
                <label>Prioritas:</label><br>
                <select name="priority" required style="width: 100%; padding: 8px;">
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                </select>
            </div>

            <div style="margin-bottom: 15px;">
                <label>Deadline:</label><br>
                <input type="datetime-local" name="deadline" style="width: 100%; padding: 8px;">
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

            <button type="submit" style="padding: 10px 15px; background: #28a745; color: white; border: none; cursor: pointer;">Simpan Tugas</button>
        </form>
    </div>
@endsection