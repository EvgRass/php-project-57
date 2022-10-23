@extends('layouts.app')

@section('content')
    <h1 class="mb-5">{{ __('messages.Statuses') }}</h1>

    @can('create', App\Models\TaskStatus::class)
    <div>
        <a href="/task_statuses/create" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        {{ __('messages.Add status') }}
        </a>
    </div>
    @endcan

    <table class="mt-4">
        <thead class="border-b-2 border-solid border-black text-left">
            <tr>
                <th>ID</th>
                <th>{{ __('messages.Name') }}</th>
                <th>{{ __('messages.Date of creation') }}</th>
                @auth
                <th>{{ __('messages.Actions') }}</th>
                @endauth
            </tr>
        </thead>
        <tbody>
            @foreach($taskStatuses as $status)
            <tr class="border-b border-dashed text-left">
                <td>{{ $status->id }}</td>
                <td>{{ $status->name }}</td>
                <td>{{ \Carbon\Carbon::parse($status->created_at)->format('d.m.Y') }}</td>
                @auth
                <td>
                    <a data-confirm="{{ __('messages.Are you sure?') }}" data-method="delete" 
                        class="text-red-600 hover:text-red-900" rel="nofollow" href="/task_statuses/{{ $status->id }}">
                        {{ __('messages.delete') }}
                    </a>
                    <a class="text-blue-600 hover:text-blue-900" href="/task_statuses/{{ $status->id }}/edit">
                        {{ __('messages.edit') }}
                    </a>
                </td>
                @endauth
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
