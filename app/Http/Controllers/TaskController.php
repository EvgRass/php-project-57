<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Task::class);
    }

    public function index()
    {
        $tasks = Task::orderBy('id')->with(['status', 'createdBy'])->paginate(15);
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $task = new Task();
        $taskStatuses = TaskStatus::orderBy('id')->pluck('name', 'id')->all();
        $users = User::pluck('name', 'id')->all();
        return view('tasks.create', compact('task', 'taskStatuses', 'users'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!isset($user)) {
                throw new \Exception('User is not authenticated');
        }

        $data = $this->validate($request, [
            'name' => 'required',
            'status_id' => 'required',
            'assigned_to_id' => 'nullable',
            'description' => 'nullable'
        ]);

        $task = $user->tasks()->make($data);
        $task->save();

        flash(__('messages.Task added successfully!'))->success();

        return redirect()->route('tasks.index');
    }

    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $taskStatuses = TaskStatus::orderBy('id')->pluck('name', 'id')->all();
        $users = User::pluck('name', 'id')->all();

        $taskStatusesSelected = $task->status->id ?? null;
        $userAssignedToSelected = $task->assignedTo->id ?? null;

        return view('tasks.edit', compact(  'task', 
                                            'taskStatuses', 
                                            'users', 
                                            'taskStatusesSelected', 
                                            'userAssignedToSelected'
                                        ));
    }

    public function update(Request $request, Task $task)
    {
        $data = $this->validate($request, [
            'name' => 'required',
            'status_id' => 'required',
            'assigned_to_id' => 'nullable',
            'description' => 'nullable'
        ]);

        $task->fill($data);
        $task->save();
    
        flash(__('messages.Task edited successfully!'))->success();
    
        return redirect()->route('tasks.index');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        flash(__('messages.Task deleted successfully!'))->success();
    
        return redirect()->route('tasks.index');
    }
}
