<?php
/**
 * Created by IntelliJ IDEA.
 * User: User1
 * Date: 14/02 - Feb/17
 * Time: 2:39 PM
 */


$GLOBALS["loader"] = $loader = new Twig_Loader_Filesystem(dirname(__DIR__) . "/template");
$GLOBALS["twig"] = $twig = new Twig_Environment($loader, array(
    'cache' => dirname(__DIR__) . "/template/cache",
    'debug' => true,
));

$twig->addGlobal("baseurl", $baseurl);

$twig->addFunction(new Twig_Function("ng", function ($exp) {
    return "{{ $exp }}";
}));