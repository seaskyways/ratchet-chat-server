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

class AppHttpServer extends SlimHttpServer
{

    private $twig;

    function __construct()
    {
        parent::__construct();
        $this->twig = $GLOBALS["twig"];
    }

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
        $app->get("[/]", function (Request $request, Response $response) {
            echo $GLOBALS["twig"]->render("chat-client.twig");
            return $response;
        });

        $allowedDirs = ["js", "css", "fonts"];
        $dirs = join("|", $allowedDirs);
        $app->get("/{dir: $dirs }/{file}", function (Request $r, Response $response, $dir, $file) {
            $filePath = dirname(__DIR__, 2) . "/$dir/$file";

            if (file_exists($filePath)) {
                $response
                    ->withHeader("content-type", mime_content_type($filePath))
                    ->write(file_get_contents($filePath));
            } else {
                $response
                    ->withHeader("Content-Type", "text/html")
                    ->withStatus(404, "File not found")
                    ->write($GLOBALS["twig"]->render("error.twig"));
            }
            return $response;
        });
    }
}