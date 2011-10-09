<?php 
	defined('INFRAME')?'':header('Location: logout.php');
?>
<ul>
	<li><?=$_SESSION['username']?>，万万岁</li>
	<li><a href="comInfo.php" title="网站参数">网站参数</a></li>
	<li><a href="category_list.php" title="类别管理">类别管理</a></li>
	<li><a href="news_list.php" title="新闻管理">新闻管理</a></li>
	<li><a href="products_list.php" title="产品管理">产品管理</a></li>
	<li><a href="honors_list.php" title="荣誉管理">荣誉管理</a></li>
	<li><a href="hunters_list.php" title="招聘管理">招聘管理</a></li>
	<li><a href="messages_list.php" title="留言管理">留言管理</a></li>
	<li><a href="customs_list.php" title="自助管理">自助管理</a></li>
	<li><a href="logout.php" title="安全退出">下班回家</a></li>
</ul>