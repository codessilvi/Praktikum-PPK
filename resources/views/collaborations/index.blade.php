<div class="card p-4 mt-4">
    <h3 class="text-lg font-bold mb-2">Kolaborasi Tim & Progress</h3>
    
    <!-- Indikator Progress (SRS-006) -->
    <div class="mb-4">
        <span class="text-sm text-gray-600">Progress Penyelesaian</span>
        <div class="w-full bg-gray-200 rounded-full h-2.5 mt-1">
            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $progressPercentage ?? 0 }}%"></div>
        </div>
        <span class="text-xs text-gray-500 mt-1 inline-block">{{ $completedCount ?? 0 }} dari {{ $totalTaskCount ?? 0 }} tugas selesai</span>
    </div>

    <!-- Daftar Anggota (SRS-005) -->
    <div class="mb-4">
        <h4 class="font-semibold text-sm mb-2">Anggota Tergabung:</h4>
        <ul class="list-disc pl-5 text-sm">
            @forelse($task->users ?? [] as $member)
                <li class="flex justify-between items-center py-1">
                    <span>{{ $member->name }}</span>
                    <form action="{{ route('tasks.members.remove', [$task->id ?? 1, $member->id]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 text-xs hover:underline">Hapus</button>
                    </form>
                </li>
            @empty
                <li class="text-gray-400 text-sm">Belum ada anggota kolaborasi.</li>
            @endforelse
        </ul>
    </div>

    <!-- Form Tambah Anggota (SRS-005) -->
    <form action="{{ route('tasks.members.add', $task->id ?? 1) }}" method="POST" class="flex gap-2">
        @csrf
        <select name="user_id" class="border rounded px-2 py-1 text-sm flex-1">
            <option value="">Pilih Pengguna...</option>
            @foreach($allUsers ?? [] as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">Tambah</button>
    </form>
</div>