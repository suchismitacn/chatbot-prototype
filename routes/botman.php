<?php

use App\Conversations\InitConversation;
use App\Conversations\SendConversation;
use App\Middlewares\CapturedMiddleware;
use App\Middlewares\HeardMiddleware;
use App\Middlewares\SendingMiddleware;

$botman = resolve('botman');

// custom middlewares
$botman->middleware->heard(new HeardMiddleware());
$botman->middleware->captured(new CapturedMiddleware());
$botman->middleware->sending(new SendingMiddleware());


$botman->hears('Hi|Hello|Hey', function ($bot) {
    $bot->startConversation(new InitConversation);
});

$botman->hears('Mail me|Email me', function ($bot) {
    $bot->startConversation(new SendConversation);
});

$botman->hears('Bye', function ($bot) {
    $user = $bot->userStorage()->get('name') ?? 'user';
    $bot->reply('Bye ' . $user . '! Have a nice day. :)');
    /* user storage can be deleted only by this way, I tried to delete from inside 
    conversation class, but it did not work. Same with stopsConversation. */
    $bot->userStorage()->delete();
})->stopsConversation();
