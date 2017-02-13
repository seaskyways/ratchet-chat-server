<?php
/**
 * Created by IntelliJ IDEA.
 * User: Ahmad
 * Date: 13/02 Feb/2017
 * Time: 9:29 PM
 */
namespace MyApp\Server;

use Slim\App;
use Slim\Http\Request;

abstract class SlimHttpServer extends SimpleHttpServer
{

    private $app;

    function __construct()
    {
        $this->app = $this->constructSlimApp();
        $this->setupSlimApp($this->app);
    }

    protected function constructSlimApp(): App
    {
        return new App;
    }

    protected abstract function setupSlimApp(App $app);

    final function getRequestResponse(Request $request = null, $response)
    {
        return $this->app->__invoke($request, $response);
    }
}