<?php
require_once 'Base.php';
require_once 'Runtime.php';

use bear\helpers\Base;
use bear\helpers\Runtime;

Base::charsetUTF8();

$runtime = new Runtime();

//开始计时
$runtime->start();

for ($i = 0; $i < 10; $i ++) {
	sleep(1);
}

//停止计时
$runtime->stop();

echo '执行时间: '.$runtime->spent().' 毫秒';