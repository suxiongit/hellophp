<?php
namespace bear\helpers;

//定义目录分隔符
defined('DS') or define('DS', DIRECTORY_SEPARATOR);

/**
 * 日志工具类
 *
 * @author suxiongit
 *
 */
class Log {

	private static $_basepath = null;
	
	public static function setBasePath($basepath) {
		self::$_basepath = $basepath;
	}

	public static function getBasePath() {
		
		if (empty(self::$_basepath)) {
			self::$_basepath = dirname(__FILE__).DS.'logs';
		}
		
		return self::$_basepath;
	}

	public static function write($str, $type = 'log', $mode = 'a', $path = null) {

		if (empty($path)) {
			$path = self::getBasePath();
		}

		$file = $path.DS.$type.date('Ymd').'.log';
		
		if (!is_string($str)) {
			ob_start();
			print_r($str);
			$str = ob_get_contents();
			ob_end_clean();
		}

		$fp = @fopen($file, $mode);
		fwrite($fp, sprintf("[%s]\t%s\n", date('Y-m-d H:i:s'), $str));
		@fclose($fp);
	}

	public static function debug($str, $mode = 'a', $path = null) {
		self::write($str, 'debug', $mode, $path);
	}

	public static function info($str, $mode = 'a', $path = null) {
		self::write($str, 'info', $mode, $path);
	}

	public static function error($str, $mode = 'a', $path = null) {
		self::write($str, 'error', $mode, $path);
	}

	public static function warn($str, $mode = 'a', $path = null) {
		self::write($str, 'warn', $mode, $path);
	}
	
}