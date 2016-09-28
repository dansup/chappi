<?php

namespace Plugins;

use Phergie\Irc\Plugin\React\Command\CommandEvent;
use Phergie\Irc\Bot\React\EventQueueInterface;
use Phergie\Irc\Bot\React\PluginInterface;

class UrbanDictionaryPlugin implements PluginInterface
{
    public function getSubscribedEvents()
    {
        return array(
            'command.u' => 'queryLookup',
            );
    }

    public function queryLookup(CommandEvent $event, EventQueueInterface $queue)
    {
        $command = $event->getCustomCommand();
        $params = $event->getCustomParams();

        $channel = $event->getSource();
        $query = urlencode(implode(' ', $params));

        $url = "http://api.urbandictionary.com/v0/define?term=";
        $mention = $event->getNick().': ';
        try {
          $res = file_get_contents($url.$query);
          $res = json_decode($res, true);
          if($res['result_type'] == 'exact'){
            $definition = $res['list'][0]['definition'];
            $permalink = $res['list'][0]['permalink'];
            $thumbs_up = $res['list'][0]['thumbs_up'];
            $thumbs_down = $res['list'][0]['thumbs_down'];
            $msg = "{$mention} [+{$thumbs_up}][-{$thumbs_down}] {$definition} {$permalink}";
            $queue->ircPrivmsg($channel, $msg);
          }
        } catch (Exception $e) {
          $queue->ircPrivmsg($channel, $mention.'An error occured.');
        }

    }
}