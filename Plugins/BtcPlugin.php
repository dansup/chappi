<?php

namespace Plugins;

use \Phergie\Irc\Plugin\React\Command\CommandEvent;
use \Phergie\Irc\Bot\React\EventQueueInterface;
use \Phergie\Irc\Bot\React\PluginInterface;
use \GuzzleHttp\Psr7\Request;

class BtcPlugin implements PluginInterface
{
    public function getSubscribedEvents()
    {
        return array(
            'command.btc' => 'getPrice',
            );
    }
    public function getPrice(CommandEvent $event, EventQueueInterface $queue)
    {
        $client = new \GuzzleHttp\Client;
        $request = new Request('GET', 'https://api.coindesk.com/v1/bpi/currentprice.json');
        $response = $client->send($request, ['timeout' => 2]);
        $data = json_decode($response->getBody(), true);

        $usd = $data['bpi']['USD']['rate'];
        $gbp = $data['bpi']['GBP']['rate'];
        $eur = $data['bpi']['EUR']['rate'];
        $time = $data['time']['updated'];

        $mention = $event->getNick().":";
        $channel = $event->getSource();

        $msg = "{$mention} 1.0 BTC == $".floor($usd)." || £".floor($gbp)." || €".floor($eur)." as of {$time}";
        $queue->ircPrivmsg($channel, $msg);

    }
}