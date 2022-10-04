@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="jumbotron">
            <h1 class="display-4">{{ __('messages.Greetings from Hexlet!') }}</h1>
            <p class="lead">{{ __('messages.Practical programming courses') }}</p>
            <hr class="my-4">
            <a class="btn btn-primary btn-lg" href="https://hexlet.io" role="button">{{ __('messages.To learn more') }}</a>
        </div>
        </div>
    </div>
</div>
@endsection