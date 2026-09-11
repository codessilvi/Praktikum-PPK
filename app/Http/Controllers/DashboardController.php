<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // SRS-002: menampilkan daftar tugas milik user yang login
    public function index(Request $request)
    {
        $user = $request->user();

        // [SESUAIKAN] Ini masih data dummy. Begitu Model Task dari Orang 2 jadi,
        // ganti bagian ini jadi query beneran, contoh:
        //
        //   $tasks = Task::where('user_id', $user->id)->latest()->get();
        //
        // Sesuaikan juga:
        // - nama tabel & kolom (title, status, priority, deadline) harus sama
        //   persis dengan migration yang dibuat Orang 2
        // - relasi: apakah task langsung punya user_id, atau lewat tabel
        //   list/list_members dulu (tergantung desain akhir Orang 3 juga,
        //   karena SRS-005 soal kolaborasi/anggota list)
        $tasks = [
            ['title' => 'Contoh tugas 1', 'status' => 'belum selesai'],
            ['title' => 'Contoh tugas 2', 'status' => 'selesai'],
        ];

        return view('dashboard', [
            'tasks' => $tasks,
        ]);
    }
}
