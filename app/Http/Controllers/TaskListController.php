<?php

namespace App\Http\Controllers;

use App\Models\TaskList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskListController extends Controller
{
    public function destroy(TaskList $taskList)
    {
        // Pastikan hanya owner yang boleh menghapus task list
        if ($taskList->user_id !== auth()->id()) {
            abort(403);
        }

        DB::transaction(function () use ($taskList) {
            // Hapus semua task di dalam list
            $taskList->tasks()->delete();

            // Hapus task list
            $taskList->delete();
        });

        return redirect()
            ->route('dashboard')
            ->with('success', 'Task list berhasil dihapus!');
    }
}