@extends('layouts.app')

@section('content')
    <h1 class="mb-5">{{ __('messages.Labels') }}</h1>

    @can('create', App\Models\Label::class)
    <div>
        <a href="/labels/create" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        {{ __('messages.Add label') }}
        </a>
    </div>
    @endcan

    <table class="mt-4">
        <thead class="border-b-2 border-solid border-black text-left">
            <tr>
                <th>ID</th>
                <th>{{ __('messages.Name') }}</th>
                <th>{{ __('messages.Description') }}</th>
                <th>{{ __('messages.Date of creation') }}</th>
                @auth
                <th>{{ __('messages.Actions') }}</th>
                @endauth
            </tr>
        </thead>
        <tbody>
            @foreach($labels as $label)
            <tr class="border-b border-dashed text-left">
                <td>{{ $label->id }}</td>
                <td>{{ $label->name }}</td>
                <td>{{ $label->description }}</td>
                <td>{{ \Carbon\Carbon::parse($label->created_at)->format('d.m.Y') }}</td>
                @auth
                <td>
                    @can('delete', $label)
                    <a href="{{ route('labels.destroy', ['label' => $label->id]) }}" 
                            data-confirm="{{ __('messages.Are you sure?') }}" 
                            data-method="delete" rel="nofollow" 
                            class="text-red-600 hover:text-red-900">
                        {{ __('messages.delete') }}
                    </a>
                    @endcan
                    <a class="text-blue-600 hover:text-blue-900" href="/labels/{{ $label->id }}/edit">
                        {{ __('messages.edit') }}
                    </a>
                </td>
                @endauth
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
