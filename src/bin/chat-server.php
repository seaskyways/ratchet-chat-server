<?php
/**
 * Created by IntelliJ IDEA.
 * User: User1
 * Date: 01/02 - Feb/17
 * Time: 3:57 PM
 */
require dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use MyApp\Chat\Chat;
use MyApp\Server\AppHttpServer;
use Ratchet\App;
use Symfony\Component\Routing\Route;

require_once 'setup-server-host-url.php';
require_once 'setup-twig.php';
require_once 'idiorm.php';
require_once 'paris.php';
require_once 'setup-database.php';

$app = new App($baseurl, 8080, '0.0.0.0');

$app->route("/ws/chat", new Chat(), ["*"]);

$appHttpServer = new AppHttpServer;

$app->routes->add("some_static_html", new Route("/{opt}",
        array(
            "_controller" => $appHttpServer,
            "opt" => ""
        ),
        array(
            'Origin' => "localhost",
            "opt" => '.*'
        ),
        array(),
        $baseurl,
        array(),
        array('GET', 'POST', 'DELETE')
    )
);


$app->run();