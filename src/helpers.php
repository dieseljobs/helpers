<?php

/**
 * Absolute path to asset from CDN host
 * If local, use absolute local path
 *
 * @param string $asset
 */
function cdn_asset($asset)
{
    if (env('APP_ENV') == 'local') {
        return env('DOMAIN_PRIMARY') . $asset;
    } else {
        return env('AWS_CLOUDFRONT_ENDPOINT') . $asset;
    }
}

/**
 * Get path to verioned asset using elixir helper
 * If local, don't return versioned asset
 *
 * @param string $asset
 */
function versioned_asset($asset)
{
    if (env('APP_ENV') == 'local') {
        return asset($asset);
    } else {
        return cdn_asset(elixir($asset));
    }
}

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
    if (isset(request()->route()->parameters()[$key])) {
        return request()->route()->parameters()[$key];
    } else {
        return null;
    }
}
