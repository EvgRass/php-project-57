@extends('layouts.app')

@section('content')
<h1 class="mb-5">{{ __('messages.Add status') }}</h1>

@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li class="text-danger">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{ Form::model($taskStatus, ['url' => route('task_statuses.store'), 'method' => 'post', 'class' => 'w50']) }}
    <div>
        {{ Form::label('name', __('messages.Name')) }}
    </div>
    <div class="mt-2">
        {{ Form::text('name', $value = old('name'), ['class' => 'rounded border-gray-300 w-1/3']) }}
    </div>
    <div class="mt-2">
        {{ Form::submit(__('messages.Сreate'), ['class' => 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded']) }}
    </div>
{{ Form::close() }}

@endsection
