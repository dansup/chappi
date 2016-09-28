<?php

namespace Plugins;

use Phergie\Irc\Plugin\React\Command\CommandEvent;
use Phergie\Irc\Bot\React\EventQueueInterface;
use Phergie\Irc\Bot\React\PluginInterface;

class CalculatorPlugin implements PluginInterface
{
    public function getSubscribedEvents()
    {
        return array(
            'command.calc' => 'calculate',
            'command.calc.help' => 'help',
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
            'A simple calculator.',
            'Usage: .calc 1 + 1',
            'See http://php.net/manual/en/language.operators.arithmetic.php for a full list of arithmetic operators.',
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
    public function calculate(CommandEvent $event, EventQueueInterface $queue)
    {
        $command = $event->getCustomCommand();
        $params = $event->getCustomParams();

        $channel = $event->getSource();

        $query = $this->operation($params);
        if($query == false) {
        } else {
            $mention = $event->getNick().':';
            $msg = "{$mention} {$query}";
            $queue->ircPrivmsg($channel, $msg);
        }

    }

    public function operation($params)
    {
        $allowed = [
        '+', // add
        '-', // subtract
        '*', // multiply
        'x', // multiply
        'X', // multiply
        '/', // divide
        '%', // modulus
        '**', // exponentiation
        ];

        if( in_array($params[1], $allowed) == false) {
            return false;
        }
        $eq = 'NaN';

        switch ($params[1]) {
            case '+':
                $eq = intval($params[0]) + intval($params[2]);
                break;
            case '-':
                $eq = intval($params[0]) - intval($params[2]);
                break;
            case '*':
            case 'x':
            case 'X':
                $eq = intval($params[0]) * intval($params[2]);
                break;
            case '/':
                $eq = intval($params[0]) / intval($params[2]);
                break;
            case '%':
                $eq = intval($params[0]) % intval($params[2]);
                break;
            case '**':
                $eq = intval($params[0]) ** intval($params[2]);
                break;
            
            default:
                $eq = 'Beep Borp... Did not compute. Pls try just a little bit harder.';
                break;
        }

        return $eq;

    }
}