public function users()
{
    return $this->belongsToMany(User::class, 'task_user');
}