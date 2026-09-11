{{-- resources/views/tasks/create.blade.php --}}
@extends('layouts.app') {{-- Atau layout utama kelompokmu --}}

@section('content')
    <div style="max-width: 500px; margin: 20px auto;">
        <h2>Tambah Tugas Baru</h2>

        <form action="{{ route('tasks.store') }}" method="POST">
            @csrf
            
            <div style="margin-bottom: 15px;">
                <label style="display:block;">Judul Task:</label>
                <input type="text" name="title" required placeholder="Masukkan judul tugas..." style="width: 100%; padding: 8px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display:block;">Deskripsi:</label>
                <textarea name="description" placeholder="Deskripsi tugas..." style="width: 100%; padding: 8px;"></textarea>
            </div>

            <!-- SRS-004: Priority -->
            <div style="margin-bottom: 15px;">
                <label style="display:block;">Prioritas:</label>
                <select name="priority" style="width: 100%; padding: 8px;">
                    <option value="Low">Low</option>
                    <option value="Medium" selected>Medium</option>
                    <option value="High">High</option>
                </select>
            </div>

            <!-- SRS-004: Deadline -->
            <div style="margin-bottom: 15px;">
                <label style="display:block;">Deadline:</label>
                <input type="datetime-local" name="deadline" style="width: 100%; padding: 8px;">
            </div>

            <button type="submit" style="padding: 8px 16px; cursor: pointer;">Simpan Tugas</button>
            <a href="{{ route('dashboard') }}" style="margin-left: 10px;">Batal</a>
        </form>
    </div>
@endsection