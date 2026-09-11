<!-- resources/views/components/task-status.blade.php -->
<div style="display: flex; align-items: center; margin-bottom: 10px;">
    
    <!-- Tombol Centang Status -->
    <form action="/tasks/{{ $task->id }}/status" method="POST" style="margin-right: 10px;">
        @csrf
        @method('PATCH')
        <button type="submit" style="border: none; background: none; cursor: pointer; font-size: 18px;">
            @if($task->is_completed)
                ✅
            @else
                ⬜
            @endif
        </button>
    </form>

    <!-- Judul Tugas (Dicoret kalau selesai) -->
    <span style="{{ $task->is_completed ? 'text-decoration: line-through; color: #888;' : '' }}">
        {{ $task->title }}
    </span>
</div>