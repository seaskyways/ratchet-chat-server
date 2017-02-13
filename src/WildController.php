<?php
/**
 * Created by IntelliJ IDEA.
 * User: User1
 * Date: 13/02 - Feb/17
 * Time: 10:26 AM
 */

namespace MyApp;


use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\Message\Response;
use JsonSchema\Exception\ResourceNotFoundException;
use Ratchet\ConnectionInterface;
use Ratchet\Http\HttpServerInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Router;

class WildController implements HttpServerInterface
{
    private $basePath;
    private $matcher, $routes;

    function __construct($basePath)
    {
        $this->routes = new RouteCollection();
        $this->matcher = new UrlMatcher($this->routes, new RequestContext($basePath));

        $this->routes->add("index", new Route("/", array("_controller" => "helloWorld")));

        $this->basePath = $basePath;
    }

    function onClose(ConnectionInterface $conn)
    {
    }

    function onError(ConnectionInterface $conn, \Exception $e)
    {
    }

    public function onOpen(ConnectionInterface $conn, RequestInterface $request = null)
    {
        $realPath = $request->getPath();
        $pos = strpos($realPath, $this->basePath);
        if ($pos !== false) {
            $realPath = substr_replace($realPath, "", $pos, strlen($this->basePath));
        }
        echo "received request ... " . $realPath . PHP_EOL;


//        foreach ($this->routes as $key => $func) {
//            if ($key == $realPath) {
//                $resp = new Response(200, [
//                    'Content-Type' => 'text/html; charset=utf-8',
//                ]);
//                $resp->setBody($func());
//                $conn->send($resp);
//                $conn->close();
//                return;
//            }
//        }
        try {
            $func = $this->matcher->match($request->getPath())["_controller"];
            call_user_func($func["_controller"]);

        } catch (ResourceNotFoundException $e) {
            $err = new Response(200, [
                'Content-Type' => 'text/html; charset=utf-8',
            ]);

            $err->setBody("An Error has occured !");
            $conn->send($err);
            $conn->close();
        }
    }

    function onMessage(ConnectionInterface $from, $msg)
    {
    }

    function helloWorld()
    {
        return "Hello World !";
    }

    private function getFunctionOf($str): \Closure
    {
        return function () use ($str) {
            return call_user_func($str);
        };
    }
}