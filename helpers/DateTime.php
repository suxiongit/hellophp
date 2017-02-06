<?php
namespace bear\helpers;

/**
 * 日期时间工具类
 * 
 * @author suxiongit
 *
 */
class DateTime
{

    /**
     * 纠正时间
     *
     * @param number $time            
     * @return number
     */
    public static function fitTime($time = null)
    {
        if (is_null($time)) {
            $time = time();
        } else 
            if (! is_numeric($time)) {
                $time = strtotime($time);
            }
        
        return $time;
    }

    /**
     * 获取今天开始的时间
     *
     * @param number $time            
     * @param string $type
     *            DAY|WEEK|MONTH|YEAR
     * @param number $sweek
     *            1=星期一为周的开始日|0=星期日为周的开始日
     * @return number
     */
    public static function getBeginTime($time = null, $type = 'day', $sweek = 1)
    {
        $time = self::fitTime($time);
        
        switch (strtoupper($type)) {
            case 'YEAR':
                // TODO
                break;
            case 'MONTH':
                $time = mktime(0, 0, 0, date('m', $time), 1, date('Y', $time));
                break;
            case 'WEEK':
                $time = mktime(0, 0, 0, date('m', $time), date('d', $time) - date('w', $time) + $sweek, date('Y', $time));
                break;
            case 'DAY':
            default:
                $time = mktime(0, 0, 0, date('m', $time), date('d', $time), date('Y', $time));
        }
        
        return $time;
    }

    /**
     * 获取今天结束的时间
     *
     * @param number $time            
     * @param string $type
     *            DAY|WEEK|MONTH|YEAR
     * @param number $sweek
     *            1=星期一为周的开始日|0=星期日为周的开始日
     * @return number
     */
    public static function getEndTime($time = null, $type = 'DAY', $sweek = 1)
    {
        $time = self::fitTime($time);
        
        switch (strtoupper($type)) {
            case 'YEAR':
                // TODO
                break;
            case 'MONTH':
                $time = mktime(23, 59, 59, date('m', $time), date('t', $time), date('Y', $time));
                break;
            case 'WEEK':
                $time = mktime(23, 59, 59, date('m', $time), date('d', $time) - date('w', $time) + 6 + $sweek, date('Y', $time));
                break;
            case 'DAY':
            default:
                $time = mktime(23, 59, 59, date('m', $time), date('d', $time), date('Y', $time)); // $time = mktime(0,0,0,date('m', $time),date('d', $time)+1,date('Y', $time))-1;
        }
        
        return $time;
    }

    /**
     * 获取到期的时间
     *
     * @param string $time            
     * @param string $type
     *            DAY|WEEK|MONTH|YEAR
     * @param number $sweek
     *            $sweek 1=星期一为周的开始日|0=星期日为周的开始日
     * @return number
     */
    public static function getExpireTime($time = null, $type = 'DAY', $sweek = 1)
    {
        return self::getEndTime($time, $type, $sweek) - self::fitTime($time);
    }

    /**
     * 是否当前日期
     *
     * @param date $date
     *            1989-02-22|604080000|time()
     * @param string $format
     *            Y|m|d
     * @return boolean
     */
    public static function thisDate($date, $format = 'Y')
    {
        if (! is_numeric($date)) {
            $date = strtotime($date);
        }
        return date($format) == date($format, $date);
    }

