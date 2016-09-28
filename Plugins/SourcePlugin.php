<?php

namespace Plugins;

use Phergie\Irc\Plugin\React\Command\CommandEvent;
use Phergie\Irc\Bot\React\EventQueueInterface;
use Phergie\Irc\Bot\React\PluginInterface;

class SourcePlugin implements PluginInterface
{
    public function getSubscribedEvents()
    {
        return array(
            'command.source' => 'source',
            'command.about' => 'source',
            );
    }

    /**
     * Send source command
     *
     * @param \Phergie\Irc\Plugin\React\Command\CommandEvent $event
     * @param \Phergie\Irc\Bot\React\EventQueueInterface $queue
     */
    public function source(CommandEvent $event, EventQueueInterface $queue)
    {
        $channel = $event->getSource();
        $msg = "I am bot. Source code: https://github.com/dansup/chappi";
        $queue->ircPrivmsg($channel, $msg);

    }


}