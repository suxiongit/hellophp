<?php
require_once 'Base.php';

use bear\helpers\Base;

Base::charsetUTF8();
Base::openError();

/*
 * 定义需要测试的方法
 */

function testOpenError()
{
    // 见testRedirect301(),testRedirect302()
}

function testPrintr()
{
    $person = new stdClass();
    $person->name = 'Bii Gates';
    $person->gender = 'male';
    $person->age = 59;
    
    Base::printr($person);
}

function testGetPrintd()
{
    $person = new stdClass();
    $person->name = 'Bii Gates';
    $person->gender = 'male';
    $person->age = 59;
    
    echo Base::getPrintd($person);
}

function testImport()
{
    // TODO
    Base::import($folder);
}

function testSubstr()
{
    // TODO
}

function testArrayToString()
{
    $name = array(
        "self" => "wangzhengyi",
        "student" => array(
            "chenshan",
            "xiaolingang"
        ),
        "unkmow" => "chaikun",
        "teacher" => array(
            "huangwei",
            "fanwenqing"
        )
    );
    Base::printr($name);
    
    echo Base::arrayToString($name);
    echo '<br />';
    echo Base::arrayToString($name, '|');
}

function testXmlToArray()
{
    $xmlPath = 'http://www.romzj.com/output/devices.xml';
    $array = Base::xmlToArray(simplexml_load_file($xmlPath));
    print_r($array);
}

function testXmlToArray2()
{
    $s = <<<EOS
<root>
<Formula>
<formulaname>Basic</formulaname>
<movespeed>1</movespeed>
<box>4</box>
<chicken>3</chicken>
<ducks>1</ducks>
<cereal>2</cereal>
</Formula>
</root>
EOS;
    $array = Base::xmlToArray2($s);
    print_r($array);
}

function testArrayToXml()
{
    $array = array(
        'formula' => array(
            'formulaname' => 'Basic',
            'movespeed' => 1,
            'box' => 4,
            'chicken' => 3,
            'ducks' => 1,
            'cereal' => 2
        )
    );
    echo Base::arrayToXml($array);
}

function testObjectToArray()
{
    $object = new stdClass();
    $object->foo = "Test data";
    $object->bar = new stdClass();
    $object->bar->baaz = "Testing";
    $object->bar->fooz = new stdClass();
    $object->bar->fooz->baz = "Testing again";
    $object->foox = "Just test";
    Base::printr($object);
    echo 'Covert object to array';
    $array = Base::objectToArray($object);
    Base::printr($array);
}

function testArrayToObject()
{
    $array = array();
    $array['foo'] = "Test data";
    $array['bar'] = array(
        'baaz' => "Testing",
        'fooz' => array(
            'baz' => 'Testing again'
        )
    );
    $array['foox'] = 'Just test';
    Base::printr($array);
    echo 'Covert array to object';
    $object = Base::arrayToObject($array);
    Base::printr($object);
}

function testArraySort()
{
    /*
     * $array = array( '0'=>array('id'=>1, 'name'=>'Cherry', 'age'=>22), '1'=>array('id'=>2, 'name'=>'Alma', 'age'=>23), '2'=>array('id'=>3, 'name'=>'Peter', 'age'=>25), '3'=>array('id'=>4, 'name'=>'John', 'age'=>20), );
     */
    $array = array(
        'a' => array(
            'id' => 1,
            'name' => 'Cherry',
            'age' => 22
        ),
        'b' => array(
            'id' => 2,
            'name' => 'Alma',
            'age' => 23
        ),
        'c' => array(
            'id' => 3,
            'name' => 'Peter',
            'age' => 25
        ),
        'd' => array(
            'id' => 4,
            'name' => 'John',
            'age' => 20
        )
    );
    /*
     * $array = array( '100'=>array('id'=>1, 'name'=>'Cherry', 'age'=>22), '101'=>array('id'=>2, 'name'=>'Alma', 'age'=>23), '102'=>array('id'=>3, 'name'=>'Peter', 'age'=>25), '103'=>array('id'=>4, 'name'=>'John', 'age'=>20), );
     */
    echo '<pre>';
    print_r($array);
    // echo '<br/>按name排序：<br/>';
    // $array2 = Base::arraySort($array, 'name');
    // print_r($array2);
    echo '<br/>按age排序：<br/>';
    $array3 = Base::arraySort($array, 'age', SORT_DESC);
    print_r($array3);
    echo '</pre>';
}

