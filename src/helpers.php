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
        return request()->route()->parameters()[$key];
    } else {
        return null;
    }
}

/**
 * Get timestamp with microtime
 *
 * @return string
 */
function timestamp_micro()
{
    $t = microtime(true);
    $micro = sprintf("%06d",($t - floor($t)) * 1000000);
    $d = new DateTime( date('Y-m-d H:i:s.'.$micro, $t) );
    return $d->format("Y-m-d H:i:s.u");
}

/**
 * Get difference (in time) between two DateTime instances
 *
 * @param  DateTime $datetime1
 * @param  DateTime $datetime2
 * @return string
 */
function time_diff($datetime1, $datetime2)
{
    $interval = $datetime1->diff($datetime2);
    $h = $interval->format('%h');
    $i = $interval->format('%i');
    $s = $interval->format('%s');
    $u = ($datetime2->format('u') + 1000000) - $datetime1->format('u');
    if ($u >= 1000000) {
        $u = ($u - 1000000);
        $s++;
    }
    return "{$h}:{$i}:{$s}.{$u}";
}
