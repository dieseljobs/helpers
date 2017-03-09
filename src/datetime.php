<?php

/**
 * Take any date/time format and enforce to epoch time
 *
 * @param  mixed $date
 * @return integer
 */
function datetime_enforce_integer($date)
{
    if (is_integer($date)) {
        return $date;
    } elseif (is_string($date)) {
        return strtotime(date('Y-m-d'));
    } elseif ($date instanceof DateTime) {
        return $date->getTimestamp();
    } else {
        return null;
    }
}

/**
 * Take any date/time format and enforce to date (only) string
 *
 * @param  mixed $date
 * @return integer
 */
function datetime_enforce_date_string($date)
{
    if (is_integer($date)) {
        return date('Y-m-d', $date);
    } elseif (is_string($date)) {
        return date('Y-m-d', strtotime($date));
    } elseif ($date instanceof DateTime) {
        return $date->format('Y-m-d');
    } else {
        return null;
    }
}

/**
 * Get days ago string
 *
 * @param  mixed $date
 * @return string
 */
function days_ago($date)
{
    $date = datetime_enforce_integer($date);
    $today = strtotime(date('Y-m-d'));
    $daysago = $today - $date;
    $daysago = ceil($daysago / (60 * 60 * 24));
    if ($daysago == 0) {
        return 'Today';
    } elseif ($daysago == 1) {
        return 'Yesterday';
    } elseif ($daysago <= 105) {
        return $daysago . ' days ago';
    } elseif ($daysago <= 365) {
        return round($daysago / 30, 0) . ' months ago';
    } else {
        if (round($daysago / 365, 0) > 1) {
            return round($daysago / 365, 0) . ' years ago';
        } else {
            return '1 year ago';
        }
    }
}

/**
 * Get the beginning-of-day datetime stamp
 *
 * @param  mixed $date
 * @return string
 */
function beginning_of_day($date)
{
    $date = datetime_enforce_date_string($date);
    $date = DateTime::createFromFormat('Y-m-d H:i:s', $date . ' 00:00:00');
    return $date->format('Y-m-d H:i:s');
}

/**
 * Get the end-of-day datetime stamp
 *
 * @param  mixed $date
 * @return string
 */
function end_of_day($date)
{
    $date = datetime_enforce_date_string($date);
    $date = DateTime::createFromFormat('Y-m-d H:i:s', $date . ' 00:00:00');
    $date->modify('+1 day');
    $date->modify('-1 second');
    return $date->format('Y-m-d H:i:s');
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
