<?php

function enformat_date($date) {
    if(empty($date))
        return null;
    $date = explode("-", $date);
    $temp = $date[0];
    $date[0] = $date[2];
    $date[2] = $temp;
    return implode("-", $date);
}

function next_day($now, $delta = 1) {
    return mktime(0, 0, 0, date("m", $now), date("d", $now) + $delta, date("Y", $now));
}

function next_month($now, $delta = 1) {
    return mktime(0, 0, 0, date("m", $now) + $delta, date("d", $now), date("Y", $now));
}

function next_year($now, $delta = 1) {
    return mktime(0, 0, 0, date("m", $now), date("d", $now), date("Y", $now) + $delta);
}

function padding_date($i) {
    if ($i < 10)
        return "0$i";
    return $i;
}

function date_id($format, $timestamp = null) {
    if ($timestamp == null)
        //$timestamp = time();
        return null;

    $retval = "";
    $format = str_split($format);
    foreach ($format as $f) {
        switch ($f) {
            case "l":
                $retval .= day_id((int) date("w", $timestamp) + 1);
                break;
            case "D":
                $retval .= day_id((int) date("w", $timestamp) + 1, false);
                break;
            case "F":
                $retval .= month_id((int) date("n", $timestamp));
                break;
            case "M":
                $retval .= month_id((int) date("n", $timestamp), false);
                break;
            default:
                $retval .= date($f, $timestamp);
                break;
        }
    }

    return $retval;
}

function day_id($i = null, $is_full = true) {
    $days = array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu");
    if ($i == null)
        return $days;
    $retval = $days[$i - 1];
    if (!$is_full)
        return substr($retval, 0, 3);
    return $retval;
}

function month_id($i = null, $is_full = true) {
    $months = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
    if ($i == null)
        return $months;
    $retval = $months[$i - 1];
    if (!$is_full)
        return substr($retval, 0, 3);
    return $retval;
}

function month_to_year($nmonths) {
    $nyears = floor($nmonths / 12);
    $nmonths = $nmonths % 12;
    return "$nyears tahun, $nmonths bulan";
}

function time_diff($one, $two) {
    $diff = _date_diff($one, $two);
    return $diff["y"] . " tahun, " . $diff["m"] . " bulan, " . $diff["d"] . " hari";
}

function _date_range_limit($start, $end, $adj, $a, $b, $result) {
    if ($result[$a] < $start) {
        $result[$b] -= intval(($start - $result[$a] - 1) / $adj) + 1;
        $result[$a] += $adj * intval(($start - $result[$a] - 1) / $adj + 1);
    }

    if ($result[$a] >= $end) {
        $result[$b] += intval($result[$a] / $adj);
        $result[$a] -= $adj * intval($result[$a] / $adj);
    }

    return $result;
}

function _date_range_limit_days($base, $result) {
    $days_in_month_leap = array(31, 31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    $days_in_month = array(31, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

    $base = _date_range_limit(1, 13, 12, "m", "y", $base);

    $year = $base["y"];
    $month = $base["m"];

    if (!$result["invert"]) {
        while ($result["d"] < 0) {
            $month--;
            if ($month < 1) {
                $month += 12;
                $year--;
            }

            $leapyear = $year % 400 == 0 || ($year % 100 != 0 && $year % 4 == 0);
            $days = $leapyear ? $days_in_month_leap[$month] : $days_in_month[$month];

            $result["d"] += $days;
            $result["m"]--;
        }
    } else {
        while ($result["d"] < 0) {
            $leapyear = $year % 400 == 0 || ($year % 100 != 0 && $year % 4 == 0);
            $days = $leapyear ? $days_in_month_leap[$month] : $days_in_month[$month];

            $result["d"] += $days;
            $result["m"]--;

            $month++;
            if ($month > 12) {
                $month -= 12;
                $year++;
            }
        }
    }

    return array($base, $result);
}

function _date_normalize($base, $result) {
    $result = _date_range_limit(0, 60, 60, "s", "i", $result);
    $result = _date_range_limit(0, 60, 60, "i", "h", $result);
    $result = _date_range_limit(0, 24, 24, "h", "d", $result);
    $result = _date_range_limit(0, 12, 12, "m", "y", $result);

    list($base, $result) = _date_range_limit_days($base, $result);

    $result = _date_range_limit(0, 12, 12, "m", "y", $result);

    return array($base, $result);
}

function _date_diff($one, $two) {
    $invert = false;
    if ($one > $two) {
        list($one, $two) = array($two, $one);
        $invert = true;
    }

    $key = array("y", "m", "d", "h", "i", "s");
    $a = array_combine($key, array_map("intval", explode(" ", date("Y m d H i s", $one))));
    $b = array_combine($key, array_map("intval", explode(" ", date("Y m d H i s", $two))));

    $result = array();
    $result["y"] = $b["y"] - $a["y"];
    $result["m"] = $b["m"] - $a["m"];
    $result["d"] = $b["d"] - $a["d"];
    $result["h"] = $b["h"] - $a["h"];
    $result["i"] = $b["i"] - $a["i"];
    $result["s"] = $b["s"] - $a["s"];
    $result["invert"] = $invert ? 1 : 0;
    $result["days"] = intval(abs(($one - $two) / 86400));

    if ($invert) {
        list($a, $result) = _date_normalize($a, $result);
    } else {
        list($b, $result) = _date_normalize($b, $result);
    }

    return $result;
}
