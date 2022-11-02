@extends('layouts.app')

@section('content')
    <h1 class="mb-5">{{ __('messages.Tasks') }}</h1>

    <div>
    {{Form::open([ 'method' => 'GET'])}}
        {{Form::select('filter[status_id]', ['' => __('messages.Status')] + $taskStatuses, $filter['status_id'] ?? null, ['class' => 'rounded border-gray-300'])}}
        {{Form::select('filter[created_by_id]', ['' => __('messages.Author')] + $usersCreated, $filter['created_by_id'] ?? null, ['class' => 'ml-2 rounded border-gray-300'])}}
        {{Form::select('filter[assigned_to_id]', ['' => __('messages.Executor')] + $usersAssigned, $filter['assigned_to_id'] ?? null, ['class' => 'ml-2 rounded border-gray-300'])}}
        {{Form::submit(__('messages.Apply'), ['class' => 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2'])}}
    {{Form::close()}}
    </div>

    @can('create', App\Models\Task::class)
    <div>
        <a href="/tasks/create" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        {{ __('messages.Add task') }}
        </a>
    </div>
    @endcan

    <table class="mt-4">
        <thead class="border-b-2 border-solid border-black text-left">
            <tr>
                <th>ID</th>
                <th>{{ __('messages.Status') }}</th>
                <th>{{ __('messages.Name') }}</th>
                <th>{{ __('messages.Author') }}</th>
                <th>{{ __('messages.Executor') }}</th>
                <th>{{ __('messages.Date of creation') }}</th>
                @auth
                <th>{{ __('messages.Actions') }}</th>
                @endauth
            </tr>
        </thead>
        <tbody>
            @foreach($tasks as $task)
            <tr class="border-b border-dashed text-left">
                <td>{{ $task->id }}</td>
                <td>{{ $task->status->name ?? null }}</td>
                <td><a href="{{ route('tasks.show', $task) }}" class="text-blue-600 hover:text-blue-900">{{ $task->name }}</a></td>
                <td>{{ $task->createdBy->name }}</td>
                <td>{{ $task->assignedTo->name ?? null }}</td>
                <td>{{ \Carbon\Carbon::parse($task->created_at)->format('d.m.Y') }}</td>
                @auth
                <td>
                    <a class="text-blue-600 hover:text-blue-900" href="/tasks/{{ $task->id }}/edit">
                        {{ __('messages.edit') }}
                    </a>
                    @can('delete', $task)
                    <a href="{{ route('tasks.destroy', ['task' => $task->id]) }}" 
                            data-confirm="{{ __('messages.Are you sure?') }}" 
                            data-method="delete" rel="nofollow" 
                            class="text-red-600 hover:text-red-900">
                        {{ __('messages.delete') }}
                    </a>
                    @endcan
                </td>
                @endauth
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $tasks->links() }}
    </div>
@endsection
