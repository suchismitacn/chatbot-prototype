<?php

use App\Conversations\InitConversation;
use App\Conversations\SendConversation;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;

$botman = resolve('botman');

$botman->hears('Hi|Hello|Hey', function ($bot) {
    $bot->startConversation(new InitConversation);
});

$botman->hears('Mail me|Mail conversation|mail me|email me|Email me', function ($bot) {
    $bot->startConversation(new SendConversation);
});

$botman->receivesImages(function($bot, $images) {
    foreach ($images as $image) {
        // $url = $image->getUrl(); // The direct url
        // $title = $image->getTitle(); // The title, if available
        // $payload = $image->getPayload(); // The original payload
        $bot->reply(OutgoingMessage::create('Received: ')->withAttachment($image));
    }
});
