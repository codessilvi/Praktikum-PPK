use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskCollaborationController;

Route::middleware(['auth'])->group(function () {
    Route::post('/tasks/{task}/members', [TaskCollaborationController::class, 'addMember'])->name('tasks.members.add');
    Route::delete('/tasks/{task}/members/{user}', [TaskCollaborationController::class, 'removeMember'])->name('tasks.members.remove');
    Route::patch('/tasks/{task}/toggle', [TaskCollaborationController::class, 'toggleComplete'])->name('tasks.toggle');
});