<?php
/**
 * This file contains all functions to get data for chart.js on statistics.php
 *
 * @package default
 */

if (!defined('NICE_PROJECT')) {
    die('Permission denied.');
}

/**
 * returns array with all possible colors
 * @return string[] array with possible colors
 */
function colorCounter()
{
    return array(
        "39,135,28",
        "211,47,57"
    );
}

/**
 * formats correct for login/user statistical data
 * @param array $Input structured request data
 * @return array structured result data for chart tool
 */
function loginStatistics($Input)
{
    $dataI = $Input['data'];
    $result = array();
    foreach ($dataI as $dateI) {
        $amount = $dateI['Amount'];
        $type = $dateI['type'];
        $data = array();
        switch ($type) {
            case 'D':
                $data = getStatisticalDataLastDays($amount);
                break;
            case 'W':
                $data = getStatisticalDataLastWeeks($amount);
                break;
            case 'M':
                $data = getStatisticalDataLastMonth($amount);
                break;
            case 'Y':
                $data = getStatisticalDataLastYear($amount);
                break;
        }
        $guestData = array();
        $userData = array();
        foreach ($data as $date) {
            if ($date['type'] == 'user') {
                $userData[] = array(
                    'x' => $date['time'],
                    'y' => $date['counter']
                );
            } else if ($date['type'] == 'guest') {
                $guestData[] = array(
                    'x' => $date['time'],
                    'y' => $date['counter']
                );
            }
        }
        $userData = fillUnknownData($userData, $amount, $type);
        $guestData = fillUnknownData($guestData, $amount, $type);
        $UserDataset = createGraph($userData, colorCounter()[0], "Nutzer", true);
        $GuestDataset = createGraph($guestData, colorCounter()[1], "Gäste");
        $result[] = array_merge($dateI, array('data' => array($UserDataset, $GuestDataset)));
    }
    return $result;
}

/**
 * creates structures for graphs
 * @param array $data data to show
 * @param string $color rgba background color
 * @param string $label the name which should be stand for the line of things
 * @param boolean $fill defines if graph is filled; standard is false
 * @return array return structured information
 */
function createGraph($data, $color, $label, $fill = false)
{
    $colorBg = "rgba(" . $color . ",0.4)";
    $colorFont = "rgba(" . $color . ",1)";
    $Bg = array();
    $font = array();
    if (count($data) > 0) {
        for ($i = 0; $i < count($data); $i++) {
            $Bg[] = $colorBg;
            $font[] = $colorFont;
        }
    } else {
        $Bg[] = $colorBg;
        $font[] = $colorFont;
    }
    $Dataset = array(
        'label' => $label,
        'data' => $data,
        'backgroundColor' => $Bg,
        'borderColor' => $font,
        'borderWidth' => 3,
        "lineTension" => 0,
        "fill" => $fill
    );
    return $Dataset;
}

/**
 * inserts missing dates
 * @param array $statisticalData given statistical data
 * @param int $periodeAmount number of amount of timespan of type
 * @param string $type timespan is given in month, year, days, weeks
 * @return array with completed data
 */
function fillUnknownData($statisticalData, $periodeAmount, $type)
{
    $dates = array();
    $identifier = "";
    switch ($type) {
        case 'D':
            $identifier = "D";
            break;
        case 'W':
            $identifier = "W";
            break;
        case 'M':
            $identifier = "M";
            break;
        case 'Y':
            $identifier = "Y";
            break;
    }
    $periode = new DateInterval("P" . $periodeAmount . $identifier);
    $enddate = new DateTime(date("Y-m-d"));
    $enddate->sub($periode);
    $dates_periode_gen = new DatePeriod(
        new DateTime($enddate->format('Y-m-d')),
        new DateInterval('P1D'),
        new DateTime(date("Y-m-d"))
    );
    foreach ($statisticalData as $date) {
        $dates[] = $date['x'];
    }
    foreach ($dates_periode_gen as $value) {
        $date = $value->format('Y-m-d');
        if (in_array($date, $dates) == false) {
            $statisticalData[] = array('x' => $date, 'y' => 0);
        }
    }
    $test = true;
    while ($test) {
        $test = false;
        for ($i = 0; $i < count($statisticalData) - 1; $i++) {
            if ($statisticalData[$i]['x'] > $statisticalData[$i + 1]['x']) {
                $bucket = $statisticalData[$i];
                $statisticalData[$i] = $statisticalData[$i + 1];
                $statisticalData[$i + 1] = $bucket;
                $test = true;
            }
        }
    }
    if ($statisticalData[count($statisticalData) - 1]['x'] !== date('Y-m-d')) {
        $statisticalData[] = array('x' => date('Y-m-d'), 'y' => 0);
    }
    return $statisticalData;
}

/**
 * formats correct for new users statistical data
 * @param array $Input structured request data
 * @return array structured result data for chart tool
 */
