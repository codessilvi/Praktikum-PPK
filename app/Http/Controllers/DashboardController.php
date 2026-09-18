<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskList;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        // Ambil task milik user atau task yang melibatkan user sebagai collaborator
        $tasks = Task::where(function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->orWhereHas('collaborators', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->latest()
                ->get();

        // Ambil task list milik user
        $taskLists = TaskList::where('user_id', $userId)
            ->with('tasks')
            ->latest()
            ->get();

        return view('dashboard', [
            'tasks' => $tasks,
            'taskLists' => $taskLists,
        ]);
    }
}