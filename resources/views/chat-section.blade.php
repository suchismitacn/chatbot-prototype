@extends('layouts.app')
@section('title', 'Live Chat')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @if (isset($status))
                <div class="alert alert-info" role="alert">
                    <strong>{{ $status }}</strong>
                </div>
            @else
                <chat :sender='@json($sender)' :recipient='@json($recipient)'
                    :chat-id='@json($chatId)'></chat>
                    @auth()
                        <chat-user-list :users='@json($user)' :sender='@json($sender)'></chat-user-list>
                    @endauth
            @endif
        </div>
    </div>
@endsection
