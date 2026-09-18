<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // SRS-002: menampilkan daftar tugas milik user yang login
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $tasks = Task::where(function($query) use ($userId) {
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
                ->latest()
                ->get();

        return view('tasks.index', [
            'tasks' => $tasks,
        ]);
    }
}