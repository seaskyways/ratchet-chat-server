<?php
/**
 * Created by IntelliJ IDEA.
 * User: Ahmad
 * Date: 13/02 Feb/2017
 * Time: 9:23 PM
 */

namespace MyApp\Server;


use Guzzle\Http\Message\RequestInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Http\HttpServerInterface;
use Slim\Http\Body;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Uri;

abstract class SimpleHttpServer implements HttpServerInterface
{
    function onMessage(ConnectionInterface $from, $msg)
    {
    }

    function onError(ConnectionInterface $conn, \Exception $e)
    {
    }

    function onClose(ConnectionInterface $conn)
    {
    }

    final public function onOpen(ConnectionInterface $conn, RequestInterface $r = null)
    {
        $slimRequest = new Request(
            $r->getMethod(),
            new Uri($r->getScheme(), $r->getHost(), $r->getPort(), $r->getPath()),
            new Headers($r->getHeaderLines()),
            $r->getCookies(),
            $r->getParams()->getAll(),
            new Body($r->getResponseBody()->getStream())
        );

        $response = $this->getRequestResponse($slimRequest, new Response());
        $conn->send($response);
        $conn->close();
    }

    abstract function getRequestResponse(Request $request = null, $response);
}