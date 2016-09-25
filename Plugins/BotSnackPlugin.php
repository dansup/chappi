<?php

namespace Plugins;

use Phergie\Irc\Plugin\React\Command\CommandEvent;
use Phergie\Irc\Bot\React\EventQueueInterface;
use Phergie\Irc\Bot\React\PluginInterface;

class BotSnackPlugin implements PluginInterface
{
    public function getSubscribedEvents()
    {
        return array(
            'command.botsnack' => 'snack',
            );
    }

    public function snack(CommandEvent $event, EventQueueInterface $queue)
    {
        $command = $event->getCustomCommand();
        $params = $event->getCustomParams();

        $channel = $event->getSource();

        $noise = [
            'nom nom nom.',
            'snack acquired. world domination plans have been postponed.',
            'mmmm cookies.',
            'I LURVE SNACKS!',
            'pls feed me moar, im getting skinny.',
            ''
        ];

        $salutations = [
            '',
            'very much appreciated '.$event->getNick().'.',
            $event->getNick().' da real mvp.',
            $event->getNick().' mah homegurl fo sho.',
            'thanks.',
            'this guy fucks'
        ];

        $msg = trim($noise[array_rand($noise)].' '.$salutations[array_rand($salutations)]);
        
        if(empty($msg) != true) {
            $queue->ircPrivmsg($channel, $msg);
        }

    }
}