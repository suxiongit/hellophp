<?php
require_once 'Base.php';
require_once 'Log.php';

use bear\helpers\Base;
use bear\helpers\Log;

Base::charsetUTF8();

/* //设置日志目录
Log::setBasePath(dirname(__FILE__).DS.'logs');

//获取日志存储的目录
echo Log::getBasePath();

//调试，例：debug20140507.log
Log::debug('debug test');

//错误，例：error20140507.log
Log::error('error test');

//信息，例：info20140507.log
Log::info('info test');

//警告，例：warn20140507.log
Log::warn('warn test');

//默认，例：log20140507.log
Log::write('write test'); */

//测试输出变量
$person = array(
	'name' => 'Bill Gates',
	'gender' => 'male',
	'age' => '58',
);

Log::write($person);