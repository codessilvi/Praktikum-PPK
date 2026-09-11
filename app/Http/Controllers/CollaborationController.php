<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
// PENTING: Sama seperti tadi, sesuaikan 'TodoList' dengan nama model list dari kelompokmu
use App\Models\TodoList; 

class CollaborationController extends Controller
{
    // Menambahkan anggota ke dalam list tugas
    public function addMember(Request $request, $id)
    {
        // Validasi: pastikan yang diinput adalah email
        $request->validate([
            'email' => 'required|email'
        ]);

        $list = TodoList::findOrFail($id);
        
        // Cari user berdasarkan email yang diinput
        $user = User::where('email', $request->email)->first();

        // Kalau user-nya tidak ada di database
        if (!$user) {
            return back()->with('error', 'Pengguna dengan email tersebut tidak ditemukan!');
        }

        // Cek apakah user sudah ada di dalam list (biar nggak dobel)
        if (!$list->members->contains($user->id)) {
            // Masukkan user ke tabel perantara (attach)
            $list->members()->attach($user->id);
            return back()->with('success', 'Anggota berhasil ditambahkan ke list!');
        }

        return back()->with('error', 'Pengguna tersebut sudah menjadi anggota di list ini.');
    }

    // Menghapus anggota dari list tugas
    public function removeMember($id, $userId)
    {
        $list = TodoList::findOrFail($id);
        
        // Hapus relasi user dari tabel perantara (detach)
        $list->members()->detach($userId);

        return back()->with('success', 'Anggota berhasil dihapus dari list!');
    }
}