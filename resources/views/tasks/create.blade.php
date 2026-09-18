@extends('layouts.app')

@section('title', 'Tambah Tugas')

@section('content')
    <nav style="margin-bottom: 20px;">
        <strong>Tambah Tugas Baru</strong> | 
        <a href="{{ route('tasks.index') }}" style="text-decoration: none; color: #007bff;">Kembali ke Daftar</a>
    </nav>

    <div style="background: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 5px; max-width: 600px;">
        <form action="{{ route('tasks.store') }}" method="POST">
            @csrf
            
            <div style="margin-bottom: 15px;">
                <label style="font-weight: bold;">Judul Tugas:</label><br>
                <input type="text" name="title" required style="width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;" placeholder="Masukkan judul tugas">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="font-weight: bold;">Deskripsi:</label><br>
                <textarea name="description" rows="4" style="width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;" placeholder="Tambahkan deskripsi..."></textarea>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="font-weight: bold;">Prioritas:</label><br>
                <select name="priority" required style="width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="Low">Low</option>
                    <option value="Medium" selected>Medium</option>
                    <option value="High">High</option>
                </select>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="font-weight: bold;">Deadline:</label><br>
                <input type="datetime-local" name="deadline" style="width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="font-weight: bold;">Tambah Anggota Kolaborasi:</label><br>
                {{-- Data $users di-pass dari TaskController: User::where('id', '!=', auth()->id())->get() --}}
                <select name="collaborators[]" multiple style="width: 100%; padding: 10px; margin-top: 5px; height: 100px; border: 1px solid #ccc; border-radius: 4px;">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
                <small style="color: #666; display: block; margin-top: 5px;">Tahan Ctrl/Cmd untuk memilih lebih dari satu anggota.</small>
            </div>

            <button type="submit" style="padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Simpan Tugas</button>
        </form>
    </div>
@endsection
