<?php
/**
 * Created by IntelliJ IDEA.
 * User: User1
 * Date: 11/02 - Feb/17
 * Time: 8:44 AM
 */

namespace MyApp;


use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\Message\Response;
use Ratchet\ConnectionInterface;
use Ratchet\Http\HttpServerInterface;


class StaticHtmlServer implements HttpServerInterface
{
    private $staticPage;
    private $dom;
    private $pagePath;

    function __construct($pagePath)
    {
        $this->dom = new \DOMDocument();
        $this->pagePath = $pagePath;
    }

    function loadHtml(){
        $this->dom->loadHTMLFile($this->pagePath);
        $this->staticPage = $this->dom->saveHTML();
    }

    function onClose(ConnectionInterface $conn)
    {
    }

    function onError(ConnectionInterface $conn, \Exception $e)
    {
    }

    public function onOpen(ConnectionInterface $conn, RequestInterface $request = null)
    {
        $this->loadHtml();
        $response = new Response(200, [
            'Content-Type' => 'text/html; charset=utf-8',
        ]);
        $response->setBody($this->staticPage);
        $conn->send($response);
        $conn->close();
    }

    function onMessage(ConnectionInterface $from, $msg)
    {
    }
}