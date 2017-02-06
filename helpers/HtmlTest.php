<?php
require_once 'Base.php';
require_once 'Html.php';

use bear\helpers\Base;
use bear\helpers\Html;

Base::charsetUTF8();
/*
 * 定义需要测试的方法
 */

function testStripAttribute() {
	$html = <<<EOF
<ul class="bottomnav">
<li> <a href="http://www.romzj.com/posts/" target="_blank" title="安卓Android手机最新ROM资讯">热门资讯</a> <span class="divider">|</span> </li>
<li> <a href="/about-us/join-us.html"> <span>加入我们</span> </a> <span class="divider">|</span> </li>
<li> <a href="/about-us/contact-us.html"> <span>联系我们</span> </a> <span class="divider">|</span> </li>
<li> <a href="/features/changelog.html"> <span>更新日志</span> </a> <span class="divider">|</span> </li>
<li> <a href="/sitemap.html?sitemap=1"> <span>网站地图</span> </a> <span class="divider">|</span> </li>
<li> <a href="http://support.shuame.com/discuss.html" target="_blank" rel="nofollow"> <span>产品反馈</span> </a> </li>
</ul>
EOF;
	echo $html;
	echo '<br/>之后：<br/>';
	echo Html::stripAttribute($html, array('href'));
}

function testGetImage() {
	$html = '<p style="padding: 0px; margin-top: 0px; margin-bottom: 0px; line-height: 200%;"><img border="0" src="upfiles/2009/07/1246430143_4.jpg" alt=""/></p><p style="padding: 0px; margin-top: 0px; margin-bottom: 0px; line-height: 200%;"><img border="0" src="upfiles/2009/07/1246430143_3.jpg" alt=""/></p><p style="padding: 0px; margin-top: 0px; margin-bottom: 0px; line-height: 200%;"><img border="0" src="upfiles/2009/07/1246430143_1.jpg" alt=""/></p>';
	echo $html;
	echo '<br/>之后：<br/>';
	var_dump(Html::getImage($html));
}

function testGetLink() {
	$html = '<a href="链接1">文本1</a><a href="链接2">文本2</a><a href="链接3">文本3</a>';
	echo $html;
	echo '<br/>之后：<br/>';
	var_dump(Html::getLink($html));
}

function testGetSummary() {
	$html = <<<EOF
<p>&nbsp;</p>
<p style="padding-left: 30px;"><span style="color: #5a5a5a; font-family: 'Microsoft Yahei', Arial, Helvetica, sans-serif; line-height: 30px;">• </span>刷机精灵是由深圳 <span style="color: #4e9300;"><strong>瓶子科技 ours团队</strong></span> 推出的一款运行于PC端的Android手机一键刷机软件，Ours秉承 “<span style="color: #4e9300;"><strong>懂快乐·爱分享</strong></span>” 的理念，目标是让人人都懂得刷机装系统；真正实现一键式无忧安装，帮助用户在简短的流程内快速完成刷机升级，它能够帮您&nbsp;<span style="color: #4e9300;"><strong>自动安装设备驱动、自动获取ROOT权限、自动刷入Clockworkmod Recovery </strong></span>以完成您的Android设备系统升级以及刷入第三方系统。您还可以通过刷机精灵旗下的全球首个ROM市场“<span style="color: #4e9300;"><strong>ROM之家</strong></span>”找到适合于您设备的第三方系统。</p>
<p style="padding-left: 30px;">&nbsp;</p>
<p style="padding-left: 30px;"><a href="index.php?option=com_content&amp;view=article&amp;id=12:2012-02-01-09-21-04&amp;catid=1:beginner&amp;Itemid=7">刷机精灵能做什么？</a></p>
EOF;
	echo $html;
	echo '<br/>之后：<br/>';
	echo Html::getSummary($html, 300);
}

function testHasImage() {
	$content='<p><img src="http://www.caizhichao.cn/caizhichao/images/wx/wx_aiguozu1314.jpg" /><br />扫二维码 添加爱国足de博客微信<p>'; //文章内容
	if(Html::hasImage($content)) {
		echo "这篇文章里有图片";
	} else {
		echo "这篇文章里没有图片";
	}
}

function testTags() {
    $data = [
        '动作类',
        '冒险类',
    ];
    echo Html::tags($data, 'tag');
}


/*
 * 测试入口
 */

if (!defined('INDEX_TEST')) {
	if (isset($_REQUEST['method'])) {
		$method = 'test'.ucfirst($_REQUEST['method']);
		echo '<strong>'.$method.':</strong>';
		echo '<br/>----------------------------------------------<br/>';
		$method();
		echo '<br/><br/><br/><br/>';
	} else {
		echo '请传参数method，测试您所需的方法！';
	}
}


