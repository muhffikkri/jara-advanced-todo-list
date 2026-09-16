<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;

class TaskController extends Controller
{
    public function store(StoreTaskRequest $request, int $list): RedirectResponse
    {
        $this->authorize('create', [Task::class, $list]);

        Task::create([
            ...$request->validated(),
            'list_id' => $list,
        ]);

        return back()->with('status', 'Task created successfully.');
    }

    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $this->authorize('update', $task);

        $task->update($request->validated());

        return back()->with('status', 'Task updated successfully.');
    }

    public function toggle(Task $task): RedirectResponse
    {
        $this->authorize('update', $task);

        $isCompleted = ! $task->is_completed;

        $task->update([
            'is_completed' => $isCompleted,
            'completed_at' => $isCompleted ? now() : null,
        ]);

        return back()->with('status', 'Task status updated successfully.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $this->authorize('delete', $task);

        $task->delete();

        return back()->with('status', 'Task deleted successfully.');
    }
}
