<?php
namespace bear\helpers;

/**
 * 计算代码的执行时间
 *
 * @author suxiongit
 *
 */
class Runtime
{
	var $startTime = 0;
	var $stopTime = 0;

	function get_microtime()
	{
		/* list($usec, $sec) = explode(' ', microtime());
		 return ((float)$usec + (float)$sec); */
		return microtime(true);//与上行注释代码等价，返回单位为秒（s）。
	}

	function start()
	{
		$this->startTime = $this->get_microtime();
	}

	function stop()
	{
		$this->stopTime = $this->get_microtime();
	}

	function spent()
	{
		return round(($this->stopTime - $this->startTime) * 1000, 1);//返回单位为毫秒（ms）
	}
}