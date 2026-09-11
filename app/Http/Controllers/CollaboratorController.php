<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Collaborator;
use App\Models\User;

class CollaboratorController extends Controller
{
    // Menambahkan anggota ke suatu task/proyek
    public function store(Request $request, $taskId)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        Collaborator::create([
            'task_id' => $taskId,
            'user_id' => $request->user_id,
        ]);

        return back()->with('success', 'Anggota berhasil ditambahkan.');
    }

    // Menghapus anggota dari task/proyek
    public function destroy($id)
    {
        $collaborator = Collaborator::findOrFail($id);
        $collaborator->delete();

        return back()->with('success', 'Anggota berhasil dihapus.');
    }
}