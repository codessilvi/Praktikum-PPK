<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // SRS-003: Menampilkan daftar task
   public function index()
    {
        $userId = auth()->id();
        
        // Mengambil task milik user sendiri atau tugas kolaborasi
        $tasks = \App\Models\Task::where(function($query) use ($userId) {
                    if (\Schema::hasColumn('tasks', 'user_id')) {
                        $query->orWhere('user_id', $userId);
                    }
                    if (\Schema::hasColumn('tasks', 'created_by')) {
                        $query->orWhere('created_by', $userId);
                    }
                })
                ->orWhereHas('collaborators', function($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->get();

        return view('dashboard', compact('tasks'));
    }

// SRS-003: Simpan Task Baru
    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'priority'     => 'required|in:Low,Medium,High',
            'deadline'     => 'nullable|date',
            'collaborators' => 'nullable|array',
        ]);

        $task = Task::create([
            'user_id'     => Auth::id(),
            'title'       => $request->title,
            'description' => $request->description,
            'priority'    => $request->priority,
            'deadline'    => $request->deadline,
        ]);

        // Simpan anggota kolaborasi jika dipilih
        if ($request->has('collaborators')) {
            $task->collaborators()->sync($request->collaborators);
        }

        return redirect()->route('dashboard')->with('success', 'Task berhasil ditambahkan!');
    }

// SRS-003: Update Task
    public function update(Request $request, Task $task)
    {
        $isOwner = $task->user_id === Auth::id();
        $isCollaborator = $task->collaborators()->where('user_id', Auth::id())->exists();

        if (!$isOwner && !$isCollaborator) {
            abort(403);
        }

        $request->validate([
            'title'        => 'required|string|max:255',
            'priority'     => 'required|in:Low,Medium,High',
            'deadline'     => 'nullable|date',
            'collaborators' => 'nullable|array',
        ]);

        $task->update(
            $request->only(['title', 'description', 'priority', 'deadline'])
        );

        // Perbarui anggota kolaborasi
        $task->collaborators()->sync($request->input('collaborators', []));

        return redirect()->route('dashboard')->with('success', 'Task berhasil diperbarui!');
    }

    // SRS-003: Hapus Task
    public function destroy(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        $task->delete();

        return redirect()->back()->with('success', 'Task berhasil dihapus!');
    }
    public function complete(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        $task->update([
            'status' => 'selesai',
        ]);

        return redirect()->back()->with('success', 'Task berhasil diselesaikan!');
    }
    public function toggleStatus($id)
    {
        $task = Task::findOrFail($id);
        $isOwner = $task->user_id === Auth::id();
        $isCollaborator = $task->collaborators()->where('user_id', Auth::id())->exists();

        if (!$isOwner && !$isCollaborator) {
            abort(403);
        }

        $task->is_completed = !$task->is_completed;
        $task->save();

        return back()->with('success', 'Status task berhasil diperbarui.');
    }

    // Menampilkan form tambah task
    public function create()
    {
        return view('tasks.create');
    }

    public function edit(Task $task)
    {
        // Cek hak akses apakah user pembuat atau kolaborator
        $isOwner = $task->user_id === Auth::id();
        $isCollaborator = $task->collaborators()->where('user_id', Auth::id())->exists();

        if (!$isOwner && !$isCollaborator) {
            abort(403);
        }

        return view('tasks.edit', compact('task'));
    }

}