function testArraySort2()
{
    $array = array(
        '100' => array(
            'id' => 1,
            'name' => 'Cherry',
            'age' => 22
        ),
        '101' => array(
            'id' => 2,
            'name' => 'Alma',
            'age' => 23
        ),
        '102' => array(
            'id' => 3,
            'name' => 'Peter',
            'age' => 25
        ),
        '103' => array(
            'id' => 4,
            'name' => 'John',
            'age' => 20
        )
    );
    echo '<pre>';
    print_r($array);
    echo '<br/>按name排序：<br/>';
    $array2 = Base::arraySort2($array, 'name'/* , SORT_ASC, true */);
    print_r($array2);
    echo '</pre>';
}

function testArrayObjectSort()
{
    // 测试二维数组排序
    /*
     * $array = array( array('name'=>'手机', 'brand'=>'诺基亚', 'price'=>1050), array('name'=>'笔记本电脑', 'brand'=>'lenovo', 'price'=>4300), array('name'=>'剃须刀', 'brand'=>'飞利浦', 'price'=>3100), array('name'=>'跑步机', 'brand'=>'三和松石', 'price'=>4900), array('name'=>'手表', 'brand'=>'卡西欧', 'price'=>960), array('name'=>'液晶电视', 'brand'=>'索尼', 'price'=>6299), array('name'=>'激光打印机', 'brand'=>'惠普', 'price'=>1200), ); echo '<pre>'; print_r($array); echo '<br/>按price排序：<br/>'; $array2 = Base::arrayObjectSort($array, 'price'); print_r($array2); echo '</pre>';
     */
    
    // 测试二维数组对象排序
    $array = array();
    
    $person = new stdClass();
    $person->id = 1;
    $person->name = 'Cherry';
    $person->age = 22;
    $array[] = $person;
    
    $person = new stdClass();
    $person->id = 2;
    $person->name = 'Alma';
    $person->age = 23;
    $array[] = $person;
    
    $person = new stdClass();
    $person->id = 3;
    $person->name = 'Peter';
    $person->age = 25;
    $array[] = $person;
    
    $person = new stdClass();
    $person->id = 4;
    $person->name = 'John';
    $person->age = 20;
    $array[] = $person;
    
    Base::printr($array);
    
    $array = Base::arrayObjectSort($array, 'age');
    
    Base::printr($array);
    
    /*
     * $item = new stdClass(); $item->update = '2014年8月27日'; $array[] = $item; $item = new stdClass(); $item->update = '2014年7月09日';//2014年7月9日 有误！ $array[] = $item; $item = new stdClass(); $item->update = '2014年7月24日'; $array[] = $item; Base::printr($array); $array = Base::arrayObjectSort($array, 'update'); Base::printr($array);
     */
}

function testArrayVersionSort()
{
    
    // $array = array('5.3.0', '5.2.10', '5.2.9', '5.2.17', '5.3.10', '5.2.0', '5.2.8');
    // $array = array('5.22.3', '5.3.0');
    $array = array(
        '2.9.2-O-20141017.0206',
        '2.9.2-O-20141017.0207',
        '2.9.2-O-20141018.0206',
        '2.9.3-O-20141017.0206'
    );
    echo '排序前：';
    Base::printr($array);
    $array = Base::arrayVersionSort($array, SORT_DESC);
    echo '排序后：';
    Base::printr($array);
}

function testMaxVersion()
{
    $array = array(
        '5.3.0',
        '5.2.10',
        '5.2.9',
        '5.2.17',
        '5.3.10',
        '5.2.0',
        '5.2.8'
    );
    Base::printr($array);
    echo '最大版本：' . Base::maxVersion($array);
    echo '<br />';
    $array = array(
        '2.9.2-O-20141017.0206',
        '2.9.2-O-20141017.0207',
        '2.9.2-O-20141018.0206',
        '2.9.3-O-20141017.0206'
    );
    Base::printr($array);
    echo '最大版本：' . Base::maxVersion($array);
}

