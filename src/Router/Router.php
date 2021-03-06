<?php
namespace Strata\Router;

use Exception;

use Strata\Router\RouteParser\Alto\AltoRouteParser;
use Strata\Router\RouteParser\Callback\CallbackRouter;

/**
 * Assigns callback handlers on demand and from the URL context.
 *
 * @package       Strata.Router
 * @link          http://strata.francoisfaubert.com/docs/routes/
 */
class Router {

    /**
     * @var Strata\Router\RouteParser\Route A route that this object will try to execute
     */
    public $route = null;

    /**
     * Generates a dynamic and unique callback ready to use with Wordpress' add_action calls.
     * @param string $ctrl Controller class shortname
     * @param string $action
     * @return array A valid callback for call_user_func
     */
    public static function callback($ctrl, $action)
    {
        return CallbackRouter::factory($ctrl, $action);
    }

    /**
     * Generates a parser for URL based rules, as one may be used to in
     * the world of Model View Controller programming.
     * @param  array  $routes A list of available routes and callbacks
     * @return Strata\Router\RouteParser\Alto\AltoRouteParser  The parser that generates a valid route
     */
    public static function automateURLRoutes($routes = array())
    {
        return AltoRouteParser::factory($routes);
    }

    /**
     * Attemps to run the currently loaded route object.
     * @return mixed Returns what the action function will have returned.
     * @throws  Exception when the route is not instantiated.
     */
    public function run()
    {
        if (is_null($this->route)) {
            throw new Exception("This is an invalid route.");
        }

        $this->route->process();
        if ($this->route->isValid()) {
            $this->route->controller->init();
            $this->route->controller->before();
            $returnData = call_user_func_array(array($this->route->controller, $this->route->action), $this->route->arguments);
            $this->route->controller->after();
            return $returnData;
        }
    }
}


