<?php

use App\Conversations\ChatBotV1\InitConversation;
use App\Conversations\ChatBotV1\SendConversation;
use App\Conversations\ChatBotV2\FAQConversation;
use App\Conversations\ChatBotV2\MenuConversation;
use App\Conversations\ChatBotV2\OriginateConversation;
use App\Middlewares\CapturedMiddleware;
use App\Middlewares\HeardMiddleware;
use App\Middlewares\SendingMiddleware;
use BotMan\Middleware\DialogFlow\V2\DialogFlow;

$botman = resolve('botman');

$dialogflow = DialogFlow::create('en');

// custom middlewares
$botman->middleware->heard(new HeardMiddleware());
$botman->middleware->captured(new CapturedMiddleware());
$botman->middleware->sending(new SendingMiddleware());
$botman->middleware->received($dialogflow);

// $botman->hears('start', function ($bot) {
//     $bot->startConversation(new InitConversation);
// });

// $botman->hears('mail', function ($bot) {
//     $bot->startConversation(new SendConversation);
// });

$botman->hears('bye', function ($bot) {
    $user = $bot->userStorage()->get('name') ?? 'user';
    $bot->reply('Bye ' . $user . '! Have a nice day. :)');
    $bot->reply('You can always wake me up by saying hi.');
    /* user storage can be deleted only by this way, I tried to delete from inside 
    conversation class, but it did not work. Same with stopsConversation. */
    $bot->userStorage()->delete();
})->stopsConversation();

$botman->hears('hi', function ($bot) {
    $bot->startConversation(new OriginateConversation);
});

$botman->hears('menu', function ($bot) {
    $bot->startConversation(new MenuConversation);
});

$botman->hears('testfaq.(.*)', function ($bot) {
    $bot->startConversation(new FAQConversation);
})->middleware($dialogflow);

$botman->fallback(function($bot) {
    $bot->reply('Sorry, I did not understand that.');
});
