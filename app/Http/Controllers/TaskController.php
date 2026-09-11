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
        $tasks = Task::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('tasks.index', compact('tasks'));
    }

    // SRS-003: Simpan Task Baru
    public function store(Request $request)
    {
        $request->validate([
            'title'    => 'required|string|max:255',
            'priority' => 'required|in:Low,Medium,High',
            'deadline' => 'nullable|date',
        ]);

        Task::create([
            'user_id'     => Auth::id(),
            'title'       => $request->title,
            'description' => $request->description,
            'priority'    => $request->priority,
            'deadline'    => $request->deadline,
        ]);

        return redirect()->back()->with('success', 'Task berhasil ditambahkan!');
    }

    // SRS-003: Update Task
    public function update(Request $request, Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title'    => 'required|string|max:255',
            'priority' => 'required|in:Low,Medium,High',
            'deadline' => 'nullable|date',
        ]);

        $task->update(
            $request->only(['title', 'description', 'priority', 'deadline'])
        );

        return redirect()->back()->with('success', 'Task berhasil diperbarui!');
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
}