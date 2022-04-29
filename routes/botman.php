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


$botman->hears('start', function ($bot) {
    $bot->startConversation(new InitConversation);
});

$botman->hears('mail', function ($bot) {
    $bot->startConversation(new SendConversation);
});

$botman->hears('bye', function ($bot) {
    $user = $bot->userStorage()->get('name') ?? 'user';
    $bot->reply('Bye ' . $user . '! Have a nice day. :)');
    /* user storage can be deleted only by this way, I tried to delete from inside 
    conversation class, but it did not work. Same with stopsConversation. */
    $bot->userStorage()->delete();
})->stopsConversation();