function NewUsersStatistics($Input)
{
    $dataI = $Input['data'];
    $result = array();
    foreach ($dataI as $dateI) {
        $amount = $dateI['Amount'];
        $type = $dateI['type'];
        $data = array();
        switch ($type) {
            case 'D':
                $data = getNewUsersStatisticalDataLastDays($amount);
                break;
            case 'W':
                $data = getNewUsersStatisticalDataLastWeeks($amount);
                break;
            case 'M':
                $data = getNewUsersStatisticalDataLastMonth($amount);
                break;
            case 'Y':
                $data = getNewUsersStatisticalDataLastYear($amount);
                break;
        }
        $finalData = array();
        foreach ($data as $date) {
            $finalData[] = array(
                'x' => $date['time'],
                'y' => $date['counter']
            );
        }
        $finalData = fillUnknownData($finalData, $amount, $type);
        $Dataset = createGraph($finalData, colorCounter()[0], "Nutzer", true);
        $result[] = array_merge($dateI, array('data' => array($Dataset)));
    }
    return $result;
}

/**
 * formats correct data for new/edited pictures or stories statistical data
 * @param array $Input structured request data
 * @param string $Source defines where the data comes from
 * @return array structured result data for chart tool
 */
function getStatistics($Input, $Source)
{
    $dataI = $Input['data'];
    $result = array();
    $datas = array();
    foreach ($dataI as $dateI) {
        $amount = $dateI['Amount'];
        $type = $dateI['type'];
        if ($type == null) {
            $type = "COSE";
        }
        $data = array();
        switch ($type) {
            case 'D':
                if ($Source == "pic") {
                    $data = getNewPicturesStatisticalDataLastDays($amount);
                } else if ($Source == "story") {
                    $data = getNewStoryStatisticalDataLastDays($amount);
                } else if ($Source == "contact") {
                    $data = getContactStatisticalDataLastDays($amount);
                }
                break;
            case 'W':
                if ($Source == "pic") {
                    $data = getNewPicturesStatisticalDataLastWeeks($amount);
                } else if ($Source == "story") {
                    $data = getNewStoryStatisticalDataLastWeeks($amount);
                } else if ($Source == "contact") {
                    $data = getContactStatisticalDataLastWeeks($amount);
                }
                break;
            case 'M':
                if ($Source == "pic") {
                    $data = getNewPicturesStatisticalDataLastMonth($amount);
                } else if ($Source == "story") {
                    $data = getNewStoryStatisticalDataLastMonth($amount);
                } else if ($Source == "contact") {
                    $data = getContactStatisticalDataLastMonth($amount);
                }
                break;
            case 'Y':
                if ($Source == "pic") {
                    $data = getNewPicturesStatisticalDataLastYear($amount);
                } else if ($Source == "story") {
                    $data = getNewStoryStatisticalDataLastYear($amount);
                } else if ($Source == "contact") {
                    $data = getContactStatisticalDataLastYear($amount);
                }
                break;
        }
        foreach ($data as $date) {
            if ($date['type'] == null) {
                $date['type'] = "COSE";
            }
            if (array_key_exists($date['type'], $datas) === false) {
                $datas[$date['type']] = array();
            }
            $datas[$date['type']][] = array(
                'x' => $date['time'],
                'y' => $date['counter']
            );
        }
        $Dataset = array();
        $colorId = 0;
        foreach (array_keys($datas) as $key) {
            $datas[$key] = correctData($datas[$key]);
            $datas[$key] = fillUnknownData($datas[$key], $amount, $type);
            $Dataset[] = createGraph($datas[$key], colorCounter()[$colorId], $key, false);
            $colorId = $colorId + 1;
            if (count(colorCounter()) <= $colorId) {
                $colorId = 0;
            }
        }
        foreach (getAllApiTokens() as $api)
        {
            if (in_array($api['name'], array_keys($datas)) == false){
                $datas[$api['name']] = fillUnknownData(array(), $amount, $type);
                $Dataset[] = createGraph($datas[$api['name']], colorCounter()[$colorId], $api['name'], false);
                $colorId = $colorId + 1;
                if (count(colorCounter()) <= $colorId) {
                    $colorId = 0;
                }
            }
        }
        $result[] = array_merge($dateI, array('data' => $Dataset));
    }
    return $result;
}

/**
 * corrects given data
 * @param array $inputArray structured input array
 * @return array structured results
 */
function correctData($inputArray) {
    $orderdByDate = array();
    $dates = array();
    foreach ($inputArray as $data) {
        if (in_array($data['x'], $dates) == false) {
            $dates[] = $data['x'];
        }
    }
    foreach ($dates as $date) {
        $orderdByDate[$date] = 0;
    }
    foreach ($inputArray as $data) {
        if (is_int($data['y']) == false) {
            $orderdByDate[$data['x']] += intval($data['y']);
        }
    }
    $result = array();
    foreach (array_keys($orderdByDate) as $key) {
        $result[] = array('x' => $key, 'y' => $orderdByDate[$key]);
    }
    return $result;
}