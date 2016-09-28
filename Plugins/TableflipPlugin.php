<?php

namespace Plugins;

use Phergie\Irc\Plugin\React\Command\CommandEvent;
use Phergie\Irc\Bot\React\EventQueueInterface;
use Phergie\Irc\Bot\React\PluginInterface;

class TableflipPlugin implements PluginInterface
{
    public function getSubscribedEvents()
    {
        return array(
            'command.tf' => 'tableflip',
            'command.tf.help' => 'help',
            );
    }

    /**
     * Say Command Help
     *
     * @param \Phergie\Irc\Plugin\React\Command\CommandEvent $event
     * @param \Phergie\Irc\Bot\React\EventQueueInterface $queue
     */
    public function help(CommandEvent $event, EventQueueInterface $queue)
    {
        $this->sendHelpReply($event, $queue, array(
            'Ash uses tableflip.',
            'Usage: .sh nickname',
        ));
    }

    /**
     * Responds to a help command.
     *
     * @param \Phergie\Irc\Plugin\React\Command\CommandEvent $event
     * @param \Phergie\Irc\Bot\React\EventQueueInterface $queue
     * @param array $messages
     */
    protected function sendHelpReply(CommandEvent $event, EventQueueInterface $queue, array $messages)
    {
        $method = 'irc' . $event->getCommand();
        $target = $event->getSource();
        foreach ($messages as $message) {
            $queue->$method($target, $message);
        }
    }
    public function tableflip(CommandEvent $event, EventQueueInterface $queue)
    {
        $command = $event->getCustomCommand();
        $params = $event->getCustomParams();

        $channel = $event->getSource();

        $query = filter_var($params[0]);
        if( empty($query) == false) {
            $mention = $query.':';
            $msg = "{$mention} (╯°□°）╯︵ ┻━┻";
            $queue->ircPrivmsg($channel, $msg);
        } else {
            $msg = "(╯°□°）╯︵ ┻━┻";
            $queue->ircPrivmsg($channel, $msg);
        }

    }


}