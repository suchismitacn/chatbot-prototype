@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @if (isset($status))
                <div class="alert alert-info" role="alert">
                    <strong>{{ $status }}</strong>
                </div>
            @else
                <chat :sender='@json($sender)' :recipient='@json($recipient)'></chat>
            @endif
        </div>
    </div>
@endsection
