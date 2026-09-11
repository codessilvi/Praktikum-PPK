<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // SRS-002: menampilkan daftar tugas milik user yang login
    public function index(Request $request)
    {
        $user = $request->user();

        $tasks = Task::where('user_id', $user->id)
            ->latest()
            ->get();

        return view('dashboard', [
            'tasks' => $tasks,
        ]);
    }
}