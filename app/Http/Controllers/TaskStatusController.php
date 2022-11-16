<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskStatus;

class TaskStatusController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(TaskStatus::class);
    }

    public function index()
    {
        $taskStatuses = TaskStatus::orderBy('id')->paginate(20);
        return view('taskStatuses.index', compact('taskStatuses'));
    }

    public function create()
    {
        $taskStatuses = new TaskStatus();
        return view('taskStatuses.create', compact('taskStatuses'));
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'name' => 'required|unique:task_statuses,name',
        ], ['unique' => __('messages.Status exists')]);

        $taskStatus = new TaskStatus();
        $taskStatus->fill($data);
        $taskStatus->save();

        flash(__('messages.Status added successfully!'))->success();

        return redirect()->route('task_statuses.index');
    }

    public function edit(TaskStatus $taskStatus)
    {
        return view('taskStatuses.edit', compact('taskStatus'));
    }

    public function update(Request $request, TaskStatus $taskStatus)
    {
        $data = $this->validate($request, [
            'name' => 'required|unique:task_statuses,name'
        ]);
        $taskStatus->fill($data);
        $taskStatus->save();

        flash(__('messages.Status edited successfully!'))->success();

        return redirect()->route('task_statuses.index');
    }

    public function destroy(TaskStatus $taskStatus)
    {
        if ($taskStatus->tasks->count() === 0) {
            $taskStatus->delete();
            flash(__('messages.Status deleted successfully!'))->success();
        } else {
            flash(__('messages.Failed to delete status'))->error();
        }

        return redirect()->route('task_statuses.index');
    }
}
