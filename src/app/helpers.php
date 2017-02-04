<?php

if (!function_exists('auth_user')) {
    /**
     * Get the auth_user.
     *
     * @return mixed
     */
    function auth_user()
    {
        return app(\Dingo\Api\Auth\Auth::class)->user();
    }
}

if (!function_exists('dingo_route')) {
    /**
     * 根据别名获得url.
     *
     * @param string $version
     * @param string $name
     * @param string $params
     *
     * @return string
     */
    function dingo_route($version, $name, $params = [])
    {
        return app(\Dingo\Api\Routing\UrlGenerator::class)->version($version)->route($name, $params);
    }
}

if (!function_exists('app_path')) {
    /**
     * Get the path to the application folder.
     *
     * @param  string $path
     *
     * @return string
     */
    function app_path($path = '')
    {
        return app('path') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}
