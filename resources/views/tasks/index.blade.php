{{-- resources/views/tasks/index.blade.php --}}
<div class="task-section">
    <h2>Daftar Tugas</h2>

    <!-- Form Tambah Task (SRS-003 & SRS-004) -->
    <form action="{{ route('tasks.store') }}" method="POST" class="mb-4">
        @csrf
        <input type="text" name="title" placeholder="Judul tugas..." required>
        <textarea name="description" placeholder="Deskripsi..."></textarea>
        
        <select name="priority">
            <option value="Low">Low</option>
            <option value="Medium" selected>Medium</option>
            <option value="High">High</option>
        </select>
        
        <input type="datetime-local" name="deadline">
        
        <button type="submit">Tambah Task</button>
    </form>

    <!-- Loop Daftar Task -->
    <div class="task-list">
        @forelse($tasks as $task)
            <div class="task-card">
                <h4>{{ $task->title }}</h4>
                <p>{{ $task->description }}</p>
                
                <!-- Badge Priority -->
                <span class="badge priority-{{ strtolower($task->priority) }}">
                    {{ $task->priority }}
                </span>

                <!-- Deadline -->
                @if($task->deadline)
                    <small>Deadline: {{ \Carbon\Carbon::parse($task->deadline)->format('d M Y H:i') }}</small>
                @endif

                <!-- Action Button Hapus -->
                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Yakin hapus?')">Hapus</button>
                </form>
            </div>
        @empty
            <p>Belum ada tugas.</p>
        @endforelse
    </div>
</div>