function testMinVersion()
{
    $array = array(
        '5.3.0',
        '5.2.10',
        '5.2.9',
        '5.2.17',
        '5.3.10',
        '5.2.0',
        '5.2.8'
    );
    Base::printr($array);
    echo '最小版本：' . Base::minVersion($array);
}

function testGetMillisecond()
{
    echo '当前时间的毫秒数：' . Base::getMillisecond();
}

function testGetClientIp()
{
    echo Base::getClientIp();
}

function testConvertIp()
{
    $ip = '14.17.11.162';
    echo $ip . ' ' . Base::convertIp($ip);
}

function testIsMobile()
{
    if (Base::isMobile()) {
        echo '您当前使用的是移动设备访问……';
    } else {
        echo '您当前使用的不是移动设备访问……';
    }
}

function testRequest()
{
    $url = 'http://api1.shuame.org/v2/root/support_device_list';
    $json = Base::request($url, null, 'GET', null, $httpCode, $error);
    echo 'HTTP ' . $httpCode . ':';
    echo $error;
    echo ($json);
}

function testPostRequest()
{
    $url = '';
    $json = Base::postRequest($url);
    var_dump($json);
}

function testRedirect301()
{
    Base::openError();
    $url = 'http://www.shuame.com/';
    Base::redirect301($url);
}

function testRedirect302()
{
    Base::openError();
    $url = 'http://www.shuame.com/';
    Base::redirect302($url);
}

function testValidUrl()
{
    $url = 'http://www.shuame.com';
    $valid = Base::validUrl($url);
    
    echo $url;
    if ($valid) {
        echo ' 有效';
    } else {
        echo ' 无效';
    }
}

function testValidUrl2()
{
    $url = 'http://www.shuame.com';
    $valid = Base::validUrl2($url);
    
    echo $url;
    if ($valid) {
        echo ' 有效';
    } else {
        echo ' 无效';
    }
}

function testValidImage()
{
    $url = 'http://static1.romzj.com/images/stories/mobiles/samsung-i9300.png';
    $valid = Base::validImage($url);
    
    echo $url;
    if ($valid) {
        echo ' 有效';
    } else {
        echo ' 无效';
    }
}

function testValidImage2()
{
    $url = 'http://static1.romzj.com/images/stories/mobiles/0samsung-i9300.png';
    $valid = Base::validImage2($url);
    
    echo $url;
    if ($valid) {
        echo ' 有效';
    } else {
        echo ' 无效';
    }
}

function testThumbImage()
{
    // TODO
}

function testAddMark()
{
    // TODO
}

function testFormatTime()
{
    echo Base::formatTime('2014-05-09 15:22:49');
}

function testFormatNumber()
{
    echo Base::formatNumber(123456);
}

function testFormatFileSize()
{
    echo Base::formatFileSize(123456);
}

function testGetFileSize()
{
    $uri = 'http://localhost/api.joomla.zip';
    echo $uri . ' 文件大小：' . Base::getFileSize($uri);
}

function testGetFileName()
{
    $uri = 'http://localhost/api.joomla.zip';
    echo $uri . ' 文件名：' . Base::getFileName($uri);
}

function testGetFileExtension()
{
    $uri = 'http://localhost/api.joomla.zip';
    echo $uri . ' 文件扩展名：' . Base::getFileExtension($uri);
}

function testStartWith()
{
    $uri = 'http://localhost/api.joomla.zip';
    echo $uri . ' ';
    if (Base::startWith($uri, 'http://')) {
        echo '是一个以http://开始的有效网址。';
    } else {
        echo '不是一个以http://开始的有效网址。';
    }
}

function testStartWith2()
{
    $uri = 'http://localhost/api.joomla.zip';
    echo $uri . ' ';
    if (Base::startWith2($uri, 'http://')) {
        echo '是一个以http://开始的有效网址。';
    } else {
        echo '不是一个以http://开始的有效网址。';
    }
}

function testEndWith()
{
    $uri = 'http://localhost/api.joomla.zip';
    echo $uri . ' ';
    if (Base::endWith($uri, '.zip')) {
        echo '是一个.zip文件包。';
    } else {
        echo '不是一个.zip文件包。';
    }
}

