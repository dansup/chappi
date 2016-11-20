<?php

namespace Plugins;

use Phergie\Irc\Plugin\React\Command\CommandEvent;
use Phergie\Irc\Bot\React\EventQueueInterface;
use Phergie\Irc\Bot\React\PluginInterface;

class AsciiPlugin implements PluginInterface
{
    public function getSubscribedEvents()
    {
        return array(
            'command.tf' => 'handleEvent',
            'command.tableflip' => 'handleEvent',
            'command.sh' => 'handleEvent',
            'command.shrug' => 'handleEvent',
            'command.lod' => 'handleEvent',
            'command.zoidberg' => 'handleEvent',
            'command.yuno' => 'handleEvent',
            'command.facepalm' => 'handleEvent',
            'command.flipall' => 'handleEvent',
            'command.zerofucks' => 'handleEvent',
            'command.kill' => 'handleEvent',
            'command.huh' => 'handleEvent',
            'command.omg' => 'handleEvent',
            'command.deal' => 'handleEvent',
            );
    }

    public function handleEvent(CommandEvent $event, EventQueueInterface $queue)
    {
        $command = $event->getCustomCommand();
        $params = $event->getCustomParams();

        $channel = $event->getSource();
        $msg = null;

        switch ($command) {
          case 'sh':
          case 'shrug':
            $msg = "¯\_(ツ)_/¯";
            break;

          case 'tf':
          case 'tableflip':
            $msg = "(╯°□°）╯︵ ┻━┻";
            break;

          case 'lod':
            $msg = "ಠ_ಠ";
            break;

          case 'zoidberg':
            $msg = "(\/) (°,,°) (\/)";
            break;

          case 'yuno':
            $msg = "(ノಠ益ಠ)ノ";
            break;

          case 'facepalm':
            $msg = "(>ლ)";
            break;

          case 'flipall':
            $msg = "┻━┻︵ (°□°)/ ︵ ┻━┻";
            break;

          case 'zerofucks':
            $msg = "╭∩╮（︶︿︶）╭∩╮";
            break;

          case 'kill':
            $msg = "(╯°□°)--︻╦╤─ - - -";
            break;

          case 'huh':
            $msg = "•͡˘㇁•͡˘";
            break;

          case 'omg':
            $msg = "⨀_⨀";
            break;

          case 'deal':
            $msg = "(••) ( ••)>⌐■-■ (⌐■_■)";
            break;

          default:
            $msg = null;
            break;
        }
        if(null == $msg) {
          return;
        }
        $query = filter_var($params[0]);
        if( empty($query) == false) {
            if($command == 'kill') {
                $mention = $query;
                $message = "{$msg} {$mention}"; 
            } else {
                $mention = $query.':';
                $message = "{$mention} {$msg}";
            }
            $queue->ircPrivmsg($channel, $message);
        } else {
            $message = $msg;
            $queue->ircPrivmsg($channel, $message);
        }

    }


}