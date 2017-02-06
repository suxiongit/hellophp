<?php
namespace bear\helpers;

/**
 * 基本工具类
 *
 * @author suxiongit
 *        
 */
class Base
{

    /**
     * 设置默认时区为北京时间（PRC）
     */
    public static function timezonePRC()
    {
        date_default_timezone_set('PRC');
    }

    /**
     * 设置页面的编码为UTF-8
     *
     * @param string $type
     *            text/html,text/xml
     */
    public static function charsetUTF8($type = 'text/html')
    {
        header("Content-Type: {$type}; charset=utf-8"); // <meta http-equiv="content-type" content="text/html; charset=utf-8">
    }

    /**
     * 在php文件里打开错误信息
     */
    public static function openError()
    {
        ini_set("display_errors", "On");
        error_reporting(E_ALL);
    }

    /**
     * 显示变量有易于理解的信息（效果等于查看网页源代码）
     *
     * @param mixed $var            
     */
    public static function printr($var)
    {
        echo '<pre>';
        print_r($var);
        echo '</pre>';
    }

    /**
     * 返回变量的字符串信息
     * PS：var_export($var, true) 返回一个变量的字符串
     *
     * @param mixed $var            
     * @return string
     */
    public static function getPrintd($var)
    {
        ob_start();
        print_r($var);
        $out = ob_get_contents();
        ob_end_clean();
        
        return $out;
    }

    /**
     * 导入某个目录下的所有PHP文件
     *
     * @param string $folder            
     */
    public static function import($folder)
    {
        foreach (glob("{$folder}/*.php") as $filename) {
            require_once $filename;
        }
    }

    /**
     * 截取字符数
     *
     * @param string $sourceStr
     *            截取的字符
     * @param unknown $cutLength
     *            截取的长度
     * @param string $suffix
     *            截取后增加的后缀
     * @return string
     */
    public static function substr($sourceStr, $cutLength, $suffix = '...')
    {
        $returnStr = '';
        $i = 0;
        $n = 0;
        $str_length = strlen($sourceStr); // 字符串的字节数
        while (($n < $cutLength) and ($i <= $str_length)) {
            $temp_str = substr($sourceStr, $i, 1);
            $ascnum = Ord($temp_str); // 得到字符串中第$i位字符的ascii码
            if ($ascnum >= 224) { // 如果ASCII位高与224，
                $returnStr = $returnStr . substr($sourceStr, $i, 3); // 根据UTF-8编码规范，将3个连续的字符计为单个字符
                $i = $i + 3; // 实际Byte计为3
                $n ++; // 字串长度计1
            } elseif ($ascnum >= 192) { // 如果ASCII位高与192，
                $returnStr = $returnStr . substr($sourceStr, $i, 2); // 根据UTF-8编码规范，将2个连续的字符计为单个字符
                $i = $i + 2; // 实际Byte计为2
                $n ++; // 字串长度计1
            } elseif ($ascnum >= 65 && $ascnum <= 90) { // 如果是大写字母，
                $returnStr = $returnStr . substr($sourceStr, $i, 1);
                $i = $i + 1; // 实际的Byte数仍计1个
                $n ++; // 但考虑整体美观，大写字母计成一个高位字符
            } else { // 其他情况下，包括小写字母和半角标点符号，
                $returnStr = $returnStr . substr($sourceStr, $i, 1);
                $i = $i + 1; // 实际的Byte数计1个
                $n = $n + 0.5; // 小写字母和半角标点等与半个高位字符宽...
            }
        }
        if ($str_length > $cutLength) {
            $returnStr = $returnStr . $suffix; // 超过长度时在尾处加上省略号
        }
        return $returnStr;
    }