function testEndWith2()
{
    $uri = 'http://localhost/api.joomla.zip';
    echo $uri . ' ';
    if (Base::endWith2($uri, '.zip')) {
        echo '是一个.zip文件包。';
    } else {
        echo '不是一个.zip文件包。';
    }
}

function testStartWithAndEndWith()
{
    $str = '/* 这是一行注释 */';
    echo $str . ' ';
    if (Base::startWith($str, '/*') && Base::endWith($str, '*/')) {
        echo 'YES';
    } else {
        echo 'NO';
    }
}

function testStartWith2AndEndWith2()
{
    $str = '/* 这是一行注释 */';
    echo $str . ' ';
    if (Base::startWith2($str, '/*') && Base::endWith2($str, '*/')) {
        echo 'YES';
    } else {
        echo 'NO';
    }
}

function testRandomStr()
{
    echo '生成12位字符串：' . Base::randomStr(12);
}

function testRandomNum()
{
    echo '生成8位数字：' . Base::randomNum(8);
}

function testMakeUuid()
{
//     echo Base::makeUuid() . '<br/>';
//     echo Base::makeUuid(true);
    Base::setExectimeMemory();
    $units = array();
    for($i=0;$i<1000000;$i++){
        $units[]=Base::makeUuid();
    }
    $values  = array_count_values($units);
    $duplicates = [];
    foreach($values as $k=>$v){
        if($v>1){
            $duplicates[$k]=$v;
        }
    }
    echo '<pre>';
    print_r($duplicates);
    echo '</pre>';
    echo '<pre>';
    print_r($units);
    echo '</pre>';
}

function testGetErrLoc()
{
    echo Base::getErrLoc();
}

function testUrlSegments()
{
    $urlSegments = array(
        'name=billgates',
        'age=58',
        'gender=male'
    );
    echo 'query.php' . Base::urlSegments($urlSegments) . '<br/>';
    unset($urlSegments[0]);
    echo 'query.php?name=billgates' . Base::urlSegments($urlSegments, false);
}

function testGetToken()
{
    $skey = ')Sr*VY(SIp*?53HV<sJ/0CWyMUG(G?=E@?xIgGTapcf&rVFik{Q:z%E!etPrAC/D26F1!321K1y#th,^!-E$L)FS';//$skey = 'secret_key';
    echo Base::getToken($skey, true);
}

function testValidToken()
{
    $token = $_GET['token'];
    $skey = 'secret_key';
    echo date('Y-m-d H:i:s', substr($token, 0, 10)) . ' 生成token：' . $token;
    if (Base::validToken($token, $skey)) {
        echo ' 有效';
    } else {
        echo ' 无效';
    }
}

function testEncode()
{
    $text = 'abcdefg';
    echo $text . ' 加密为 ';
    echo Base::encode($text);
}

function testDecode()
{
    $text = '1MfG1sraxg%3D%3D';
    echo $text . ' 解密为 ';
    echo Base::decode($text);
}

function testGetBaseDomain()
{
    $server = 'rom.shuame.com';
    echo $server . ' 顶级域名：' . Base::getBaseDomain($server);
}

function testGetEmailDomain()
{
    $email = 'suxiong@shuame.com';
    echo $email . ' 域名：' . Base::getEmailDomain($email);
}

function testGetParam()
{
    // echo Base::getParam('param');
    // echo Base::getParam('param', 'default');
    echo Base::getParam(array(
        'param',
        'param2'
    ));
}

function testGetBirthExt()
{
    // 时间戳
    $birth = 603648000;
    $array = Base::getBirthExt($birth);
    Base::printr($array);
    
    // 其它时间写法
    $birth = '1989-02-17';
    $array = Base::getBirthExt($birth);
    Base::printr($array);
    
    $birth = '19890217';
    $array = Base::getBirthExt($birth);
    Base::printr($array);
}

function testToUpperDate()
{
    echo Base::toUpperDate(2015, 01, 26);
}

function testInArray()
{
    if (Base::inArray('e', array(
        'a',
        'b',
        'c',
        'd',
        array(
            'e'
        )
    ))) {
        echo '有';
    } else {
        echo '没有';
    }
}

function testArrayInArray()
{
    // TODO
}

function testGetRequestUrl()
{
    echo Base::getRequestUrl();
}

