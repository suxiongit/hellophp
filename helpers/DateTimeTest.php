<?php
require_once 'Base.php';
require_once 'DateTime.php';

use bear\helpers\Base;
use bear\helpers\DateTime;

Base::charsetUTF8();
/*
 * 定义需要测试的方法
 */

function testFitTime()
{
    echo DateTime::fitTime();
}

function testGetBeginTime()
{
    $time = strtotime('2015-03-09 00:00:00');
    echo $time;
    echo '<br />调用方法前：<br />';
    echo DateTime::getBeginTime($time);
}

function testGetEndTime()
{
    $time = strtotime('2015-03-09 23:59:59');
    echo $time;
    echo '<br />调用方法前：<br />';
    echo DateTime::getEndTime($time);
}

function testGetBeginEndTime()
{
    // 时间是不是在本周内
    $weekOfBegin = DateTime::getBeginTime(null, 'week');
    $weekOfEnd = DateTime::getEndTime(null, 'week');
    
    $date1 = '2015-12-01';
    echo $date1;
    $time = strtotime($date1);
    if ($time >= $weekOfBegin && $time <= $weekOfEnd) {
        echo ' 本周内<br />';
    } else {
        echo ' 不是本周<br />';
    }
    $date2 = '2015-12-10';
    echo $date2;
    $time = strtotime($date2);
    if ($time >= $weekOfBegin && $time <= $weekOfEnd) {
        echo ' 本周内<br />';
    } else {
        echo ' 不是本周<br />';
    }
    $date3 = '2015-12-14';
    echo $date3;
    $time = strtotime($date3);
    if ($time >= $weekOfBegin && $time <= $weekOfEnd) {
        echo ' 本周内<br />';
    } else {
        echo ' 不是本周<br />';
    }
    $date4 = '2015-12-06';
    echo $date4;
    $time = strtotime($date4);
    if ($time >= $weekOfBegin && $time <= $weekOfEnd) {
        echo ' 本周内<br />';
    } else {
        echo ' 不是本周<br />';
    }
}

function testGetExpireTime()
{
    echo DateTime::getExpireTime();
}

function testThisDate()
{
    // $date = '2014-05-13 11:52:27';//1399953147
    $date = time();
    if (DateTime::thisDate($date)) {
        echo '今年内';
    } else {
        echo '不是今年';
    }
}

function testValidDate()
{
    $date = "2013-09-10";
    echo $date . ' ' . (DateTime::validDate($date) ? '有效' : '无效') . '<br/>';
    
    $date = "2013-09-ha";
    echo $date . ' ' . (DateTime::validDate($date) ? '有效' : '无效') . '<br/>';
    
    $date = "2012-02-29";
    echo $date . ' ' . (DateTime::validDate($date) ? '有效' : '无效') . '<br/>';
    
    $date = "2013-02-29";
    echo $date . ' ' . (DateTime::validDate($date) ? '有效' : '无效') . '<br/>';
    
    $date = "2013-01-20";
    echo $date . ' ' . (DateTime::validDate($date) ? '有效' : '无效') . '<br/>';
    
    $date = "2013/01/20";
    echo $date . ' ' . (DateTime::validDate($date) ? '有效' : '无效') . '<br/>';
    
    $date = "2013.01.20";
    echo $date . ' ' . (DateTime::validDate($date) ? '有效' : '无效') . '<br/>';
    
    $date = "2013-01-20 08:59:59";
    echo $date . ' ' . (DateTime::validDate($date) ? '有效' : '无效') . '<br/>';
}

function testInTime()
{
    $datetime = '2014-9-17 10:46:04';
    $minutes = 60;
    if (DateTime::inTime($datetime, $minutes)) {
        echo $datetime . ' 在 ' . $minutes . ' 分钟以内';
    } else {
        echo $datetime . ' 不在 ' . $minutes . ' 分钟以内';
    }
    
    echo '场景：商品售后保障服务（本产品承诺7天内免费退货，15天包换，3年保修。）<br/>';
    $buyTime = '2010-9-10 11:09:11';
    echo '本产品购买时间：' . $buyTime . ' 当前时间：' . date('Y-m-d H:i:s') . '<br/>结果：';
    
    // 7天内免费退货
    if (DateTime::inTime($buyTime, 7 * 24 * 60)) {
        echo ' 可享受7天内免费退货！';
    } elseif (DateTime::inTime($buyTime, 15 * 24 * 60)) { // 15天包换
        echo ' 可享受15天包换！';
    } elseif (DateTime::inTime($buyTime, 3 * 365 * 24 * 60)) { // 3年保修
        echo ' 可享受3年保修！';
    } else {
        echo ' 抱歉，您已无售后保障服务！';
    }
}

function testOutTime()
{
    $datetime = '2015-9-17 10:46:04';
    $minutes = 60;
    if (DateTime::outTime($datetime, $minutes)) {
        echo $datetime . ' 在 ' . $minutes . ' 分钟以外';
    } else {
        echo $datetime . ' 在 ' . $minutes . ' 分钟之内';
    }
}

function testGetMonth()
{
    echo '上一月：';
    echo DateTime::getMonth(- 1);
    echo '<br/>这个月：';
    echo DateTime::getMonth();
    echo '<br/>下一月：';
    echo DateTime::getMonth(1);
    echo '<br/>';
    echo '<br/>上一月：';
    echo DateTime::getMonth(- 1, 'ym');
    echo '<br/>这个月：';
    echo DateTime::getMonth(0, 'ym');
    echo '<br/>下一月：';
    echo DateTime::getMonth(1, 'ym');
}

function testInNaturalWeek()
{
    $date = $_REQUEST['date'];
    if (empty($date)) {
        echo '请传参数date';
        return;
    }
    echo $date;
    if (DateTime::inNaturalWeek($date)) {
        echo '本周内';
    } else {
        echo '不是本周内';
    }
}

function testDaysBetweenDates()
{
    $date1 = $_GET['date1'];
    $date2 = $_GET['date2'];
    echo $date1, '和', $date2, '相隔的天数为:';
    echo DateTime::daysBetweenDates($date1, $date2);
}

function testGetValidYaM()
{
    $date = DateTime::getValidYaM($_GET['y'], $_GET['m']);
    print_r($date);
}

function testGetBirthday()
{
    $mydate = '1990-11-24';
    echo '出生：' . $mydate . ' 年龄：' . DateTime::getBirthday($mydate);
}

function testGetAnimal()
{
    $year = 1990;
    echo $year . '年属' . DateTime::getAnimal($year);
}

function testGetConstellation() {
    $month = 11;
    $day = 24;
    echo $month.'月'.$day.'日是'.DateTime::getConstellation($month, $day);
}

/*
 * 测试入口
 */

if (! defined('INDEX_TEST')) {
    if (isset($_REQUEST['method'])) {
        $method = 'test' . ucfirst($_REQUEST['method']);
        echo '<strong>' . $method . ':</strong>';
        echo '<br/>----------------------------------------------<br/>';
        $method();
        echo '<br/><br/><br/><br/>';
    } else {
        echo '请传参数method，测试您所需的方法！';
    }
}



