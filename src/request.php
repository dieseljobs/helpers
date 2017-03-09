<?php

/**
 * Get actual client IP address
 * Detects if redirected from Load Balancer via HTTP_X_FORWARDED_FOR header
 *
 * @return string
 */
function client_ip()
{
    $request = request();
    if ($request->server('HTTP_X_FORWARDED_FOR')) {
        $clientIpAddress = $request->server('HTTP_X_FORWARDED_FOR');
    } else {
        $clientIpAddress = $request->server('REMOTE_ADDR');
    }
    return $clientIpAddress;
}

/**
 * Determine if request is AJAX (json content type)
 *
 * @return boolean
 */
function is_ajax()
{
    return request()->ajax();
    /*
    return (bool)preg_match(
        "#application\/json#i",
        request()->header("content-type")
    );
    */
}

/**
 * Get a parameter from request route by key
 *
 * @param  string $key
 * @return string
 */
function route_param($key)
{
    $route_params = request()->route()->parameters();
    if (isset($route_params[$key])) {
        return $route_params[$key];
    } else {
        return null;
    }
}

/**
 * Determine if request is coming from human based on user-agent
 *
 * @param  string|null  $agent
 * @return boolean
 */
function is_human($agent = null)
{
    $agent = ($agent) ? $agent : request()->header('User-Agent');
    $pttrn = '#(bot|google|crawler|spider|prerender|facebookexternalhit)#i';
    return (!preg_match($pttrn, $agent) and !is_null($agent));
}
