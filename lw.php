<?php
	//文件：将提交的内容 写入数据库 公共使用需加防SQL注入语句
	//该文件使用GET/POST皆可 提交内容过多推荐使用POST
	$lock_file = 'lock.install';
	if(!file_exists($lock_file)){die(header("refresh:0;url=install.html"));}//没有锁定文件 判断为未安装 跳转安装
	if(!isset($_GET["submit"])){die(header("refresh:1;url=lw.html"));}//不是通过表单提交 跳转留言
	//没有输入内容时 默认留言内容
	$name = "#没有名字#";
	$text = "#这个人很懒什么都没写#";
	
	//Ip
	$ip = $_SERVER['REMOTE_ADDR'];
	//判断是否有提交数据 有则赋值
	if(isset($_GET["name"])&&$_GET["name"]!=''){$name = $_GET["name"];}
	if(isset($_GET["text"])&&$_GET["text"]!=''){$text = $_GET["text"];}
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////防注入
	//并不能完全阻止
	//trim();函数可去除空格 制表 换行 但留言可能用到
	//stripslashes();函数可删除\反斜杠
	$name = addslashes($name);//将‘ “ \ NULL前面加上\使之变成普通字符
	$text = addslashes($text);//将‘ “ \ NULL前面加上\使之变成普通字符
	$name = htmlspecialchars($name); //函数把预定义的字符转换为 HTML 实体。
	$text = htmlspecialchars($text); //函数把预定义的字符转换为 HTML 实体。
	//可以屏蔽掉注入时 所使用语句的特殊符号 将特殊符号转换成类似：&copy；（版权符号）
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////防注入
	//连接数据库 并将$con指向数据库
	@require('db_config.php');
	$con = @mysql_connect($mysql_server_name, $mysql_username, $mysql_password);
	if(!$con){die("连接数据库出错");}
	
	//打开$mysql_database库
	$db_selected = mysql_select_db($mysql_database, $con);
	//设置数据库编码类型为UTF8 同网页类型
	mysql_query("SET NAMES UTF8");
	//生成datetime数据 例：2016-11-30 19:05:00
	$date = date('Y-m-d'); 
	$time = date('G:i:s');
	$datetime = "$date,$time";
	//将'$datetime','$name','$text'写入jjlyb表中的`Time`, `Name`, `Text`
	$sql = "INSERT INTO `jjlyb`(`Ip`, `Time`, `Name`, `Text`) VALUES ('$ip','$datetime','$name','$text')";
	//执行上述语句 可写作mysql_query($sql);
	mysql_query($sql, $con);
	//关闭数据库
	mysql_close($con);
	//显示写入的日期和时间
	echo $datetime;
	//显示写入成功信息，并跳转到主页
	echo "写入成功，1秒后自动跳转主页";
	header("refresh:1;url=index.php");
?>