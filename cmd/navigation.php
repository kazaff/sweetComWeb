<?php 
	defined('INFRAME')?'':header('Location: logout.php');
?>
<ul>
	<li><?=$_SESSION['username']?>，万万岁</li>
	<li><a href="category_list.php" title="类别管理">类别管理</a></li>
	<li><a href="news_list.php" title="新闻管理">新闻管理</a></li>
	<li><a href="logout.php" title="安全退出">下班回家</a></li>
</ul>