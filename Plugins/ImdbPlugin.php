<?php

namespace Plugins;

use Phergie\Irc\Plugin\React\Command\CommandEvent;
use Phergie\Irc\Bot\React\EventQueueInterface;
use Phergie\Irc\Bot\React\PluginInterface;

class ImdbPlugin implements PluginInterface
{
    public function getSubscribedEvents()
    {
        return array(
            'command.imdb' => 'queryLookup',
            );
    }

    public function queryLookup(CommandEvent $event, EventQueueInterface $queue)
    {
        $command = $event->getCustomCommand();
        $params = $event->getCustomParams();

        $channel = $event->getSource();
        $query = urlencode(implode(' ', $params));

        $url = "http://www.omdbapi.com/?t=";
        $mention = $event->getNick().': ';
        try {
          $res = file_get_contents($url.$query);
          $res = json_decode($res, true);
          if($res['Title'] != null){
            $title = $res['Title'];
            $year = $res['Year'];
            $rating = $res['imdbRating'];
            $id = $res['imdbID'];
            $url = "http://imdb.com/title/{$id}";
            $msg = "{$mention} \x0315[{$year}] [rating: {$rating}]\x03 \x02{$title}\x02 {$url}";
            $queue->ircPrivmsg($channel, $msg);
          } else {
            $msg = "error";
            $queue->ircPrivmsg($channel, $msg);
          }
        } catch (Exception $e) {
          $queue->ircPrivmsg($channel, $mention.'An error occured.');
        }

    }
}