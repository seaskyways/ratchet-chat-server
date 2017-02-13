<?php
/**
 * Created by IntelliJ IDEA.
 * User: User1
 * Date: 01/02 - Feb/17
 * Time: 3:57 PM
 */
require dirname(__DIR__) . '/vendor/autoload.php';

use MyApp\Chat;
use MyApp\ChatClientServer;
use Ratchet\App;
use Symfony\Component\Routing\Route;


$app = new App("192.168.2.11", 80, '0.0.0.0');
$chatClient = new ChatClientServer;

$app->route("/", $chatClient, ["*"]);
$app->route("/chat/ws", new Chat(), ["*"]);

$app->routes->add("some_static_html", new Route("/hello/{opt}",
        array(
            "_controller" => new \MyApp\WildController("/hello"),
            "opt" => ""
        ),
        array(
            'Origin' => "localhost",
            "opt" => '.*'
        ),
        array(),
        "localhost",
        array(),
        array('GET')
    )
);


$app->run();