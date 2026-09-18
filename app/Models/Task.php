<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TaskList;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id',
    'task_list_id',
    'title',
    'description',
    'priority',
    'deadline',
    'status',
];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function taskList()
    {
        return $this->belongsTo(TaskList::class);
    }
    public function collaborators()
    {
        return $this->belongsToMany(User::class, 'collaborators', 'task_id', 'user_id');
    }
}