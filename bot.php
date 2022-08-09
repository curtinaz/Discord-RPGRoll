<?php

include __DIR__ . '/vendor/autoload.php';

use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Event;

$discord = new Discord([
    'token' => 'NzExNTc0NDIyMjQyMzI4NjI2.GkC3gC.2lHhNiYc4pDPpbIVlcrVYuLpo-crGObd_hHbHc',
]);

$discord->on('ready', function (Discord $discord) {
    echo "Bot is ready!", PHP_EOL;

    // Listen for messages.
    $discord->on(Event::MESSAGE_CREATE, function (Message $message, Discord $discord) {

        if ($message->author->bot) {
            return;
        }

        if (strrpos($message->content, '$') !== 0) {
            return;
        }

        if($message->content == '$d20') {
            $message->reply(rand(1,20));
        }

        echo "{$message->author->username}: {$message->content}", PHP_EOL;
    });
});

$discord->run();
