<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
// PENTING: Sesuaikan 'TodoList' dengan nama file model dari kelompokmu, 
// bisa jadi namanya cuma 'TodoList' atau 'Board'
use App\Models\TodoList; 

class TaskStatusController extends Controller
{
    // Mengubah status tugas (Selesai/Belum)
    public function updateStatus(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        
        // Kebalikan dari status sekarang (kalau belum selesai jadi selesai, dst)
        $task->is_completed = !$task->is_completed; 
        $task->save();

        // Kembali ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Status tugas berhasil diupdate!');
    }

    // Menghitung progress persentase (SRS-006)
    public function getProgress($id)
    {
        // Cari list berdasarkan ID
        $list = TodoList::findOrFail($id); 
        
        // Hitung total tugas dan tugas yang selesai
        $totalTasks = $list->tasks()->count();
        $completedTasks = $list->tasks()->where('is_completed', true)->count();

        // Hitung persentase (cegah error pembagian dengan nol)
        $percentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        // Nanti datanya bisa dipakai di file tampilan (Blade)
        return back()->with([
            'percentage' => $percentage,
            'completedTasks' => $completedTasks,
            'totalTasks' => $totalTasks
        ]);
    }
}