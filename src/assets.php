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
        return asset($asset);
    } else {
        if ($asset[0] != "/") $asset = "/{$asset}";
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
