<?php

include './vendor/autoload.php';

use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Event;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load('./.env');

$discord = new Discord([
    'token' => $_ENV['DISCORD_TOKEN'],
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

        // Dados de todos os tamanhos
        if (strrpos($message->content, '$roll d') === 0) {
            $tamanho = explode('$roll d', $message->content)[1];
            if((int)$tamanho) {
                $message->reply(rand(1, (int)$tamanho));
            }
            return;
        }

        if ($message->content == '$d20') {
            $message->reply(rand(1, 20));
            return;
        }

        // Verdade ou mentira?
        if (strpos('$?', $message->content)) {
            $rand = (rand(1, 2));
            if($rand == 1) {
                $message->reply('Verdade!');
            } else {
                $message->reply('Mentira!');
            }
            return;
        }

        echo "{$message->author->username}: {$message->content}", PHP_EOL;
    });
});

$discord->run();
