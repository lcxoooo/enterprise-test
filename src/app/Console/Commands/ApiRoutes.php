<?php

namespace App\Console\Commands;

use Dingo\Api\Routing\RouteCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Dingo\Api\Routing\Router;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class ApiRoutes extends RouteList
{
    /**
     * Dingo router instance.
     *
     * @var \Dingo\Api\Routing\Router
     */
    protected $router;

    /**
     * Array of route collections.
     *
     * @var array
     */
    protected $routes;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'api:routes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all registered API routes';

    /**
     * Array of routerRules.
     *
     * @var array
     */
    protected $routerRulesMap = [];

    /**
     * The table headers for the command.
     *
     * @var array
     */
    protected $headers
        = [
            //            '请求地址',
            '请求方式',
            '资源路径',
            '路由别名',
            '控制器',
            '动作',
            //            '受保护',
            '版本',
            '中间件',
            //            '范围',
            '频率限制'
        ];

    /**
     * Create a new routes command instance.
     *
     * @param \Dingo\Api\Routing\Router $router
     *
     * @return void
     */
    public function __construct(Router $router)
    {
        // Ugly, but we need to bypass the constructor and directly target the
        // constructor on the command class.
        Command::__construct();

        $this->router = $router;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->routes = $this->router->getRoutes();

        parent::fire();
    }

    /**
     * Search URI and Http method
     *
     * @param array $arr
     * @param null  $attribute
     * @param null  $condition
     *
     * @return bool|array
     */
    protected function arr_search($arr, $attribute = null, $condition = null)
    {
        static $str;
        if (!is_array($arr)) {
            return false;
        }
        foreach ($arr as $key => $val) {
            if (is_array($val)) {
                $this->arr_search($val, $attribute, $condition);
            } else {
                if ($key === $condition) {
                    !is_null($attribute) ? $str[$val][] = $attribute : $str[] = $val;
                }
            }
        }

        return $str;
    }

    /**
     * Compile the routes into a displayable format.
     *
     * @return array
     */
    protected function getRoutes()
    {
        $routes = [];

        $this->setRouterRulesMap(app('api.router.adapter')->getRoutes());

        foreach ($this->router->getRoutes() as $collection) {

            /** @var RouteCollection $collection */
            foreach ($collection->getRoutes() as $route) {
                /** @var \Dingo\Api\Routing\Route $route */
                $routes[] = $this->filterRoute([
                    //                    'host' => empty($domain = $route->domain()) ? '前缀:/' . config('api.prefix') . '/' : '域名:' . $domain,
                    'method' => implode('|', $this->routerRulesMap['/' . $route->uri()]),
                    'uri' => '/' . $route->uri(),
                    'name' => $route->getName(),
                    'controller' => substr($name = $route->getActionName(), 21, strpos($name, '@') - 21),
                    'action' => substr($name, strpos($name, '@')),
                    //                    'protected' => $route->isProtected() ? '是' : '否',
                    'versions' => implode(', ', $route->versions()),
                    'middleware' => $this->getMiddleware($route),
                    //                    'scopes' => implode(', ', $route->scopes()),
                    'rate' => empty($rate = $this->routeRateLimit($route)) ? '无限制' : $rate,
                ]);
            }
        }

        if ($sort = $this->option('sort')) {
            $routes = Arr::sort($routes, function ($value) use ($sort) {
                return $value[$sort];
            });
        }

        if ($this->option('reverse')) {
            $routes = array_reverse($routes);
        }

        if ($this->option('short')) {
            $this->headers = ['Method', 'URI', 'Name', 'Version(s)'];

            $routes = array_map(function ($item) {
                return array_only($item, ['method', 'uri', 'name', 'versions']);
            }, $routes);
        }

        return array_filter(array_unique($routes, SORT_REGULAR));
    }

    /**
     * Display the routes rate limiting requests per second. This takes the limit
     * and divides it by the expiration time in seconds to give you a rough
     * idea of how many requests you'd be able to fire off per second
     * on the route.
     *
     * @param \Dingo\Api\Routing\Route $route
     *
     * @return null|string
     */
    protected function routeRateLimit($route)
    {
        list($limit, $expires) = [$route->getRateLimit(), $route->getRateLimitExpiration()];

        if ($limit && $expires) {
            return sprintf('%s req/s', round($limit / ($expires * 60), 2));
        }
    }

    /**
     * Get before filters.
     *
     * @param  \Dingo\Api\Routing\Route $route
     *
     * @return string
     */
    protected function getMiddleware($route)
    {
        return collect($route->middleware())->map(function ($middleware) {
            return $middleware instanceof \Closure ? 'Closure' : $middleware;
        })->implode(',');
    }

    /**
     * Filter the route by URI, Version, Scopes and / or name.
     *
     * @param array $route
     *
     * @return void|array
     */
    protected function filterRoute(array $route)
    {
        $filters = ['name', 'path', 'protected', 'unprotected', 'versions', 'scopes'];

        foreach ($filters as $filter) {
            if ($this->option($filter) && !$this->{'filterBy' . ucfirst($filter)}($route)) {
                return;
            }
        }

        return $route;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        $options = parent::getOptions();

        foreach ($options as $key => $option) {
            if ($option[0] == 'sort') {
                unset($options[$key]);
            }
        }

        return array_merge($options, [
            [
                'sort',
                null,
                InputOption::VALUE_OPTIONAL,
                'The column (domain, method, uri, name, action) to sort by'
            ],
            [
                'versions',
                null,
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL,
                'Filter the routes by version'
            ],
            [
                'scopes',
                'S',
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL,
                'Filter the routes by scopes'
            ],
            ['protected', null, InputOption::VALUE_NONE, 'Filter the protected routes'],
            ['unprotected', null, InputOption::VALUE_NONE, 'Filter the unprotected routes'],
            ['short', null, InputOption::VALUE_NONE, 'Get an abridged version of the routes'],
        ]);
    }

    /**
     * Set The RouterRulesMap
     *
     * @param array
     *
     * @return void
     */
    protected function setRouterRulesMap($routers)
    {
        array_walk($routers, function ($x) {
            /** @var \FastRoute\RouteCollector $x */
            array_map(function ($v) {
                array_walk($v, function ($arr, $httpMethod) {
                    $this->routerRulesMap = array_merge($this->routerRulesMap,
                        $this->arr_search($arr, $httpMethod, 'uri'));
                });
            }, $x->getData());
        }, $routers);
    }

    /**
     * Filter the route by its path.
     *
     * @param array $route
     *
     * @return bool
     */
    protected function filterByPath(array $route)
    {
        return Str::contains($route['uri'], $this->option('path'));
    }

    /**
     * Filter the route by whether or not it is protected.
     *
     * @param array $route
     *
     * @return bool
     */
    protected function filterByProtected(array $route)
    {
        return $this->option('protected') && $route['protected'] == 'Yes';
    }

    /**
     * Filter the route by whether or not it is unprotected.
     *
     * @param array $route
     *
     * @return bool
     */
    protected function filterByUnprotected(array $route)
    {
        return $this->option('unprotected') && $route['protected'] == 'No';
    }

    /**
     * Filter the route by its versions.
     *
     * @param array $route
     *
     * @return bool
     */
    protected function filterByVersions(array $route)
    {
        foreach ($this->option('versions') as $version) {
            if (Str::contains($route['versions'], $version)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Filter the route by its name.
     *
     * @param array $route
     *
     * @return bool
     */
    protected function filterByName(array $route)
    {
        return Str::contains($route['name'], $this->option('name'));
    }

    /**
     * Filter the route by its scopes.
     *
     * @param array $route
     *
     * @return bool
     */
    protected function filterByScopes(array $route)
    {
        foreach ($this->option('scopes') as $scope) {
            if (Str::contains($route['scopes'], $scope)) {
                return true;
            }
        }

        return false;
    }
}
