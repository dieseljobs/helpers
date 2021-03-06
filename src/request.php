<?php

/**
 * Get actual client IP address
 * Detects if redirected from Load Balancer via HTTP_X_FORWARDED_FOR header
 *
 * @return string
 */
function client_ip()
{
    // catch ip spoofer if present
    if ( $spoof_ip = config('app.spoof_ip') ) return $spoof_ip;

    // otherwise process for request
    $request = request();

    // catch requests forwarded by load balancer
    if ($request->server('HTTP_X_FORWARDED_FOR')) {
        $clientIpAddress = $request->server('HTTP_X_FORWARDED_FOR');
    // else get normal request
    } else {
        $clientIpAddress = $request->server('REMOTE_ADDR');
    }

    return explode(', ', $clientIpAddress)[0];
}

/**
 * Determine if request is AJAX (json content type)
 *
 * @return boolean
 */
function is_ajax()
{
    return (bool)preg_match(
        "#application\/json#i",
        request()->header("content-type")
    );
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
 * Get query string
 * Pull from request uri, load balancers send duplicate QUERY_STRING
 *
 * @return string
 */
function query_string()
{
    $uri = request()->server('REQUEST_URI');
    $pieces = preg_split("/\?/", $uri);
    if (isset($pieces[1])) {
        return $pieces[1];
    } else {
        return "";
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
