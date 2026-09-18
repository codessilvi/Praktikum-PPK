<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    /**
     * SRS-003: Menampilkan daftar task milik user atau task kolaborasi.
     */
    public function index()
    {
        $userId = Auth::id();

        

        // Mengambil task di mana user adalah Owner ATAU sebagai Collaborator
         $tasks = Task::orderByRaw("
                CASE priority
                    WHEN 'High' THEN 1
                    WHEN 'Medium' THEN 2
                    WHEN 'Low' THEN 3
                    ELSE 4
                END
            ")
            ->orderBy('deadline', 'asc')
            ->get();

        return view('tasks.index', compact('tasks'));
    }

    /**
     * SRS-003: Menampilkan form tambah task.
     */
    public function create()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        return view('tasks.create', compact('users'));
    }

    /**
     * SRS-003, SRS-004: Simpan Task Baru (Transaksi Atomik & Parameterized Validated).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'priority'      => 'required|in:Low,Medium,High',
            'deadline'      => 'nullable|date',
            'collaborators' => 'nullable|array',
            'collaborators.*' => 'exists:users,id',
        ]);

        // Dibungkus DB::transaction agar proses atomik (SRS & Keamanan)
        DB::transaction(function () use ($validated, $request) {
            $task = Task::create([
                'user_id'     => Auth::id(), // Otomatis menjadi Pemilik (Owner)
                'title'       => $validated['title'],
                'description' => $validated['description'] ?? null,
                'priority'    => $validated['priority'],
                'deadline'    => $validated['deadline'] ?? null,
            ]);

            if (!empty($validated['collaborators'])) {
                $task->collaborators()->sync($validated['collaborators']);
            }
        });

        return redirect()->route('dashboard')->with('success', 'Tugas berhasil ditambahkan!');
    }

    /**
     * SRS-003: Menampilkan form edit task.
     */
    public function edit(Task $task)
    {
        $this->authorizeTaskAccess($task);

        $users = User::where('id', '!=', Auth::id())->get();
        $taskCollaborators = $task->collaborators()->pluck('user_id')->toArray();

        return view('tasks.edit', compact('task', 'users', 'taskCollaborators'));
    }

    /**
     * SRS-003, SRS-004: Update Detail Task.
     */
    public function update(Request $request, Task $task)
    {
        $this->authorizeTaskAccess($task);

        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'priority'      => 'required|in:Low,Medium,High',
            'deadline'      => 'nullable|date',
            'collaborators' => 'nullable|array',
            'collaborators.*' => 'exists:users,id',
        ]);

        DB::transaction(function () use ($task, $validated) {
            $task->update([
                'title'       => $validated['title'],
                'description' => $validated['description'] ?? null,
                'priority'    => $validated['priority'],
                'deadline'    => $validated['deadline'] ?? null,
            ]);

            // Sync anggota kolaborasi (hanya Owner yang bisa atap/hapus anggota jika diinginkan)
            $task->collaborators()->sync($validated['collaborators'] ?? []);
        });

        return redirect()->route('dashboard')->with('success', 'Tugas berhasil diperbarui!');
    }

    /**
     * SRS-003: Hapus Task Individu.
     */
    public function destroy(Task $task)
    {
        // Pastikan hanya pembuat task yang bisa hapus
        if ((int) $task->user_id !== (int) auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus tugas ini.');
        }

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Tugas berhasil dihapus.');
    }

    /**
     * SRS-006: Toggle Status Selesai / Belum Selesai (Bisa oleh Owner maupun Member).
     */
    public function toggleStatus($id)
    {
        $task = Task::findOrFail($id);

        $this->authorizeTaskAccess($task);

        $task->status = $task->status === 'selesai' ? 'belum selesai' : 'selesai';
        $task->save();

        return redirect()->back()->with('success', 'Status tugas berhasil diperbarui.');
    }

    /**
     * Helper Method: Memastikan User adalah Owner ATAU Member Kolaborasi dari Task ini.
     */
    private function authorizeTaskAccess(Task $task): void
    {
        $userId = Auth::id();

        // 1. Cek apakah user adalah Owner (pembuat tugas)
        $isOwner = (int) $task->user_id === (int) $userId;

        // 2. Cek apakah user adalah Collaborator
        // Menggunakan exists() langsung pada relasi
        $isCollaborator = $task->collaborators()
            ->where('users.id', $userId) // Explicit tabel users.id untuk hindari ambiguitas SQL
            ->exists();

        if (!$isOwner && !$isCollaborator) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini.');
        }
    }
}