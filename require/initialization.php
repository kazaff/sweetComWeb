<?php
/**
 * 初始化系统需要的参数
 */
header("Content-type: text/html; charset=utf-8");
session_start();

define('Root_Path', dirname(dirname(__FILE__)));	//项目根目录

//安全处理POST,GET数组
require_once Root_Path.'/require/function/dataSafe.php';
if(!empty($_POST)){
	foreach ($_POST as $val){
		if(inject_check($val)){
			die('抱歉，您输入的数据太劲爆了，我们的服务器貌似暂时顶不住，请您暂且把数据提交给<a href="http://www.baidu.com/s?wd='.$val.'+%D7%A2%C8%EB">百度</a>先～');
		}
	}
	$_POST = sql_injection($_POST);
}
$_GET = sql_injection($_GET);