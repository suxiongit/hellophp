<?php
namespace bear\helpers;

/**
 * 处理HTML的工具类
 * 
 * @author suxiongit
 *
 */
class Html {
	
	/**
	 * 去掉HTML代码标签的属性
	 * @param string $s 源代码
	 * @param array $allowedattr 被允许的属性
	 * @return string $s
	 */
	public static function stripAttribute($s, $allowedattr = array()) {
		if (preg_match_all("/<[^>]*\\s([^>]*)\\/*>/msiU", $s, $res, PREG_SET_ORDER)) {
			foreach ($res as $r) {
				$tag = $r[0];
				$attrs = array();
				preg_match_all("/\\s.*=(['\"]).*\\1/msiU", " " . $r[1], $split, PREG_SET_ORDER);
				foreach ($split as $spl) {
					$attrs[] = $spl[0];
				}
				$newattrs = array();
				foreach ($attrs as $a) {
					$tmp = explode("=", $a);
					if (trim($a) != "" && (!isset($tmp[1]) || (trim($tmp[0]) != "" && !in_array(strtolower(trim($tmp[0])), $allowedattr)))) {
	
					} else {
						$newattrs[] = $a;
					}
				}
				$attrs = implode(" ", $newattrs);
				$rpl = str_replace($r[1], $attrs, $tag);
				$s = str_replace($tag, $rpl, $s);
			}
		}
		return $s;
	}
	
	/**
	 * 提取IMG图片地址的SRC
	 * @param string $html
	 * @throws Exception
	 * @return array
	 */
	public static function getImage($html) {
		if (empty($html)) {
			throw new Exception('variable is null in '.__FILE__.(':').('Line').__LINE__);
		}
		$images = array();
		$pattern = '<[img|IMG].*?src=[\'|"](.*?(?:[.gif|.jpg]))[\'|"].*?[/]?>';
		preg_match_all($pattern, $html, $matches);
		for($i=0, $n=count($matches[1]); $i<$n; $i++) {
			$images[] = $matches[1][$i];
		}
		return $images;
	}
	
	/**
	 * 提取A链接地址的HREF
	 * @param string $html
	 * @throws Exception
	 * @return array
	 */
	public static function getLink($html) {
		if (empty($html)) {
			throw new Exception('variable is null in '.__FILE__.(':').('Line').__LINE__);
		}
		$links = array();
		$pattern = '/<a .*?href="(.*?)".*?>/is';
		preg_match_all($pattern, $html, $matches);//在$subject中搜索匹配所有符合$pattern加入$matches中
		for($i=0, $n=count($matches[1]); $i<$n; $i++) {
			$links[] = $matches[1][$i];
		}
		return $links;
	}
	
	/**
	 * 截取摘要
	 * @param string $str 文本内容
	 * @param int $length 截取字数
	 * @param string $tail 截取后后面还有内容时显示的文字
	 * @param string $charset 编码
	 * @return string 返回摘要
	 */
	public static function getSummary($str, $length, $tail = '...', $charset = 'utf-8') {
		$str = str_replace("\n", '', $str);
		$str = strip_tags($str);
		$str = html_entity_decode($str, ENT_QUOTES, $charset);
		$str = ltrim($str);
		if (mb_strlen($str, $charset) > $length) {
			return mb_substr($str, 0, $length, $charset).$tail;
		}
		return $str;
	}
	
	/**
	 * 文本里是否有图片
	 * @param string $html
	 * @throws Exception
	 * @return boolean
	 */
	public static function hasImage($html) {
		if (empty($html)) {
			throw new Exception('variable is null in '.__FILE__.(':').('Line').__LINE__);
		}
		
		if(preg_match("/<img.*>/", $html)) {
			//echo "这篇文章里有图片";
			return true;
		} else {
			//echo "这篇文章里没有图片";
			return false;
		}
	}
	
	/**
	 * 生成标签列表
	 * @param string[] $data
	 * @param string $className
	 * @param string $tagName
	 */
	public static function tags($data, $className = '', $tagName = 'span') {
	    $tags = '';
	    if (is_array($data)) {
	        foreach ($data as $value) {
	            $style = empty($className) ? '' : 'class="'.$className.'"';
	            $tags[] = "<$tagName $style>".$value."</$tagName>";
	        }
	    }
	    return implode('', $tags);
	}
}