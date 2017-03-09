<?php

/**
 * Calculates the great-circle distance between two points, with
 * the Haversine formula.
 *
 * @param float $latitudeFrom Latitude of start point in [deg decimal]
 * @param float $longitudeFrom Longitude of start point in [deg decimal]
 * @param float $latitudeTo Latitude of target point in [deg decimal]
 * @param float $longitudeTo Longitude of target point in [deg decimal]
 * @param float $earthRadius Mean earth radius in [m]
 * @return float Distance between points in [m] (same as earthRadius)
 */
function haversine_great_circle_distance(
    $latitudeFrom,
    $longitudeFrom,
    $latitudeTo,
    $longitudeTo,
    $units = 'miles',
    $earthRadius = 6372.8
)
{
    // convert from degrees to radians
    $latFrom = deg2rad($latitudeFrom);
    $lonFrom = deg2rad($longitudeFrom);
    $latTo = deg2rad($latitudeTo);
    $lonTo = deg2rad($longitudeTo);

    $latDelta = $latTo - $latFrom;
    $lonDelta = $lonTo - $lonFrom;

    $angle = 2 * asin(
        sqrt(
            pow(sin($latDelta / 2), 2) +
            cos($latFrom) *
            cos($latTo) *
            pow(sin($lonDelta / 2), 2)
        )
    );

    $distance = $angle * $earthRadius;
    if ($units == 'miles') {
        $distance = round($distance * 0.621371) . ' miles';
    } else {
        $distance = round($distance) . ' kilometers';
    }
    return $distance;
}