    /**
     * 验证日期是否有效
     *
     * @param date $date            
     * @param array $formats
     *            日期格式
     * @return boolean
     */
    public static function validDate($date, $formats = array('Y-m-d', 'Y/m/d', 'Y-m-d H:i:s', 'Y/m/d H:i:s'))
    {
        $unixTime = strtotime($date);
        
        // strtotime转换不对，日期格式显然不对。
        if (! $unixTime) {
            return false;
        }
        
        // 校验日期的有效性，只要满足其中一个格式就OK
        foreach ($formats as $format) {
            if (date($format, $unixTime) == $date) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * 在某段时间以内
     *
     * @param datetime $datetime            
     * @param int $minutes
     *            分钟
     * @param string $eq
     *            （true=包含）
     * @return boolean
     */
    public static function inTime($datetime, $minutes, $eq = true)
    {
        if ($eq) {
            return (abs(time() - strtotime($datetime)) / 60) <= $minutes;
        } else {
            return (abs(time() - strtotime($datetime)) / 60) < $minutes;
        }
    }

    /**
     * 在某段时间之外
     *
     * @param datetime $datetime            
     * @param int $minutes
     *            分钟
     * @param string $eq
     *            （true=包含）
     * @return boolean
     */
    public static function outTime($datetime, $minutes, $eq = false)
    {
        if ($eq) {
            return (abs(time() - strtotime($datetime)) / 60) >= $minutes;
        } else {
            return (abs(time() - strtotime($datetime)) / 60) > $minutes;
        }
    }

    /**
     * 获取上/下一个月份
     * PS：在月份有31天的时候，date("Ym", strtotime("-1 month"))这种方法会有问题。
     *
     * @param number $sign
     *            -1=上个月、0=默认（当月）、1=下个月
     * @param string $format
     *            Ym|ym
     * @return boolean string
     */
    public static function getMonth($sign = 0, $format = 'Ym')
    {
        switch ($format) {
            case 'Ym':
                // 得到系统的年月
                $date = date($format);
                // 切割出年份
                $year = substr($date, 0, 4);
                // 切割出月份
                $month = substr($date, 4, 2);
                break;
            case 'ym':
                // 得到系统的年月
                $date = date($format);
                // 切割出年份
                $year = substr($date, 0, 2);
                // 切割出月份
                $month = substr($date, 2, 2);
                break;
            default:
                return false;
        }
        
        if (1 == $sign) {
            // 得到当前月的下一个月
            return date($format, mktime(0, 0, 0, $month + 1, 1, $year));
        } elseif (- 1 == $sign) {
            // 得到当前月的上一个月
            return date($format, mktime(0, 0, 0, $month - 1, 1, $year));
        } else {
            return $date;
        }
    }

    /**
     * 是否在本周内
     *
     * @param string $time            
     * @return boolean
     */
    public static function inNaturalWeek($time = null)
    {
        $time = self::fitTime($time);
        $begin = self::getBeginTime(null, 'week');
        $end = self::getEndTime(null, 'week');
        if ($time >= $begin && $time <= $end) {
            return true;
        }
        return false;
    }

    /**
     * 获取两个日期相隔的天数
     *
     * @param string $date1            
     * @param string $date2            
     * @return number
     */
    public static function daysBetweenDates($date1, $date2)
    {
        return ceil(abs(strtotime($date1) - strtotime($date2)) / 86400);
    }

    /**
     * 获取有效的年月
     *
     * @param number $year            
     * @param number $month            
     * @return number[][]
     */
    public static function getValidYaM($year, $month)
    {
        $yFrom = (int) $year;
        $mFrom = (int) $month;
        $yNow = date('Y');
        $mNow = date('m');
        
        $date = array();
        for ($y = $yFrom; $y <= $yNow; $y ++) {
            $_yam = array();
            $_min = 1;
            $_max = 12;
            if ($yFrom == $y && $yNow == $y) {
                $_min = $mFrom;
                $_max = $mNow;
            } elseif ($yFrom == $y) {
                $_min = $mFrom;
                $_max = 12;
            } elseif ($yNow == $y) {
                $_min = 1;
                $_max = $mNow;
            }
            for ($m = $_min; $m <= $_max; $m ++) {
                if ($m < 10) {
                    $_yam[] = '0' . $m;
                } else {
                    $_yam[] = '' . $m;
                }
            }
            $date[$y] = $_yam;
        }
        return $date;
    }

    /**
     * 计算年龄
     *
     * @param date $mydate
     *            日期，例如：1990-11-24
     * @return number
     */
    public static function getBirthday($mydate)
    {
        $birth = $mydate;
        list ($by, $bm, $bd) = explode('-', $birth);
        $cm = date('n');
        $cd = date('j');
        $age = date('Y') - $by - 1;
        if ($cm > $bm || $cm == $bm && $cd > $bd)
            $age ++;
        return $age;
    }

    /**
     * 计算生肖
     *
     * @param int $year
     *            年份，例如：1990
     * @return string
     */
    public static function getAnimal($year)
    {
        $animals = array(
            '鼠',
            '牛',
            '虎',
            '兔',
            '龙',
            '蛇',
            '马',
            '羊',
            '猴',
            '鸡',
            '狗',
            '猪'
        );
        $key = ($year - 1900) % 12;
        return $animals[$key];
    }

    /**
     * 计算星座
     *
     * @param int $month            
     * @param int $day            
     * @return string
     */
    public static function getConstellation($month, $day)
    {
        $signs = array(
            array(
                '20' => '宝瓶座'
            ),
            array(
                '19' => '双鱼座'
            ),
            array(
                '21' => '白羊座'
            ),
            array(
                '20' => '金牛座'
            ),
            array(
                '21' => '双子座'
            ),
            array(
                '22' => '巨蟹座'
            ),
            array(
                '23' => '狮子座'
            ),
            array(
                '23' => '处女座'
            ),
            array(
                '23' => '天秤座'
            ),
            array(
                '24' => '天蝎座'
            ),
            array(
                '22' => '射手座'
            ),
            array(
                '22' => '摩羯座'
            )
        );
        $key = (int) $month - 1;
        list ($startSign, $signName) = each($signs[$key]);
        if ($day < $startSign) {
            $key = $month - 2 < 0 ? $month = 11 : $month -= 2;
            list ($startSign, $signName) = each($signs[$key]);
        }
        return $signName;
    }
    
    /**
     * 日期是否为空
     * @param string $datetime
     * @return boolean
     */
    public static function isEmpty($datetime) {
        if (empty($datetime) || '0000-00-00 00:00:00' == $datetime) {
            return true;
        }
        return false;
    }
}