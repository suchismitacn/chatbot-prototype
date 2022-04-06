<?php

use App\Conversations\InitConversation;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;

$botman = resolve('botman');

$botman->hears('Hi|Hello|Hey', function ($bot) {
    $bot->startConversation(new InitConversation);
});

$botman->receivesImages(function($bot, $images) {
    foreach ($images as $image) {
        // $url = $image->getUrl(); // The direct url
        // $title = $image->getTitle(); // The title, if available
        // $payload = $image->getPayload(); // The original payload
        $bot->reply(OutgoingMessage::create('Received: ')->withAttachment($image));
    }
});
