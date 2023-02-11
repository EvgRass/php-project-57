<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\Label;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Task::class);
    }

    public function index(Request $request)
    {
        $query = Task::query()
            ->withCount('tasks')
            ->with(['status', 'createdBy', 'assignedTo']);

        $tasks = QueryBuilder::for($query)
            ->allowedFilters([
                AllowedFilter::exact('status_id'),
                AllowedFilter::exact('created_by_id'),
                AllowedFilter::exact('assigned_to_id'),
            ])
            ->orderBy('id')
            ->paginate(15);

        $taskStatuses = TaskStatus::pluck('name', 'id')->all();
        $usersCreated = User::pluck('name', 'id')->all();
        $usersAssigned = User::pluck('name', 'id')->all();

        $filter = $request->get('filter');

        return view('tasks.index', compact('tasks', 'taskStatuses', 'usersAssigned', 'usersCreated', 'filter'));
    }

    public function create()
    {
        $task = new Task();
        $taskStatuses = TaskStatus::orderBy('id')->pluck('name', 'id')->all();
        $users = User::pluck('name', 'id')->all();
        $labels = Label::orderBy('id')->pluck('name', 'id')->all();
        return view('tasks.create', compact('task', 'taskStatuses', 'users', 'labels'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!isset($user)) {
                throw new \Exception('User is not authenticated');
        }

        $data = $this->validate($request, [
            'name' => 'required|unique:tasks,name',
            'status_id' => 'required',
            'assigned_to_id' => 'nullable',
            'description' => 'nullable'
        ], ['unique' => __('messages.Task exists')]);

        $task = $user->tasks()->make($data);
        $task->save();
        $task->labels()->sync($request->labels);

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
        $labels = Label::pluck('name', 'id')->all();
        $labelsSelected = $task->labels()->get();

        return view('tasks.edit', compact(
            'task',
            'taskStatuses',
            'users',
            'taskStatusesSelected',
            'userAssignedToSelected',
            'labels',
            'labelsSelected',
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
        $task->labels()->sync($request->labels);
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
