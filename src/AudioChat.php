<?php
/**
 * Created by IntelliJ IDEA.
 * User: Ahmad
 * Date: 12/02 Feb/2017
 * Time: 10:06 AM
 */

namespace MyApp;


use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use Ratchet\Wamp\WampServerInterface;

class AudioChat implements MessageComponentInterface
{
    private $clients;

    function __construct()
    {
        $this->clients = new \SplObjectStorage();
    }

    function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
    }

    function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
    }

    function onError(ConnectionInterface $conn, \Exception $e)
    {
        $this->clients->detach($conn);
        echo PHP_EOL . $e->getTrace() . PHP_EOL;
    }

    function onMessage(ConnectionInterface $from, $msg)
    {
        foreach ($this->clients as $client){
            if ($this->clients !== $from){
                $client->send($msg);
            }
        }
    }
}