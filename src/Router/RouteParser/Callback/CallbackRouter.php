<?php
namespace Strata\Router\RouteParser\Callback;

use Strata\Router\Router;
use Strata\Router\RouteParser\Callback\CallbackRouteMatch;

class CallbackRouter extends Router
{
    public static function factory($ctrl, $action)
    {
        $router = new self();
        return $router->generate($ctrl, $action);
    }

    public function generate($controllerName, $action)
    {
        $this->route = new CallbackRoute();
        $this->route->addPossibilities(array($controllerName, $action));
        return array($this, "run");
    }

    public function run()
    {
        $this->route->arguments = func_get_args();
        return parent::run();
    }
}
