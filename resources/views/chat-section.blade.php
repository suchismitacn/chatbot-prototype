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
                <chat 
                :chat-sender='@json($sender)' 
                :chat-recipient='@json($recipient)'
                :chat-session='@json($chatId)' 
                :is-admin='@json(request()->route()->named('admin-live-chat') ? true : false)'></chat>
            @endif
        </div>
    </div>
@endsection
