<?php
/**
 * Created by IntelliJ IDEA.
 * User: User1
 * Date: 01/02 - Feb/17
 * Time: 3:57 PM
 */
require dirname(__DIR__, 2) . '/vendor/autoload.php';

use MyApp\Chat\Chat;
use MyApp\Chat\ChatClientServer;
use MyApp\Server\BaseHttpServer;
use Ratchet\App;
use Symfony\Component\Routing\Route;


$app = new App("localhost", 80, '0.0.0.0');

$chatClient = new ChatClientServer;

//$app->route("/", $chatClient, ["*"]);
$app->route("/chat/ws", new Chat(), ["*"]);

$app->routes->add("some_static_html", new Route("/{opt}",
        array(
            "_controller" => new BaseHttpServer(),
            "opt" => ""
        ),
        array(
            'Origin' => "localhost",
            "opt" => '.*'
        ),
        array(),
        "localhost",
        array(),
        array('GET', 'POST', 'DELETE')
    )
);


$app->run();