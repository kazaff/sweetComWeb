<?php
//检查用户是否登录
foreach (array('userid','username') as $index){
	if(!isset($_SESSION[$index])){
		header('Location: logout.php');
	}
}