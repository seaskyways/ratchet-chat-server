<?php
/**
 * Created by IntelliJ IDEA.
 * User: Ahmad
 * Date: 13/02 Feb/2017
 * Time: 6:53 PM
 */

namespace MyApp\Server;

use Slim\App;
use Slim\Container;
use Slim\Handlers\Strategies\RequestResponseArgs;
use Slim\Http\Request;
use Slim\Http\Response;


class BaseHttpServer extends SlimHttpServer
{

    protected function constructSlimApp(): App
    {
        $container = new Container();
        $container['foundHandler'] = function () {
            return new RequestResponseArgs();
        };
        return new App($container);
    }

    protected function setupSlimApp(App $app)
    {
        $app->get("/[{hi}]", function (Request $request, Response $response, $hi) {
            echo "Hello guys !";
            if (!empty($hi)) {
                echo PHP_EOL . "Especially : " . $hi;
            }
            return $response;
        });
    }
}