function testEncrypt()
{
    $str = $_REQUEST['str'];
    if (empty($str)) {
        exit('请求参数str不能为空！');
    }
    $gKey = array(
        0xd6,
        0xbe,
        0x9f,
        0x41,
        0x55,
        0xaa,
        0x79,
        0xd7,
        0x31,
        0x3f,
        0xe5,
        0xb3,
        0xe1,
        0xe9,
        0xfd,
        0x16,
        0xa2,
        0x1d,
        0xd3,
        0xd5,
        0xf0,
        0x61,
        0x6f,
        0xcf,
        0xef,
        0x63,
        0x40,
        0x0,
        0xe8,
        0xf4,
        0xc3,
        0x40,
        0x10,
        0xe8,
        0x89,
        0x83,
        0x85,
        0x50,
        0xf5,
        0x97,
        0xb1,
        0x2c,
        0x86,
        0xec,
        0x6d,
        0x9d,
        0xb7,
        0xf,
        0xe4,
        0x6f,
        0x78,
        0x98,
        0xa1,
        0x5d,
        0x56,
        0x39,
        0x2e,
        0x58,
        0xad,
        0x57,
        0xe,
        0x94,
        0x56,
        0x3e
    );
    echo $str . '<br />encrypt:';
    $encrypt = Base::encrypt(Base::stringToBytes($str), $gKey);
    print_r($encrypt);
    echo '<br />decrypt to string:';
    $decrypt = Base::decrypt($encrypt, $gKey);
    print_r(Base::bytesToString($decrypt));
}

function testToParams()
{
    $string = 'a:1' . PHP_EOL . 'b:2';
    print_r(Base::ToParams($string));
}

function testTagFix()
{
    $var = 'a,b,c,d';
    echo $var;
    echo ' 正确的格式为： ' . Base::tagFix($var);
    echo '<br />';
    $var = array(
        'a',
        'b',
        'c',
        'd'
    );
    print_r($var);
    echo ' 正确的格式为： ' . Base::tagFix($var);
}

function testStringToArrayAndArrayEndKeyvalue()
{
    $string = <<<STR
source=mssp;close_btn=true;refresh=30;
source=guangdiantong;close_btn=true;refresh=30;
STR;
    Base::printr($string);
    $array = Base::stringToArray($string, array(
        PHP_EOL,
        ';',
        '='
    ));
    Base::printr($array);
    $array = Base::arrayEndKeyvalue($array);
    Base::printr($array);
    $string = <<<STR
on_blank=1
on_failed_timeout=5
on_no_click_timeout=5400
STR;
    Base::printr($string);
    $array = Base::stringToArray($string, array(
        PHP_EOL,
        '='
    ));
    Base::printr($array);
    $array = Base::arrayEndKeyvalue($array);
    Base::printr($array);
}

function testCheckParams() {
    $a = @$_GET['a'];
    $b = @$_GET['b'];
    echo '参数1='.$a.' 参数2='.$b.' 结果为'.(Base::checkParams($a, $b) ? 'true': 'false');
}

function testSafeParam() {
    echo Base::safeParam($_REQUEST['var']);
}

function testUnionParams() {
    $params = array(
        'id'=>1,
        'name'=>'zhangsan'
    );
    var_export($params);
    echo '拼接参数：'.Base::unionParams($params);
}

function testTrim() {
    $DirtyArray = array(
        'Key1' => ' Value 1 ',
        'Key2' => '      Value 2      ',
        'Key3' => array(
            '   Child Array Item 1 ',
            '   Child Array Item 2'
        )
    );
    $CleanArray = Base::trim($DirtyArray);
    var_export($CleanArray);
}

/*
 * 测试入口
 */

if (! defined('INDEX_TEST')) {
    if ($method = $_REQUEST['method']) {
        if ('arrayToXml' == $method) {
            // XML
            Base::charsetUTF8('text/xml');
            $method = 'test' . ucfirst($method);
            $method();
        } else {
            // HTML
            Base::charsetUTF8();
            $method = 'test' . ucfirst($method);
            echo '<strong>' . $method . ':</strong>';
            echo '<br/>----------------------------------------------<br/>';
            $method();
            echo '<br/><br/><br/><br/>';
        }
    } else {
        echo '请传参数method，测试您所需的方法！';
    }
}



