<!-- resources/views/components/progress.blade.php -->
@php
    $totalTasks = $list->tasks->count();
    $completedTasks = $list->tasks->where('is_completed', true)->count();
    $percentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
@endphp

<div style="margin-bottom: 20px;">
    <strong>Progress Tugas:</strong>
    <div style="width: 100%; background-color: #e0e0e0; border-radius: 5px; margin-top: 5px;">
        <div style="width: {{ $percentage }}%; background-color: #4CAF50; height: 20px; border-radius: 5px; text-align: center; color: white; line-height: 20px; font-size: 12px;">
            {{ $percentage }}%
        </div>
    </div>
    <small>{{ $completedTasks }} dari {{ $totalTasks }} tugas selesai</small>
</div>