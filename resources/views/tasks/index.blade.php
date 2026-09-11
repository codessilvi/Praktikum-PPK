{{-- resources/views/tasks/index.blade.php --}}

<div style="margin-bottom: 15px;">
    <!-- Link mengarahkan ke halaman form tambah task -->
    <a href="{{ route('tasks.create') }}" style="text-decoration: underline; color: purple; font-weight: bold;">
        + Tambah Tugas
    </a>
</div>

<!-- List Task -->
@forelse ($tasks as $task)
    @php
        $item = (array) $task;
        $title = $item['title'] ?? '-';
        $priority = $item['priority'] ?? 'Low';
        $deadline = $item['deadline'] ?? null;
        $status = $item['status'] ?? 'belum selesai';
        $id = $item['id'] ?? null;
    @endphp

    <div class="task-box" style="border: 1px solid #ccc; border-radius: 4px; padding: 12px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <span style="{{ $status === 'selesai' ? 'text-decoration: line-through; color: #888;' : '' }}">
                <strong>{{ $title }}</strong> — <em>{{ $status }}</em>
            </span>

            <!-- Badge Priority & Deadline (SRS-004) -->
            <div style="font-size: 0.85em; margin-top: 4px; color: #555;">
                <span style="background: #eee; padding: 2px 6px; border-radius: 3px;">
                    Prioritas: {{ $priority }}
                </span>
                @if($deadline)
                    <span style="margin-left: 8px;">📅 {{ \Carbon\Carbon::parse($deadline)->format('d M Y H:i') }}</span>
                @endif
            </div>
        </div>

        @if($id)
            <form action="{{ route('tasks.destroy', $id) }}" method="POST" style="margin: 0;">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Hapus task ini?')" style="color: red; border: none; background: none; cursor: pointer;">Hapus</button>
            </form>
        @endif
    </div>
@empty
    <p>Belum ada tugas.</p>
@endforelse