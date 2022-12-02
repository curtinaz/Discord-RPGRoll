<?php

include './vendor/autoload.php';

use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\Parts\Interactions\Command\Command;
use Discord\Parts\Interactions\Interaction;
use Discord\WebSockets\Event;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load('./.env');

$discord = new Discord([
    'token' => $_ENV['DISCORD_TOKEN'],
]);

$discord->on('ready', function (Discord $discord) {
    echo "Bot is ready!", PHP_EOL;

    $command = new Command($discord, ['name' => 'ping', 'description' => 'pong']);
    $discord->application->commands->save($command);

    // Listen for messages.
    $discord->on(Event::MESSAGE_CREATE, function (Message $message, Discord $discord) {

        echo "{$message->author->username}: {$message->content}", PHP_EOL;

        $afirmations = [
            "Verdade.",
            "Com certeza.",
            "Sem dúvidas.",
            "Na minha opinião, sim.",
            "É obvio."
        ];

        $negatives = [
            "Mentira.",
            "Acho que não.",
            "Olha, acredito que não.",
            "Parando para pensar, acho que não.",
            "Obvio que não."
        ];

        $select = rand(0, count($afirmations) - 1);

        if ($message->author->bot) {
            return;
        }

        // Dados de todos os tamanhos
        if (strrpos($message->content, '$roll d') === 0) {
            $tamanho = explode('$roll d', $message->content)[1];
            if ((int)$tamanho) {
                $message->reply(rand(1, (int)$tamanho));
            }
            return;
        }

        if ($message->content == '$d20') {
            $message->reply(rand(1, 20));
            return;
        }

        // Verdade ou mentira?
        if (str_contains($message->content, '$?') !== false) {
            $rand = (rand(1, 2));

            if ($rand == 1) {
                $message->reply($afirmations[$select]);
            } else {
                $message->reply($negatives[$select]);
            }
            return;
        }
    });
});

// Ping command
$discord->listenCommand('ping', function (Interaction $interaction) {
    $interaction->respondWithMessage(MessageBuilder::new()->setContent('Pong!'));
});

$discord->run();
