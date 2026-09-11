<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;

class TaskCollaborationController extends Controller
{
    // Menambahkan anggota ke task (SRS-005)
    public function addMember(Request $request, $taskId)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $task = Task::findOrFail($taskId);
        $task->users()->syncWithoutDetaching([$request->user_id]);

        return back()->with('success', 'Anggota berhasil ditambahkan.');
    }

    // Menghapus anggota dari task (SRS-005)
    public function removeMember($taskId, $userId)
    {
        $task = Task::findOrFail($taskId);
        $task->users()->detach($userId);

        return back()->with('success', 'Anggota berhasil dihapus.');
    }

    // Menandai tugas sebagai selesai & melihat progress (SRS-006)
    public function toggleComplete($taskId)
    {
        $task = Task::findOrFail($taskId);
        $task->is_completed = !$task->is_completed; // Asumsi ada kolom is_completed atau status
        $task->save();

        return back()->with('success', 'Status tugas diperbarui.');
    }

    // Menghitung progress penyelesaian tugas (SRS-006)
    public function getProgress($taskId)
    {
        $task = Task::findOrFail($taskId);
        
        // Asumsi progress dihitung secara global atau per list/user, 
        // di sini kita ambil contoh perhitungan total task vs completed task
        $totalTaskCount = Task::count();
        $completedCount = Task::where('is_completed', true)->count();
        
        $progressPercentage = $totalTaskCount > 0 ? ($completedCount / $totalTaskCount) * 100 : 0;

        return response()->json([
            'totalTaskCount' => $totalTaskCount,
            'completedCount' => $completedCount,
            'progressPercentage' => round($progressPercentage, 2)
        ]);
    }
}