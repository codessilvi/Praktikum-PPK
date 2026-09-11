<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // SRS-002: menampilkan daftar tugas milik user yang login
    public function index(Request $request)
    {
        $user = $request->user();

        $tasks = [
            ['title' => 'Tugas PPK', 'status' => 'belum selesai'],
            ['title' => 'Tugas Metode Numerik', 'status' => 'selesai'],
        ];

        return view('dashboard', [
            'tasks' => $tasks,
        ]);
    }
}
