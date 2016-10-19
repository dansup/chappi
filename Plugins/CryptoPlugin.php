<?php

namespace Plugins;

use Phergie\Irc\Plugin\React\Command\CommandEvent;
use Phergie\Irc\Bot\React\EventQueueInterface;
use Phergie\Irc\Bot\React\PluginInterface;

class CryptoPlugin implements PluginInterface
{
    public function getSubscribedEvents()
    {
        return array(
            'command.hash.algos' => 'hashAlgos',
            'command.hash' => 'hash',
            );
    }
    public function hash(CommandEvent $event, EventQueueInterface $queue)
    {

        $command = $event->getCustomCommand();
        $param = $event->getCustomParams();
        $algo = $param[0];
        unset($param[0]);
        $plaintext = $param;
        $mention = $event->getNick().":";
        $channel = $event->getSource();

        if( in_array($algo, hash_algos()) == false ) {
            $msg = "{$mention} There was an error with dat algo";
            $queue->ircPrivmsg($channel, $msg);
        } else {
            $hash = hash($algo[0], $plaintext);
            $msg = "{$mention} {$hash}";
            $queue->ircPrivmsg($channel, $msg);
        }

    }
    public function hashAlgos(CommandEvent $event, EventQueueInterface $queue)
    {
        $channel = $event->getSource();
        $algos = implode(',', hash_algos());
        $msg = "Available hash algorithms: {$algos}";
        $queue->ircPrivmsg($channel, $msg);
    }
}