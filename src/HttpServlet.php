<?php
use Guzzle\Http\Message\RequestInterface;
use Ratchet\ConnectionInterface as Conn;
use Ratchet\Http\HttpServerInterface;

/**
 * Created by IntelliJ IDEA.
 * User: User1
 * Date: 13/02 - Feb/17
 * Time: 1:08 PM
 */

trait SimpleHttpServer {
    public $httpServer;

    function __construct()
    {
        $this->httpServer = new class implements HttpServerInterface{
            function onClose(Conn $conn)
            {
            }

            function onError(Conn $conn, \Exception $e)
            {
            }

            public function onOpen(Conn $conn, RequestInterface $request = null)
            {
            }

            function onMessage(Conn $from, $msg)
            {
            }
        };
    }


}