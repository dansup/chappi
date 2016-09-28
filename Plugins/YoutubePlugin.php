<?php

namespace Plugins;

use Phergie\Irc\Plugin\React\Command\CommandEvent;
use Phergie\Irc\Bot\React\EventQueueInterface;
use Phergie\Irc\Bot\React\PluginInterface;

class YoutubePlugin implements PluginInterface
{
    public function getSubscribedEvents()
    {
        return array(
            'command.yt' => 'queryLookup',
            );
    }

    public function queryLookup(CommandEvent $event, EventQueueInterface $queue)
    {
        $command = $event->getCustomCommand();
        $params = $event->getCustomParams();

        $channel = $event->getSource();
        $query = urlencode(implode(' ', $params));

        $DEVELOPER_KEY = 'Change Me';

        $client = new \Google_Client();
        $client->setDeveloperKey($DEVELOPER_KEY);

        // Define an object that will be used to make all API requests.
        $youtube = new \Google_Service_YouTube($client);

        $mention = $event->getNick().': ';
        try {
            $searchResponse = $youtube->search->listSearch('id,snippet', array(
              'q' => $query,
              'maxResults' => 3,
            ));

            $videos = '';
            $channels = '';
            $playlists = '';

            // Add each result to the appropriate list, and then display the lists of
            // matching videos, channels, and playlists.
            foreach ($searchResponse['items'] as $searchResult) {
              switch ($searchResult['id']['kind']) {
                case 'youtube#video':
                  $title = $searchResult['snippet']['title'];
                  $url = "https://youtube.com/watch?v={$searchResult['id']['videoId']}";
                  $msg = "{$mention} {$title} {$url}";
                  $queue->ircPrivmsg($channel, $msg);
                  break;
                case 'youtube#channel':
                  $queue->ircPrivmsg($channel, 'channel here');
                  break;
                case 'youtube#playlist':
                  $queue->ircPrivmsg($channel, 'playlist here');
                  break;
              }
            }
        } catch (Exception $e) {
          $queue->ircPrivmsg($channel, $mention.'An error occured.');
        }

    }
}