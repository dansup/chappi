<?php

namespace Plugins;

use Phergie\Irc\Plugin\React\Command\CommandEvent;
use Phergie\Irc\Bot\React\EventQueueInterface;
use Phergie\Irc\Bot\React\PluginInterface;

class KnowledgeGraphPlugin implements PluginInterface
{
  public function getSubscribedEvents()
  {
    return array(
      'command.kg' => 'queryLookup',
      'command.kg.help' => 'help',
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
            'Query the Google Knowledge Graph.',
            'Usage: !kg query',
            'query - A search query',
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

  public function queryLookup(CommandEvent $event, EventQueueInterface $queue)
  {
    $command = $event->getCustomCommand();
    $params = $event->getCustomParams();

    $channel = $event->getSource();
    $query = urlencode(implode(' ', $params));

    $api_key = 'CHANGE ME';

    $service_url = 'https://kgsearch.googleapis.com/v1/entities:search';
    $params = [
    'query' => $query,
    'limit' => 3,
    'key' => $api_key
    ];
    $url = $service_url . '?' . http_build_query($params);
    $response = json_decode(file_get_contents($url), true);
    foreach($response['itemListElement'] as $element) {
      $details = $element['result']['detailedDescription']['articleBody'];
      $mention = $event->getNick().':';
      $msg = "{$mention} {$details}";
      $queue->ircPrivmsg($channel, $msg);
    }

  }
}