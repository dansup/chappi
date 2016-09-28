<?php

namespace Plugins;

use Phergie\Irc\Plugin\React\Command\CommandEvent;
use Phergie\Irc\Bot\React\EventQueueInterface;
use Phergie\Irc\Bot\React\PluginInterface;

class WeatherPlugin implements PluginInterface
{
    public function getSubscribedEvents()
    {
        return array(
            'command.wz' => 'weather',
            'command.wz.help' => 'help',
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
            'A simple weather lookup tool.',
            'Usage: .wz city',
            'city - Name of a city (not all cities are supported)',
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

    public function weather(CommandEvent $event, EventQueueInterface $queue)
    {
      $command = $event->getCustomCommand();
      $params = $event->getCustomParams();

      $channel = $event->getSource();
      $query = implode(' ', $params);

      $DEVELOPER_KEY = 'CHANGE ME';

      $service_url = 'http://api.openweathermap.org/data/2.5/weather';
      $params = [
      'q' => urlencode($query),
      'units' => 'metric',
      'APPID' => $DEVELOPER_KEY
      ];
      $url = $service_url . '?' . http_build_query($params);
      $response = json_decode(file_get_contents($url), true);
      if($response['cod'] == 200) {
        $details = $response['weather'][0]['main'];
        $desc = $response['weather'][0]['description'];
        $city = $response['name'];
        $country = $response['sys']['country'];
        $temp = floor($response['main']['temp']);
        $wind = floor($response['wind']['speed']);
        $mention = $event->getNick().':';
        $msg = "{$mention} \x0315[Temp: {$temp}c] [Wind: {$wind} km/h]\x03 {$details} ({$desc}) in \x02{$city}\x02, {$country}";
        $queue->ircPrivmsg($channel, $msg);
      }

    }
}
