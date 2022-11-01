@extends('layouts.app')

@section('content')
<h1 class="mb-5">{{ __('messages.Changing a label') }}</h1>

@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li class="text-danger">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{ Form::model($label, ['url' => route('labels.update', $label), 'method' => 'PATCH', 'class' => 'w50']) }}
    <div>{{ Form::label('name', __('messages.Name')) }}</div>
    <div class="mt-2">{{ Form::text('name', $value = old('name'), ['class' => 'rounded border-gray-300 w-1/3']) }}</div>
    
    <div class="mt-2">{{ Form::label('description', __('messages.Description')) }}</div>
    <div class="mt-2">{{ Form::textarea('description', $value = old('description'), ['class' => 'rounded border-gray-300 w-1/3 h-32']) }}</div>
    
    <div class="mt-2">{{ Form::submit(__('messages.Refresh'), ['class' => 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded']) }}</div>
{{ Form::close() }}

@endsection
