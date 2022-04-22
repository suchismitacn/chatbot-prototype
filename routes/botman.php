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

$botman->hears('Mail me|Mail conversation|mail me|email me|Email me', function ($bot) {
    $bot->startConversation(new SendConversation);
});

