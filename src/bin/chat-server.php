<?php
/**
 * Created by IntelliJ IDEA.
 * User: User1
 * Date: 01/02 - Feb/17
 * Time: 3:57 PM
 */
require dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use MyApp\Chat\Chat;
use MyApp\Server\BaseHttpServer;
use Ratchet\App;
use Symfony\Component\Routing\Route;

$GLOBALS["loader"] = $loader = new Twig_Loader_Filesystem(dirname(__DIR__) . "/template");
$GLOBALS["twig"] = $twig = new Twig_Environment($loader, array(
    'cache' => dirname(__DIR__) . "/template/cache",
    'debug' => true,
));

$GLOBALS["baseurl"] = $baseurl = "192.168.0.100";

$twig->addGlobal("baseurl", $baseurl);
$twig->addFunction(new Twig_Function("ng", function ($exp) {
    return "{{ $exp }}";
}));


$app = new App($baseurl, 80, '0.0.0.0');

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
        $baseurl,
        array(),
        array('GET', 'POST', 'DELETE')
    )
);


$app->run();