@extends('layouts.app')

@section('content')
<h2 class="mb-5">{{ __('messages.View a task') }}: {{ $task->name }}
    <a href="/tasks/{{ $task->id }}/edit">⚙</a>
</h2>

@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li class="text-danger">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<p><span class="font-black">{{ __('messages.Name') . ": " }}</span>{{ $task->name }}</p>
<p><span class="font-black">{{ __('messages.Status') . ": " }}</span>{{ $task->status->name ?? null }}</p>
<p><span class="font-black">{{ __('messages.Description') . ": " }}</span>{{ $task->description ?? null}}</p>

@endsection