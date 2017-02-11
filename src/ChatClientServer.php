<?php
/**
 * Created by IntelliJ IDEA.
 * User: User1
 * Date: 11/02 - Feb/17
 * Time: 9:44 AM
 */

namespace MyApp;

use Ratchet\Http\HttpServerInterface;

class ChatClientServer extends StaticHtmlServer
{
    function __construct()
    {
        parent::__construct(__DIR__ . '/chat-client.html');
    }
}