    /**
     * 数组转字符串（支持多维数组转换）
     *
     * @param array $array            
     * @param array $force
     *            强制更新
     * @param array $separator
     *            分隔符
     * @return string
     */
    public static function arrayToString($array, $force = true, $separator = ',')
    {
        // 定义存储所有字符串的数组
        static $r_arr = array();
        if ($force) {
            $r_arr = array();
        }
        
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    // 递归遍历
                    self::arrayToString($value, false, $separator);
                } else {
                    $r_arr[] = $value;
                }
            }
        } else 
            if (is_string($array)) {
                $r_arr[] = $array;
            }
        
        /*
         * //数组去重 $r_arr = array_unique($r_arr);
         */
        
        $string = implode($separator, $r_arr);
        
        return $string;
    }

    /**
     * XML转数组
     *
     * @param object $xmlObject            
     * @param array $out            
     * @return array
     */
    public static function xmlToArray($xmlObject, $out = array ())
    {
        foreach ((array) $xmlObject as $index => $node)
            $out[$index] = (is_object($node) || is_array($node)) ? self::xmlToArray($node) : $node;
        
        return $out;
    }

    /**
     * XML转数组2
     *
     * @param object $xmlObject            
     * @return array
     */
    public static function xmlToArray2($xmlObject)
    {
        return json_decode(json_encode((array) simplexml_load_string($xmlObject)), 1);
    }

    /**
     * 数组转XML
     *
     * @param array $array            
     * @param string $rootNode            
     * @param string $xml            
     * @return mixed
     */
    public static function arrayToXml($array, $rootNode = 'root', $xml = false)
    {
        if ($xml === false) {
            $xml = new SimpleXMLElement('<' . $rootNode . '/>');
        }
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                self::arrayToXml($value, $rootNode, $xml->addChild($key));
            } else {
                $xml->addChild($key, $value);
            }
        }
        return $xml->asXML();
    }

    /**
     * 将对象转换为多维数组
     *
     * @param object $object            
     * @return array
     */
    public static function objectToArray($object)
    {
        if (is_object($object)) {
            // Gets the properties of the given object
            // with get_object_vars function
            $object = get_object_vars($object);
        }
        
        if (is_array($object)) {
            /*
             * Return array converted to object Using __FUNCTION__ (Magic constant) for recursive call
             */
            return array_map('self::objectToArray', $object);
        } else {
            // Return array
            return $object;
        }
    }

    /**
     * 将多维数组转换为对象
     *
     * @param array $array            
     * @return object
     */
    public static function arrayToObject($array)
    {
        if (is_array($array)) {
            /*
             * Return array converted to object Using __FUNCTION__ (Magic constant) for recursive call
             */
            return (object) array_map('self::arrayToObject', $array);
        } else {
            // Return object
            return $array;
        }
    }

    /**
     * 二维数组排序（PS：如果数组是字符串键名将被保留，数字键将被重新索引。）
     *
     * @param array $array
     *            二维数组
     * @param string $sortKey
     *            排序字段
     * @param string $sortOrder
     *            排序顺序 SORT_ASC|SORT_DESC
     * @return boolean array
     */
    public static function arraySort($array, $sortKey, $sortOrder = SORT_ASC)
    {
        if (is_array($array)) {
            foreach ($array as $subarray) {
                if (is_array($subarray)) {
                    $array2[] = $subarray[$sortKey];
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
        
        array_multisort($array2, $sortOrder, $array);
        
        return $array;
    }

    /**
     * 二维数组排序（PS：默认保留原有的所有键）
     *
     * @param array $array
     *            二维数组
     * @param string $sortKey
     *            排序字段
     * @param string $sortOrder
     *            排序顺序 SORT_ASC|SORT_DESC
     * @param string $resetKey
     *            是否重新索引
     * @return boolean array
     */
    public static function arraySort2($array, $sortKey, $sortOrder = SORT_ASC, $resetKey = false)
    {
        if (! is_array($array)) {
            return false;
        }
        $array2 = $array3 = array();
        foreach ($array as $key => $value) {
            $array2[$key] = $value[$sortKey];
        }
        if (SORT_ASC == $sortOrder) {
            asort($array2);
        } else {
            arsort($array2);
        }
        reset($array2);
        foreach ($array2 as $key => $value) {
            $array3[$key] = $array[$key];
        }
        if ($resetKey) {
            $array3 = array_values($array3);
        }
        return $array3;
    }

    /**
     * 二维数组对象排序
     *
     * @param array $array
     *            二维数组|数组对象
     * @param string $sortKey
     *            排序字段
     * @param string $sortOrder
     *            排序顺序 SORT_ASC|SORT_DESC
     * @return boolean array
     */
    public static function arrayObjectSort($array, $sortKey, $sortOrder = SORT_ASC)
    {
        $array2 = $array3 = array();
        
        if (! is_array($array)) {
            return false;
        }
        
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array2[$key] = $value[$sortKey];
            } elseif (is_object($value)) {
                $array2[$key] = $value->$sortKey;
            } else {
                return false;
            }
        }
        
        if (SORT_DESC === $sortOrder) {
            arsort($array2);
        } else {
            asort($array2);
        }
        
        reset($array2);
        
        foreach ($array2 as $key => $value) {
            $array3[$key] = $array[$key];
        }
        
        return $array3;
    }

    /**
     * 支持版本数组排序
     *
     * @param array $array
     *            版本数组，例：array('5.3.0', '5.2.10', '5.2.9', '5.2.17', '5.3.10', '5.2.8', '5.2.0')
     * @param string $sortOrder
     *            排序顺序 SORT_ASC|SORT_DESC
     * @return array
     */
    public static function arrayVersionSort($array, $sortOrder = SORT_ASC)
    {
        for ($i = 0, $n = count($array); $i < $n; $i ++) {
            for ($j = ($i + 1); $j < $n; $j ++) {
                if ((SORT_ASC == $sortOrder && strnatcasecmp($array[$i], $array[$j]) > 0) || (SORT_DESC == $sortOrder && strnatcasecmp($array[$i], $array[$j]) < 0)) {
                    $tmp = $array[$i];
                    $array[$i] = $array[$j];
                    $array[$j] = $tmp;
                }
            }
        }
        
        return $array;
    }

    /**
     * 最大版本
     *
     * @param array $array            
     * @return Ambigous <>
     */
    public static function maxVersion($array)
    {
        $array = Base::arrayVersionSort($array, SORT_DESC);
        return $array[0];
    }

    /**
     * 最小版本
     *
     * @param array $array            
     * @return Ambigous <>
     */
    public static function minVersion($array)
    {
        $array = Base::arrayVersionSort($array, SORT_ASC);
        return $array[0];
    }

    /**
     * 获取当前时间的毫秒数（PS：毫秒数时间无法正确的格式化）
     *
     * @return number
     */
    public static function getMillisecond()
    {
        list ($msec, $sec) = explode(' ', microtime());
        return (float) sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
    }

    /**
     * 获取客户端IP地址
     *
     * @return string
     */
    public static function getClientIp()
    {
        $ip = null;
        if (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ip = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ip = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ip = getenv('HTTP_FORWARDED');
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        
        return $ip;
    }

    /**
     * IP 转换为对应的地理位置
     *
     * 注：（1）使用纯真IP数据库（QQ IP数据库 纯真版20130815），QQWry.Dat文件放在同一级目录，否则就需要修改$dat_path的内容。
     * （2）QQWry.Dat数据为中文编码GB2312，需要转成UTF-8，以免出现乱码。
     *
     * @param string $ip            
     * @return string
     */
    public static function convertIp($ip)
    {
        $ip1num = 0;
        $ip2num = 0;
        $ipAddr1 = "";
        $ipAddr2 = "";
        $dat_path = dirname(__FILE__) . '/qqwry.dat';
        if (! preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/", $ip)) {
            return 'IP Address Error';
        }
        if (! $fd = @fopen($dat_path, 'rb')) {
            return 'IP date file not exists or access denied';
        }
        $ip = explode('.', $ip);
        $ipNum = $ip[0] * 16777216 + $ip[1] * 65536 + $ip[2] * 256 + $ip[3];
        $DataBegin = fread($fd, 4);
        $DataEnd = fread($fd, 4);
        $ipbegin = implode('', unpack('L', $DataBegin));
        if ($ipbegin < 0)
            $ipbegin += pow(2, 32);
        $ipend = implode('', unpack('L', $DataEnd));
        if ($ipend < 0)
            $ipend += pow(2, 32);
        $ipAllNum = ($ipend - $ipbegin) / 7 + 1;
        $BeginNum = 0;
        $EndNum = $ipAllNum;
        while ($ip1num > $ipNum || $ip2num < $ipNum) {
            $Middle = intval(($EndNum + $BeginNum) / 2);
            fseek($fd, $ipbegin + 7 * $Middle);
            $ipData1 = fread($fd, 4);
            if (strlen($ipData1) < 4) {
                fclose($fd);
                return 'System Error';
            }
            $ip1num = implode('', unpack('L', $ipData1));
            if ($ip1num < 0)
                $ip1num += pow(2, 32);
            
            if ($ip1num > $ipNum) {
                $EndNum = $Middle;
                continue;
            }
            $DataSeek = fread($fd, 3);
            if (strlen($DataSeek) < 3) {
                fclose($fd);
                return 'System Error';
            }
            $DataSeek = implode('', unpack('L', $DataSeek . chr(0)));
            fseek($fd, $DataSeek);
            $ipData2 = fread($fd, 4);
            if (strlen($ipData2) < 4) {
                fclose($fd);
                return 'System Error';
            }
            $ip2num = implode('', unpack('L', $ipData2));
            if ($ip2num < 0)
                $ip2num += pow(2, 32);
            if ($ip2num < $ipNum) {
                if ($Middle == $BeginNum) {
                    fclose($fd);
                    return 'Unknown';
                }
                $BeginNum = $Middle;
            }
        }
        $ipFlag = fread($fd, 1);
        if ($ipFlag == chr(1)) {
            $ipSeek = fread($fd, 3);
            if (strlen($ipSeek) < 3) {
                fclose($fd);
                return 'System Error';
            }
            $ipSeek = implode('', unpack('L', $ipSeek . chr(0)));
            fseek($fd, $ipSeek);
            $ipFlag = fread($fd, 1);
        }
        if ($ipFlag == chr(2)) {
            $AddrSeek = fread($fd, 3);
            if (strlen($AddrSeek) < 3) {
                fclose($fd);
                return 'System Error';
            }
            $ipFlag = fread($fd, 1);
            if ($ipFlag == chr(2)) {
                $AddrSeek2 = fread($fd, 3);
                if (strlen($AddrSeek2) < 3) {
                    fclose($fd);
                    return 'System Error';
                }
                $AddrSeek2 = implode('', unpack('L', $AddrSeek2 . chr(0)));
                fseek($fd, $AddrSeek2);
            } else {
                fseek($fd, - 1, SEEK_CUR);
            }
            while (($char = fread($fd, 1)) != chr(0))
                $ipAddr2 .= $char;
            $AddrSeek = implode('', unpack('L', $AddrSeek . chr(0)));
            fseek($fd, $AddrSeek);
            while (($char = fread($fd, 1)) != chr(0))
                $ipAddr1 .= $char;
        } else {
            fseek($fd, - 1, SEEK_CUR);
            while (($char = fread($fd, 1)) != chr(0))
                $ipAddr1 .= $char;
            $ipFlag = fread($fd, 1);
            if ($ipFlag == chr(2)) {
                $AddrSeek2 = fread($fd, 3);
                if (strlen($AddrSeek2) < 3) {
                    fclose($fd);
                    return 'System Error';
                }
                $AddrSeek2 = implode('', unpack('L', $AddrSeek2 . chr(0)));
                fseek($fd, $AddrSeek2);
            } else {
                fseek($fd, - 1, SEEK_CUR);
            }
            while (($char = fread($fd, 1)) != chr(0)) {
                $ipAddr2 .= $char;
            }
        }
        fclose($fd);
        if (preg_match('/http/i', $ipAddr2)) {
            $ipAddr2 = '';
        }
        $ipaddr = "$ipAddr1 $ipAddr2";
        $ipaddr = preg_replace('/CZ88.NET/is', '', $ipaddr);
        $ipaddr = preg_replace('/^s*/is', '', $ipaddr);
        $ipaddr = preg_replace('/s*$/is', '', $ipaddr);
        if (preg_match('/http/i', $ipaddr) || $ipaddr == '') {
            $ipaddr = 'Unknown';
        }
        return iconv('GB2312', 'UTF-8', $ipaddr);
    }

    /**
     * 是否移动设备
     *
     * @return boolean
     */
    public static function isMobile()
    {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return true;
        }
        /*
         * //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息 if (isset ($_SERVER['HTTP_VIA'])) { //找不到为flase,否则为true return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false; }
         */
        // 脑残法，判断手机发送的客户端标志,兼容性有待提高
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $clientKeywords = array(
                'nokia',
                'sony',
                'ericsson',
                'mot',
                'samsung',
                'htc',
                'sgh',
                'lg',
                'sharp',
                'sie-',
                'philips',
                'panasonic',
                'alcatel',
                'lenovo',
                'iphone',
                'ipod',
                'ipad',
                'blackberry',
                'meizu',
                'android',
                'netfront',
                'symbian',
                'ucweb',
                'windowsce',
                'palm',
                'operamini',
                'operamobi',
                'openwave',
                'nexusone',
                'cldc',
                'midp',
                'wap',
                'mobile'
            );
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientKeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return true;
            }
        }
        // 协议法，因为有可能不准确，放到最后判断
        if (isset($_SERVER['HTTP_ACCEPT'])) {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return true;
            }
        }
        return false;
    }

    /**
     * 实现HTTP请求，支持POST方式和HTTPs协议
     * （需要开启cURL扩展库）
     *
     * @param string $url
     *            请求地址
     * @param mixed $data
     *            请求数据 例如：字符串?var1=value1&var2=value2...或者数组array('var1'=>'value1', 'var2'=>'value2'...)，如果POST是文件就必须要用数组且文件名格式为“@绝对路径”array('file'=>'@/path/to/myfile.jpg')
     * @param string $method
     *            请求方式（GET、POST）
     * @param array $header
     *            请求头部信息 array('Content-Type: application/json', 'Accept: application/json', 'Content-Length: ' . strlen($data))
     * @param string $httpCode
     *            请求状态码
     * @param string $error
     *            错误消息
     * @param number $timeout
     *            等待时间
     * @return boolean mixed
     */
    public static function request($url, $data = null, $method = 'GET', $header = array(), &$httpCode = null, &$error = null, $timeout = 30)
    {
        $method = strtoupper($method);
        if ('' == trim($url) || ! in_array($method, array(
            'GET',
            'POST',
            'DELETE',
        ))) {
            return false;
        }
        
        $ch = curl_init(); // 启动一个 cURL 句柄
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 禁用后cURL将终止从服务端进行验证。(HTTPs)
        curl_setopt($ch, CURLOPT_HEADER, 0); // 启用时会将头文件的信息作为数据流输出。
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); // 设置cURL允许执行的最长秒数。
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout); // 在发起连接前等待的时间，如果设置为0，则无限等待。
        
        if (is_array($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        
        switch ($method) {
            case 'GET':
                if (is_array($data)) {
                    $str = '?';
                    foreach ($data as $k => $v) {
                        $str .= $k . '=' . $v . '&';
                    }
                    $str = substr($str, 0, - 1);
                    $url .= $str;
                } elseif (is_string($data)) {
                    $url .= $data;
                }
                curl_setopt($ch, CURLOPT_URL, $url);
                break;
            case 'POST':
                curl_setopt($ch, CURLOPT_URL, $url); // 要访问的地址
                curl_setopt($ch, CURLOPT_POST, 1); // 启用时会发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样。
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // 提交的表单数据，这个参数可以通过urlencoded后的字符串类似'para1=val1&para2=val2&...'或使用一个以字段名为键值，字段数据为值的数组。如果value是一个数组，Content-Type头将会被设置成multipart/form-data。如果POST是文件就必须要用数组且文件名格式为“@绝对路径”array('file'=>'@/path/to/myfile.jpg')。
                break;
            case 'DELETE':
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
                break;
            default:
                curl_setopt($ch, CURLOPT_URL, $url);
        }
        
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            // echo 'Errno: '.curl_errno($ch);
            $error = curl_error($ch); // 捕抓异常
        }
        
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        curl_close($ch); // 关闭CURL会话
        
        return $result;
    }

    /**
     * POST请求，支持HTTPs协议
     * （需要开启cURL扩展库）
     *
     * @param string $url
     *            请求地址
     * @param string $data
     *            请求数据 例如：字符串?var1=value1&var2=value2...或者数组array('var1'=>'value1', 'var2'=>'value2'...)，如果POST是文件就必须要用数组且文件名格式为“@绝对路径”array('file'=>'@/path/to/myfile.jpg')
     * @param array $header
     *            请求头部信息 array('Content-Type: application/json', 'Accept: application/json', 'Content-Length: ' . strlen($data))
     * @param string $httpCode
     *            请求状态码
     * @param string $error
     *            错误消息
     * @param number $timeout
     *            等待时间
     * @return Ambigous <boolean, mixed>
     */
    public static function postRequest($url, $data = null, $header = array(), &$httpCode = null, &$error = null, $timeout = 30)
    {
        return Base::request($url, $data, 'POST', $header, $httpCode, $error, $timeout);
    }

    /**
     * 301 永久重定向
     *
     * @param string $url            
     */
    public static function redirect301($url)
    {
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: {$url}");
    }

    /**
     * 302 临时重定向
     *
     * @param string $url            
     */
    public static function redirect302($url)
    {
        header("Location: {$url}");
    }

    /**
     * 检测远程Url是否有效
     * (使用前必须开启cURL库)
     *
     * @param string $url            
     * @return boolean
     */
    public static function validUrl($url)
    {
        $ch = curl_init(); // 初始化一个cURL对象
        curl_setopt($ch, CURLOPT_URL, $url); // 设置你需要抓取的URL
        curl_setopt($ch, CURLOPT_NOBODY, 1); // 设置不下载
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        $valid = true;
        if (curl_exec($ch) !== FALSE) { // 运行cURL，请求URL
            $valid = true;
        } else {
            $valid = false;
        }
        
        curl_close($ch); // 关闭URL请求
        
        return $valid;
    }

    /**
     * 检测远程Url是否有效
     *
     * @param string $url            
     * @return boolean
     */
    public static function validUrl2($url)
    {
        if (@file_get_contents($url, 0, NULL, 0, 1)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 检测远程图片是否有效
     * (使用前必须开启cURL库)
     *
     * @param string $url            
     * @return boolean
     */
    public static function validImage($url)
    {
        return Base::validUrl($url);
    }

    /**
     * 检测远程图片是否有效
     *
     * @param string $url            
     * @return boolean
     */
    public static function validImage2($url)
    {
        return Base::validUrl2($url);
    }

    /**
     * 生成缩略图
     *
     * @param string $image
     *            原图片路径
     * @param int $width
     *            缩放宽度
     * @param int $height
     *            缩放高度
     * @param string $thumbPath
     *            缩略图目录
     * @param number $ratioMode
     *            缩放模式（0=标准、1=按宽度、2=按高度、3=非比例缩放）
     * @param boolean $strictMode
     *            严谨模式（针对标准缩放有效，为true时，如果原图片的尺寸小于缩放尺寸，将不进行放大缩放。）
     * @return number boolean
     */
    public static function thumbImage($image, $width, $height, $thumbPath, $ratioMode = 0, $strictMode = 0)
    {
        
        // 创建缩略图目录
        if (! is_dir($thumbPath)) {
            @mkdir("$thumbPath", 0755);
        }
        
        // 缩略图已存在
        $thumb = $thumbPath . basename($image);
        if (file_exists($thumb)) {
            return 2;
        }
        
        // 原图不存在
        if (! file_exists($image)) {
            return - 1;
        }
        
        $maxWidth = $width;
        $maxHeight = $height;
        $toWidth = 0;
        $toHeight = 0;
        
        $size = getimagesize($image);
        $width = $size[0];
        $height = $size[1];
        
        switch ($size[2]) {
            case "1":
                $oldPic = imagecreatefromgif($image);
                break;
            case "2":
                $oldPic = imagecreatefromjpeg($image);
                break;
            case "3":
                $oldPic = imagecreatefrompng($image);
                break;
            default:
                return - 2; // 图片类型不对
        }
        
        // 计算缩放的宽度和高度
        switch ($ratioMode) {
            case 1: // 按宽度（公式：toWidth/width = toHeight/height）
                $toWidth = $maxWidth;
                $toHeight = $toWidth * $height / $width;
                break;
            case 2: // 按高度
                $toHeight = $maxHeight;
                $toWidth = $toHeight * $width / $height;
                break;
            case 3: // 非比例缩放
                $toWidth = $maxWidth;
                $toHeight = $maxHeight;
                break;
            case 0: // 标准
            default: // 默认
                $w_ratio = $width / $maxWidth;
                $h_ratio = $height / $maxHeight;
                if (! $strictMode) {
                    if ($w_ratio < $h_ratio) {
                        $toHeight = $maxHeight;
                        $toWidth = $width * ($maxHeight / $height);
                    } else {
                        $toWidth = $maxWidth;
                        $toHeight = $height * ($maxWidth / $width);
                    }
                } else { // 如果原图片的尺寸小于缩放尺寸，将不进行放大缩放。
                    if ($w_ratio < $h_ratio && $h_ratio > 1) {
                        $toHeight = $maxHeight;
                        $toWidth = $width * ($maxHeight / $height);
                    } else 
                        if (($w_ratio > $h_ratio || $w_ratio == $h_ratio) && $w_ratio > 1) {
                            $toWidth = $maxWidth;
                            $toHeight = $height * ($maxWidth / $width);
                        } else {
                            $toWidth = $maxWidth;
                            $toHeight = $maxHeight;
                        }
                }
        }
        
        $newPic = imagecreatetruecolor($toWidth, $toHeight);
        imagealphablending($newPic, false);
        imagesavealpha($newPic, true);
        
        imagecopyresampled($newPic, $oldPic, 0, 0, 0, 0, $toWidth, $toHeight, $width, $height);
        
        switch ($size[2]) {
            case "1":
                return imagegif($newPic, $thumb);
                break;
            case "2":
                return imagejpeg($newPic, $thumb);
                break;
            case "3":
                return imagepng($newPic, $thumb);
                break;
        }
        
        imagedestroy($oldPic);
        imagedestroy($newPic);
        
        return 1;
    }

    /**
     * 图片比例缩放
     *
     * @param string $image
     *            原图片路径
     * @param int $width
     *            缩放宽度
     * @param int $height
     *            缩放高度
     * @param string $thumbPath
     *            缩略图目录
     * @param number $ratioMode
     *            缩放模式（0=标准、1=按宽度、2=按高度、3=非比例缩放）
     * @param number $strictMode
     *            严谨模式（针对标准缩放有效，为true时，如果原图片的尺寸小于缩放尺寸，将不进行放大缩放。）
     * @return number boolean
     */
    public static function resizeImage($image, $width, $height, $thumbPath, $ratioMode = 0, $strictMode = 0)
    {
        return Base::thumbImage($image, $width, $height, $thumbPath);
    }

    /**
     * 图片加水印
     *
     * 参数：
     * $groundImage 背景图片，即需要加水印的图片，暂只支持GIF,JPG,PNG格式；
     * $waterPos 水印位置，有11种状态，0为随机位置；
     * 1为顶端居左，2为顶端居中，3为顶端居右；
     * 4为中部居左，5为中部居中，6为中部居右；
     * 7为底端居左，8为底端居中，9为底端居右；10为底端随机；
     * $waterImage 图片水印，即作为水印的图片，暂只支持GIF,JPG,PNG格式；
     * $waterText 文字水印，即把文字作为为水印，支持ASCII码，不支持中文；
     * $textFont 文字大小，值为1、2、3、4或5，默认为5；
     * $textColor 文字颜色，值为十六进制颜色值，默认为#FF0000(红色)；
     *
     * 注意：
     * Support GD 2.0，Support FreeType、GIF Read、GIF Create、JPG 、PNG
     * $waterImage 和 $waterText 最好不要同时使用，选其中之一即可，优先使用 $waterImage。
     * 当$waterImage有效时，参数$waterString、$stringFont、$stringColor均不生效。
     * 加水印后的图片的文件名和 $groundImage 一样。
     */
    public static function addMark($groundImage, $waterPos = 9, $waterImage = "", $waterText = "", $textFont = 1, $textColor = "#FF0000")
    {
        $isWaterImage = FALSE;
        $formatMsg = "暂不支持该文件格式，请用图片处理软件将图片转换为GIF、JPG、PNG格式。";
        
        // 读取水印文件
        if (! empty($waterImage) && file_exists($waterImage)) {
            $isWaterImage = TRUE;
            $water_info = getimagesize($waterImage);
            $water_w = $water_info[0]; // 取得水印图片的宽
            $water_h = $water_info[1]; // 取得水印图片的高
            
            switch ($water_info[2]) { // 取得水印图片的格式
                case 1:
                    $water_im = imagecreatefromgif($waterImage);
                    break;
                case 2:
                    $water_im = imagecreatefromjpeg($waterImage);
                    break;
                case 3:
                    $water_im = imagecreatefrompng($waterImage);
                    break;
                default:
                    die($formatMsg);
            }
        }
        
        // 读取背景图片
        if (! empty($groundImage) && file_exists($groundImage)) {
            $ground_info = getimagesize($groundImage);
            $ground_w = $ground_info[0]; // 取得背景图片的宽
            $ground_h = $ground_info[1]; // 取得背景图片的高
            
            switch ($ground_info[2]) { // 取得背景图片的格式
                case 1:
                    $ground_im = imagecreatefromgif($groundImage);
                    break;
                case 2:
                    $ground_im = imagecreatefromjpeg($groundImage);
                    break;
                case 3:
                    $ground_im = imagecreatefrompng($groundImage);
                    break;
                default:
                    die($formatMsg);
            }
        } else {
            die("需要加水印的图片不存在！" . $groundImage);
        }
        
        // 水印位置
        if ($isWaterImage) { // 图片水印
            $w = $water_w;
            $h = $water_h;
            $label = "图片的";
        } else // 文字水印
{
            $temp = imagettfbbox(ceil($textFont * 3), 0, "./cour.ttf", $waterText); // 取得使用 TrueType 字体的文本的范围
            $w = $temp[2] - $temp[6];
            $h = $temp[3] - $temp[7];
            unset($temp);
            $label = "文字区域";
        }
        // 判断图片大小与水印大小
        // if( ($ground_w<$w) || ($ground_h<$h) )
        // {
        // echo "需要加水印的图片的长度或宽度比水印".$label."还小，无法生成水印！";
        // return;
        // }
        switch ($waterPos) {
            case 0: // 随机
                $posX = rand(0, ($ground_w - $w));
                $posY = rand(0, ($ground_h - $h));
                break;
            case 1: // 1为顶端居左
                $posX = 0;
                $posY = 0;
                break;
            case 2: // 2为顶端居中
                $posX = ($ground_w - $w) / 2;
                $posY = 0;
                break;
            case 3: // 3为顶端居右
                $posX = $ground_w - $w;
                $posY = 0;
                break;
            case 4: // 4为中部居左
                $posX = 0;
                $posY = ($ground_h - $h) / 2;
                break;
            case 5: // 5为中部居中
                $posX = ($ground_w - $w) / 2;
                $posY = ($ground_h - $h) / 2;
                break;
            case 6: // 6为中部居右
                $posX = $ground_w - $w;
                $posY = ($ground_h - $h) / 2;
                break;
            case 7: // 7为底端居左
                $posX = 0;
                $posY = $ground_h - $h;
                break;
            case 8: // 8为底端居中
                $posX = ($ground_w - $w) / 2;
                $posY = $ground_h - $h;
                break;
            case 9: // 9为底端居右
                $posX = $ground_w - $w;
                $posY = $ground_h - $h;
                break;
            case 10: // 10为底端随机
                $posX = rand(0, ($ground_w - $w));
                $posY = $ground_h - $h;
                break;
            default: // 随机
                $posX = rand(0, ($ground_w - $w));
                $posY = rand(0, ($ground_h - $h));
                break;
        }
        
        // 设定图像的混色模式
        imagealphablending($ground_im, true);
        
        if ($isWaterImage) { // 图片水印
            imagecopy($ground_im, $water_im, $posX, $posY, 0, 0, $water_w, $water_h); // 拷贝水印到目标文件
        } else { // 文字水印
            if (! empty($textColor) && (strlen($textColor) == 7)) {
                $R = hexdec(substr($textColor, 1, 2));
                $G = hexdec(substr($textColor, 3, 2));
                $B = hexdec(substr($textColor, 5));
            } else {
                die("水印文字颜色格式不正确！");
            }
            imagestring($ground_im, $textFont, $posX, $posY, $waterText, imagecolorallocate($ground_im, $R, $G, $B));
        }
        
        // 生成水印后的图片
        @unlink($groundImage);
        switch ($ground_info[2]) { // 取得背景图片的格式
            case 1:
                imagegif($ground_im, $groundImage);
                break;
            case 2:
                imagejpeg($ground_im, $groundImage);
                break;
            case 3:
                imagepng($ground_im, $groundImage);
                break;
            default:
                die($errorMsg);
        }
        
        // 释放内存
        if (isset($water_info))
            unset($water_info);
        if (isset($water_im))
            imagedestroy($water_im);
        unset($ground_info);
        imagedestroy($ground_im);
    }

    /**
     * 转化成中文时间
     *
     * 时间的显示逻辑：
     * 1、刚刚 (1分钟内)
     *
     * 2、1分钟前 ~ 60分钟前 (发布一个小时内、显示的时间随着当前时间改变)
     *
     * 3、1小时前 ~ 24小时前(发布一天内)
     *
     * 4、1月1日 00:00 ~ 12月31日 24:00 (发布今年内)
     *
     * 5、20xx年-xx-xx
     *
     * @param string $datetime            
     * @return string
     */
    public static function formatTime($datetime)
    {
        $difftime = abs(time() - strtotime($datetime));
        
        if ($difftime < 60) { // 1分钟内
            return '刚刚';
        } elseif ($difftime < 3600) { // 1小时内
            return intval($difftime / 60) . '分钟前';
        } elseif ($difftime < 86400) { // 一天内（24小时）
            return intval($difftime / 3600) . '小时前';
        } elseif (Base::thisDate($datetime)) { // 今年内
            return date('m月d日 H:i', strtotime($datetime));
        } else {
            return date('Y年m月d日', strtotime($datetime));
        }
        
        return $datetime;
    }

    /**
     * 格式化数字
     *
     * 数字的显示规则：
     * 一万以下： 0 ~ 9999
     * 一万至一亿：1万 ~ 9999万
     * 一亿以上：1亿 、10.2亿
     *
     * @param int $num            
     * @return string
     */
    public static function formatNumber($num)
    {
        if (intval($num) < 10000) {
            return intval($num);
        } else 
            if (intval($num) < 100000000) {
                return round(intval($num) / 10000, 1) . '万';
            } else 
                if (intval($num) >= 100000000) {
                    return round(intval($num) / 100000000, 1) . '亿';
                }
        
        return intval($num);
    }

    /**
     * 格式化文件大小
     * （B、KB、MB、GB、TB、PB、EB、ZB、YB）
     *
     * @param int $size
     *            文件大小，单位byte
     * @return string
     */
    public static function formatFileSize($size)
    {
        $sizeText = array(
            " B",
            " KB",
            " MB",
            " GB",
            " TB",
            " PB",
            " EB",
            " ZB",
            " YB"
        );
        return round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . $sizeText[$i];
    }

    /**
     * 获取文件的大小（返回byte）
     *
     * @param string $uri            
     * @param string $user            
     * @param string $pw            
     * @return int
     */
    public static function getFileSize($uri, $user = '', $pw = '')
    {
        
        // start output buffering
        ob_start();
        // initialize curl with given uri
        $ch = curl_init($uri);
        // make sure we get the header
        curl_setopt($ch, CURLOPT_HEADER, 1);
        // make it a http HEAD request
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        // if auth is needed, do it here
        if (! empty($user) && ! empty($pw)) {
            $headers = array(
                'Authorization: Basic ' . base64_encode($user . ':' . $pw)
            );
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        $okay = curl_exec($ch);
        curl_close($ch);
        // get the output buffer
        $head = ob_get_contents();
        // clean the output buffer and return to previous
        // buffer settings
        ob_end_clean();
        
        // echo '<br>head-->'.$head.'<----end <br>';
        
        // gets you the numeric value from the Content-Length
        // field in the http header
        $regex = '/Content-Length:\s([0-9].+?)\s/';
        $count = preg_match($regex, $head, $matches);
        
        // if there was a Content-Length field, its value
        // will now be in $matches[1]
        if (isset($matches[1])) {
            $size = $matches[1];
        } else {
            $size = 'unknown';
        }
        // $last=round($size/(1024*1024),3);
        // return $last.' MB';
        return $size;
    }

    /**
     * 获取文件名（不包含文件扩展名）
     *
     * @param string $filename            
     * @return string
     */
    public static function getFileName($filename)
    {
        $filename = basename($filename);
        return substr($filename, 0, strrpos($filename, '.'));
    }

    /**
     * 获取文件的扩展名
     *
     * @param string $filename            
     * @param string $ext            
     * @return string mixed
     */
    public static function getFileExtension($filename, $ext = '')
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        if (empty($extension) && isset($ext)) {
            return $ext;
        }
        return $extension;
    }

    /**
     * 判断是否以特定的字符串为开始
     *
     * @param string $str            
     * @param string $needle            
     * @return boolean
     */
    public static function startWith($str, $needle)
    {
        return strpos($str, $needle) === 0;
    }

    /**
     * 判断是否以特定的字符串为开始（使用正则匹配）
     *
     * @param string $str            
     * @param string $needle            
     * @return number
     */
    public static function startWith2($str, $needle)
    {
        return preg_match("|^{$needle}|", $str);
    }

    /**
     * 判断是否以特定的字符串为结束
     *
     * @param string $str            
     * @param string $needle            
     * @return boolean
     */
    public static function endWith($str, $needle)
    {
        return strrchr($str, $needle) == $needle;
    }

    /**
     * 判断是否以特定的字符串为结束（使用正则匹配）
     *
     * @param string $str            
     * @param string $needle            
     * @return number
     */
    public static function endWith2($str, $needle)
    {
        return preg_match("|{$needle}$|", $str);
    }

    /**
     * 生成随机字符串
     *
     * @param integer $len            
     * @return string
     */
    public static function randomStr($len)
    {
        $chars = array(
            "a",
            "b",
            "c",
            "d",
            "e",
            "f",
            "g",
            "h",
            "i",
            "j",
            "k",
            "l",
            "m",
            "n",
            "o",
            "p",
            "q",
            "r",
            "s",
            "t",
            "u",
            "v",
            "w",
            "x",
            "y",
            "z",
            "A",
            "B",
            "C",
            "D",
            "E",
            "F",
            "G",
            "H",
            "I",
            "J",
            "K",
            "L",
            "M",
            "N",
            "O",
            "P",
            "Q",
            "R",
            "S",
            "T",
            "U",
            "V",
            "W",
            "X",
            "Y",
            "Z",
            "0",
            "1",
            "2",
            "3",
            "4",
            "5",
            "6",
            "7",
            "8",
            "9"
        );
        $charsLen = count($chars) - 1;
        shuffle($chars);
        $output = "";
        for ($i = 0; $i < $len; $i ++) {
            $output .= $chars[mt_rand(0, $charsLen)];
        }
        return $output;
    }

    /**
     * 生成随机数字
     *
     * @param integer $len            
     * @return string
     */
    public static function randomNum($len)
    {
        $chars = array(
            "0",
            "1",
            "2",
            "3",
            "4",
            "5",
            "6",
            "7",
            "8",
            "9"
        );
        $charsLen = count($chars) - 1;
        shuffle($chars);
        $output = "";
        for ($i = 0; $i < $len; $i ++) {
            $output .= $chars[mt_rand(0, $charsLen)];
        }
        return $output;
    }

    /**
     * 生成UUID（可以指定前缀）
     *
     * @param string $prefix            
     * @return string
     */
    public static function makeUuid($prefix = "")
    {
        $str = md5(uniqid(mt_rand(), true));
        $uuid = substr($str, 0, 8) . '-';
        $uuid .= substr($str, 8, 4) . '-';
        $uuid .= substr($str, 12, 4) . '-';
        $uuid .= substr($str, 16, 4) . '-';
        $uuid .= substr($str, 20, 12);
        return $prefix . $uuid;
    }

    /**
     * 获取代码所在行
     *
     * @return string
     */
    public static function getErrLoc()
    {
        $loc = null;
        $traces = debug_backtrace();
        foreach ($traces as $trace) {
            if (isset($trace['file'], $trace['line']) && strpos($trace['file'], __FILE__) !== 0) {
                $loc .= "\n在" . $trace['file'] . ' (' . $trace['line'] . '行)';
                break;
            }
        }
        return $loc;
    }

    /**
     * 拼接URL的参数
     *
     * @param array $segments
     *            例：array('name=billgates','age=58','gender=male');
     * @return string
     */
    public static function urlSegments($segments = array(), $firstParam = true)
    { // TODO http_build_query
        return (count($segments) ? ($firstParam ? '?' : '&') . implode('&', $segments) : '');
    }

    /**
     * 生成TOKEN
     *
     * @param string $skey            
     * @param boolean $msec
     *            毫秒数（PS：生成Java的token需要毫秒数）
     * @return string
     */
    public static function getToken($skey, $msec = false)
    {
        $time = time();
        if ($msec) {
            $time = Base::getMillisecond();
        }
        return $time . substr(md5($time . $skey), 16);
    }

    /**
     * 验证TOKEN
     *
     * @param string $token            
     * @param string $skey
     *            加密密钥
     * @param boolean $msec
     *            毫秒数（PS：验证Java的token需要毫秒数）
     * @param number $expire
     *            有效期（单位：分钟）
     * @return boolean
     */
    public static function validToken($token, $skey, $msec = false, $expire = 10)
    {
        $timeLen = $msec ? 13 : 10;
        $time = substr($token, 0, $timeLen);
        // $expire分钟失效
        if (! is_numeric($time) || Base::outTime(date('Y-m-d H:i:s', $time), $expire)) {
            return false; // 时间过期
        }
        $ltoken = substr($token, $timeLen);
        $rtoken = substr(md5($time . $skey), 16);
        if ($ltoken != $rtoken) {
            return false; // 密钥不正确
        }
        return true;
    }

    /**
     * 加密文本
     *
     * @param string $text            
     * @param string $key
     *            加密KEY
     * @return string
     */
    public static function encode($text, $key = 'secret_key')
    {
        $keyArray = str_split($key);
        $keyCount = count($keyArray);
        
        $textArray = str_split($text);
        $textCount = count($textArray);
        
        $result = null;
        for ($i = 0; $i < $textCount; $i ++) {
            $j = $i % $keyCount;
            $result .= chr(ord($textArray[$i]) + ord($keyArray[$j]));
        }
        
        return rawurlencode(base64_encode($result));
    }

    /**
     * 解密文本 @see SBaseUtil::encode();
     *
     * @param string $text            
     * @param string $key
     *            加密KEY
     * @return string
     */
    public static function decode($text, $key = 'secret_key')
    {
        $keyArray = str_split($key);
        $keyCount = count($keyArray);
        
        $textArray = str_split($text);
        $textCount = count($textArray);
        
        $textArray2 = str_split(base64_decode(urldecode($text)));
        $textCount2 = count($textArray2);
        
        $result = array_fill(0, $textCount2, '');
        for ($i = 0; $i < $textCount2; ++ $i) {
            $keyIndex = $i % $keyCount;
            $result[$i] = chr(ord($textArray2[$i]) - ord($keyArray[$keyIndex]));
        }
        return implode('', $result);
    }

    /**
     * 获取顶级域名（例：rom.shuame.com返回的结果为shuame.com）
     *
     * @param string $server
     *            $_SERVER['HTTP_HOST']
     * @return string
     */
    public static function getBaseDomain($server)
    {
        $match = array();
        preg_match("#[\w-]+\.(com|net|org|gov|cc|biz|info|cn|co)\b(\.(cn|hk|uk|jp|tw))*#", $server, $match);
        return $match[0];
    }

    /**
     * 获取邮箱域名
     *
     * @param string $email            
     * @return string
     */
    public static function getEmailDomain($email)
    {
        $list = explode('@', $email);
        
        $domain = 'mail.' . $list[1];
        
        if ('gmail.com' == $list[1]) {
            $domain = 'mail.google.com';
        } elseif ('hotmail.com' == $list[1]) {
            $domain = 'hotmail.com';
        }
        
        return $domain;
    }

    /**
     * 获取请求的参数值
     *
     * @param string $name            
     * @param string $default            
     * @return string null
     */
    public static function getParam($name, $default = null)
    {
        if (is_array($name)) {
            foreach ($name as $_name) {
                if (isset($_REQUEST[$_name])) {
                    return $_REQUEST[$_name];
                }
            }
            return $default;
        } else {
            return isset($_REQUEST[$name]) ? $_REQUEST[$name] : $default;
        }
    }

    /**
     * 按生日获取干支、生肖和星座
     *
     * @param datetime $birth
     *            时间戳、其它时间写法
     * @return array xz=星座、gz=干支、sx=生肖
     */
    public static function getBirthExt($birth)
    {
        if (strstr($birth, '-') === false && strlen($birth) !== 8) {
            $birth = date('Y-m-d', $birth);
        }
        if (strlen($birth) === 8) {
            if (preg_match('/([0-9]{4})([0-9]{2})([0-9]{2})$/i', $birth, $bir)) {
                $birth = "{$bir[1]}-{$bir[2]}-{$bir[3]}";
            }
        }
        if (strlen($birth) < 8) {
            return false;
        }
        $tmpstr = explode('-', $birth);
        if (count($tmpstr) !== 3) {
            return false;
        }
        $y = (int) $tmpstr[0];
        $m = (int) $tmpstr[1];
        $d = (int) $tmpstr[2];
        $result = array();
        $xzdict = array(
            '摩羯',
            '水瓶',
            '双鱼',
            '白羊',
            '金牛',
            '双子',
            '巨蟹',
            '狮子',
            '处女',
            '天秤',
            '天蝎',
            '射手'
        );
        $zone = array(
            1222,
            122,
            222,
            321,
            421,
            522,
            622,
            722,
            822,
            922,
            1022,
            1122,
            1222
        );
        if (100 * $m + $d >= $zone[0] || 100 * $m + $d < $zone[1]) {
            $i = 0;
        } else {
            for ($i = 1; $i < 12; $i ++) {
                if (100 * $m + $d >= $zone[$i] && 100 * $m + $d < $zone[$i + 1]) {
                    break;
                }
            }
        }
        $result['xz'] = $xzdict[$i] . '座';
        $gzdict = array(
            array(
                '甲',
                '乙',
                '丙',
                '丁',
                '戊',
                '己',
                '庚',
                '辛',
                '壬',
                '癸'
            ),
            array(
                '子',
                '丑',
                '寅',
                '卯',
                '辰',
                '巳',
                '午',
                '未',
                '申',
                '酉',
                '戌',
                '亥'
            )
        );
        $i = $y - 1900 + 36;
        $result['gz'] = $gzdict[0][$i % 10] . $gzdict[1][$i % 12];
        $sxdict = array(
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
        $result['sx'] = $sxdict[($y - 4) % 12];
        return $result;
    }

    /**
     * 转大写日期/支票日期
     *
     * @param string $Year            
     * @param string $Mon            
     * @param string $Day            
     * @return string
     */
    public static function toUpperDate($Year = null, $Mon = null, $Day = null)
    {
        if (empty($Year)) {
            $Year = date('Y');
            $Mon = date('m');
            $Day = date('d');
        }
        if (empty($Mon)) {
            $n = strtotime($Year);
            $Year = date('Y', $n);
            $Mon = date('m', $n);
            $Day = date('d', $n);
        }
        $n = strlen($Year);
        for ($m = 0; $m < $n; $m ++) {
            $jiaow = substr($Year, $m, 1);
            if ($jiaow == 1) {
                $Y .= '壹';
            }
            if ($jiaow == 2) {
                $Y .= '贰';
            }
            if ($jiaow == 3) {
                $Y .= '叁';
            }
            if ($jiaow == 4) {
                $Y .= '肆';
            }
            if ($jiaow == 5) {
                $Y .= '伍';
            }
            if ($jiaow == 6) {
                $Y .= '陆';
            }
            if ($jiaow == 7) {
                $Y .= '柒';
            }
            if ($jiaow == 8) {
                $Y .= '捌';
            }
            if ($jiaow == 9) {
                $Y .= '玖';
            }
            if ($jiaow == 0) {
                $Y .= '零';
            }
        }
        if ($Mon == 1) {
            $M = '零壹';
        }
        if ($Mon == 2) {
            $M = '零贰';
        }
        if ($Mon == 3) {
            $M = '零叁';
        }
        if ($Mon == 4) {
            $M = '零肆';
        }
        if ($Mon == 5) {
            $M = '零伍';
        }
        if ($Mon == 6) {
            $M = '零陆';
        }
        if ($Mon == 7) {
            $M = '零柒';
        }
        if ($Mon == 8) {
            $M = '零捌';
        }
        if ($Mon == 9) {
            $M = '零玖';
        }
        if ($Mon == 10) {
            $M = '零壹拾';
        }
        if ($Mon == 11) {
            $M = '壹拾壹';
        }
        if ($Mon == 12) {
            $M = '壹拾贰';
        }
        $r1 = substr($Day, 0, 1);
        $r2 = substr($Day, 1, 1);
        if ($r1 == 0) {
            if ($r2 == 1) {
                $D = '零壹';
            }
            if ($r2 == 2) {
                $D = '零贰';
            }
            if ($r2 == 3) {
                $D = '零叁';
            }
            if ($r2 == 4) {
                $D = '零肆';
            }
            if ($r2 == 5) {
                $D = '零伍';
            }
            if ($r2 == 6) {
                $D = '零陆';
            }
            if ($r2 == 7) {
                $D = '零柒';
            }
            if ($r2 == 8) {
                $D = '零捌';
            }
            if ($r2 == 9) {
                $D = '零玖';
            }
        } else {
            if ($r1 == 1) {
                if ($r2 == 1) {
                    $D = '壹拾壹';
                }
                if ($r2 == 2) {
                    $D = '壹拾贰';
                }
                if ($r2 == 3) {
                    $D = '壹拾叁';
                }
                if ($r2 == 4) {
                    $D = '壹拾肆';
                }
                if ($r2 == 5) {
                    $D = '壹拾伍';
                }
                if ($r2 == 6) {
                    $D = '壹拾陆';
                }
                if ($r2 == 7) {
                    $D = '壹拾柒';
                }
                if ($r2 == 8) {
                    $D = '壹拾捌';
                }
                if ($r2 == 9) {
                    $D = '壹拾玖';
                }
                if ($r2 == 0) {
                    $D = '零壹拾';
                }
            } else {
                if ($r1 == 2) {
                    if ($r2 == 1) {
                        $D = '贰拾壹';
                    }
                    if ($r2 == 2) {
                        $D = '贰拾贰';
                    }
                    if ($r2 == 3) {
                        $D = '贰拾叁';
                    }
                    if ($r2 == 4) {
                        $D = '贰拾肆';
                    }
                    if ($r2 == 5) {
                        $D = '贰拾伍';
                    }
                    if ($r2 == 6) {
                        $D = '贰拾陆';
                    }
                    if ($r2 == 7) {
                        $D = '贰拾柒';
                    }
                    if ($r2 == 8) {
                        $D = '贰拾捌';
                    }
                    if ($r2 == 9) {
                        $D = '贰拾玖';
                    }
                    if ($r2 == 0) {
                        $D = '零贰拾';
                    }
                } else {
                    if ($r2 == 1) {
                        $D = '叁拾壹';
                    }
                    if ($r2 == 0) {
                        $D = '零叁拾';
                    }
                }
            }
        }
        return $Y . '年' . $M . '月' . $D . '日';
    }

    /**
     * 检查数组中是否存在某个值
     *
     * @param string $elem            
     * @param array $array
     *            支持多维数组
     * @return boolean
     */
    public static function inArray($elem, $array)
    {
        foreach ($array as $key => $value) {
            if ($value == $elem) {
                return true;
            } elseif (is_array($value)) {
                if (self::inArray($elem, $value))
                    return true;
            }
        }
        
        return false;
    }

    /**
     * 检查数组中是否存在某些值
     *
     * @param array $needles            
     * @param array $haystack
     *            支持多维数组
     * @return boolean
     */
    public static function arrayInArray($needles, $haystack)
    {
        foreach ($needles as $needle) {
            
            if (Base::inArray($needle, $haystack)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * 获取请求的url地址
     *
     * @return string
     */
    public static function getRequestUrl()
    {
        $url = 'http://';
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $url = 'https://';
        }
        if ($_SERVER['SERVER_PORT'] != '80') {
            $url .= $_SERVER['HTTP_HOST'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
        } else {
            $url .= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        }
        return $url;
    }

    /**
     * 是否经过urlencode处理
     *
     * @param string $text            
     * @return boolean
     */
    public static function isUrlencode($text)
    {
        if (urldecode($text) != $text) {
            return true;
        }
        
        return false;
    }

    /**
     * 字符串转字节数组
     *
     * @param string $string            
     * @return array
     */
    public static function stringToBytes($string)
    {
        $bytes = array();
        for ($i = 0, $n = strlen($string); $i < $n; $i ++) {
            $bytes[] = ord($string[$i]);
        }
        
        /*
         * // or for($i = 0, $n = strlen ( $string ); $i < $n; $i ++) { if (ord ( $string [$i] ) >= 128) { $byte = ord ( $string [$i] ) - 256; } else { $byte = ord ( $string [$i] ); } $bytes [] = $byte; }
         */
        
        return $bytes;
    }

    /**
     * 字节数组转字符串
     *
     * @param array $bytes            
     * @return string
     */
    public static function bytesToString($bytes)
    {
        $string = '';
        foreach ($bytes as $byte) {
            $string .= chr($byte);
        }
        return $string;
    }

    /**
     * 整型转字节数组
     *
     * @param int $val            
     * @return byte[]
     */
    public static function integerToBytes($val)
    {
        $bytes = array();
        $bytes[0] = ($val & 0xff);
        $bytes[1] = ($val >> 8 & 0xff);
        $bytes[2] = ($val >> 16 & 0xff);
        $bytes[3] = ($val >> 24 & 0xff);
        return $bytes;
    }

    /**
     * 字节数组指定的位置转整型
     *
     * @param array $bytes            
     * @param int $position            
     * @return int
     */
    public static function bytesToInteger($bytes, $position)
    {
        $val = 0;
        $val = $bytes[$position + 3] & 0xff;
        $val <<= 8;
        $val |= $bytes[$position + 2] & 0xff;
        $val <<= 8;
        $val |= $bytes[$position + 1] & 0xff;
        $val <<= 8;
        $val |= $bytes[$position] & 0xff;
        return $val;
    }

    /**
     * Short类型转字节数组
     *
     * @param short $val            
     * @return byte[]
     */
    public static function shortToBytes($val)
    {
        $bytes = array();
        $bytes[0] = ($val & 0xff);
        $bytes[1] = ($val >> 8 & 0xff);
        return $bytes;
    }

    /**
     * 字节数组指定的位置转Short类型
     *
     * @param array $bytes            
     * @param int $position            
     * @return short
     */
    public static function bytesToShort($bytes, $position)
    {
        $val = 0;
        $val = $bytes[$position + 1] & 0xFF;
        $val = $val << 8;
        $val |= $bytes[$position] & 0xFF;
        return $val;
    }

    /**
     * 数据加密
     *
     * @param array $bytes
     *            数据
     * @param array $gKey
     *            密钥
     * @return byte[]
     */
    public static function encrypt($bytes, $gKey)
    {
        $len = count($bytes);
        $newKey = array();
        for ($i = 0, $n = count($gKey); $i < $n; $i ++) {
            $newKey[$i] = $gKey[$i] ^ ($len % 256);
        }
        for ($i = 0; $i < $len; $i ++) {
            $bytes[$i] = 0xff ^ $bytes[$i];
            $bit = $newKey[$i % 64] % 8;
            $low = $bytes[$i] & ((1 << $bit) - 1); // 求出低位，用于拼接到高位
            $bytes[$i] = ($bytes[$i] >> $bit) | ($low << (8 - $bit)); // 先将高位位移到低位，再做拼接
        }
        
        return $bytes;
    }

    /**
     * 数据解密
     *
     * @param array $bytes
     *            数据
     * @param array $gKey
     *            密钥
     * @return byte[]
     */
    public static function decrypt($bytes, $gKey)
    {
        $len = count($bytes);
        $newKey = array();
        for ($i = 0, $n = count($gKey); $i < $n; $i ++) {
            $newKey[$i] = $gKey[$i] ^ ($len % 256);
        }
        for ($i = 0; $i < $len; $i ++) {
            $bit = (8 - $newKey[$i % 64] % 8); // 反向拼接
            $low = $bytes[$i] & ((1 << $bit) - 1); // 求出低位
            $bytes[$i] = ($bytes[$i] >> $bit) | ($low << (8 - $bit));
            $bytes[$i] = 0xff ^ $bytes[$i];
        }
        
        return $bytes;
    }

    /**
     * 转换自定义参数
     *
     * @param string $string            
     * @return array
     */
    public static function toParams($string, $separator = ':')
    {
        $params = array();
        if ($string) {
            foreach (explode("\r\n", $string) as $line) {
                $array = explode($separator, $line);
                $params[$array[0]] = $array[1];
            }
        }
        return $params;
    }

    /**
     * 标签格式校验
     *
     * @param mixed $var            
     * @param string $separator            
     * @return string
     */
    public static function tagFix($var, $separator = ',')
    {
        if (is_string($var)) {
            $var = explode($separator, $var);
        }
        
        if (is_array($var)) {
            $var = array_filter($var);
        }
        
        return $separator . implode($separator, $var) . $separator;
    }

    /**
     * 字符串转数组
     *
     * @param string $string            
     * @param array $separator            
     * @return array
     */
    public static function stringToArray($string, $separator = array(PHP_EOL, ';', '='))
    {
        if (! is_array($separator)) {
            $separator = (array) $separator;
        }
        if (count($separator) <= 0) {
            return $string;
        }
        $_separator = array_shift($separator);
        $array = array_values(array_filter(explode($_separator, $string)));
        if (count($separator) > 0) {
            for ($i = 0, $n = count($array); $i < $n; $i ++) {
                $array2 = self::stringToArray($array[$i], $separator);
                if (is_array($array2)) {
                    $array[$i] = $array2;
                }
            }
        }
        return $array;
    }

    /**
     * 最后一级数组转键值对
     *
     * @param array $array            
     * @return array
     */
    public static function arrayEndKeyvalue($array)
    {
        if (! is_array($array) || count($array) <= 0) {
            return $array;
        }
        for ($i = 0, $n = count($array); $i < $n; $i ++) {
            if (! is_array($array[$i][0]) && count($array[$i]) <= 2) {
                if (count($array[$i]) == 2) {
                    $array[$array[$i][0]] = $array[$i][1];
                } else {
                    $array[$array[$i][0]] = "";
                }
                unset($array[$i]);
            } else {
                $array[$i] = self::arrayEndKeyvalue($array[$i]);
            }
        }
        return $array;
    }

    /**
     * 检查参数是否为空
     *
     * @return boolean
     */
    public static function checkParams()
    {
        $count = func_num_args();
        $params = func_get_args();
        $realCount = count(array_filter($params));
        if ($count != $realCount) {
            return false;
        }
        return true;
    }

    /**
     * 防止脚本注入攻击
     *
     * @param string $param            
     * @return string
     */
    public static function safeParam($param)
    { // TODO
        if (! isset($param))
            return '';
        $param = urldecode($param);
        $param = str_ireplace('javascript', '', $param);
        $param = str_ireplace(';', '', $param);
        $param = str_ireplace('<', '', $param);
        $param = str_ireplace('>', '', $param);
        $param = str_ireplace('"', '', $param);
        return $param;
    }

    /**
     * 拼接参数
     *
     * @param array $params            
     * @param array $separator
     *            例：array('?', '&', '=')
     * @return string 例：?id=1&name=zhangsan
     */
    public static function unionParams($params, $separator = array('?', '&', '='))
    {
        if (empty($params) || ! is_array($params))
            return '';
        $unionParams = array();
        foreach ($params as $key => $value) {
            $unionParams[] = $key . $separator[2] . $value;
        }
        return $separator[0] . implode($separator[1], $unionParams);
    }

    /**
     * 移除两侧的空白字符或其他预定义字符（支持数组）
     *
     * @param mixed $var            
     * @return mixed
     */
    public static function trim($var)
    {
        if (! is_array($var))
            return trim($var);
        return array_map('self::trim', $var);
    }

    /**
     * 移除两侧的空白字符或其他预定义字符（支持数组）（旧版）
     *
     * @param mixed $var            
     * @return mixed
     */
    public static function trim2($var)
    {
        if (! is_array($var))
            return trim($var);
        while (list ($key, $value) = each($var)) {
            if (is_array($value)) {
                $var[$key] = self::trim2($value);
            } else {
                $var[$key] = trim($value);
            }
        }
        return $var;
    }

    /**
     * 设置php最长执行时间和内存限制
     *
     * @param number $max_execution_time            
     * @param number $memory_limit            
     */
    public static function setExectimeMemory($max_execution_time = 2400, $memory_limit = 1048576000)
    {
        ini_set("max_execution_time", $max_execution_time);
        ini_set("memory_limit", $memory_limit);
    }

    /**
     * 将下划线命名转换为驼峰式命名
     * 
     * @param string|[] $str
     * @param string $ucfirst 大驼峰AbcDef|小驼峰abcDef
     * @return string|[]
     */
    public static function asCamel($str, $ucfirst = false)
    {
        if (is_array($str)) {
            $arr = [];
            foreach ($str as $key => $value) {
                $arr[self::asCamel($key)] = $value;
            }
            return $arr;
        }
        $str = ucwords(str_replace('_', ' ', $str));
        $str = str_replace(' ', '', lcfirst($str));
        return $ucfirst ? ucfirst($str) : $str;
    }
}

