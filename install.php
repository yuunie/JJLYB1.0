<!DOCTYPE html>
<html>
	<head>
		<title>JJ留言板_安装向导</title>
		<meta charset="utf-8">
		<style type="text/css">
		</style>
	</head>
	<body>
		<div id="wrap">
			<?php
				//文件：安装文件 可不加防sql注入
				//该文件最好使用POST皆可 因为提交的内容可能包含密码等敏感信息 不应出现在链接中
				$lock_file = 'lock.install';
				if(file_exists($lock_file)){die("检测到JJ留言板已经安装，如需重新安装，删除lock.install文件即可。如数据库更新请在db_config.php文件中更新数据。");}
				
				//无lock.install文件_未安装 start
				if(!isset($_POST["submit"])){die(header('refresh:0;url=install.html'));}//直接访问 跳转安装页面
				
				$jjylb_name = $mysql_sn = $mysql_un = $mysql_pw = $mysql_db = NULL;
				if(isset($_POST["title"])){$jjlyb_name = $_POST["title"];}//留言板名
				if(isset($_POST["sn"])){$mysql_sn = $_POST["sn"];}//服务器名
				if(isset($_POST["un"])){$mysql_un = $_POST["un"];}//用户名
				if(isset($_POST["pw"])){$mysql_pw = $_POST["pw"];}//用户密码
				if(isset($_POST["db"])){$mysql_db = $_POST["db"];}//数据库名

				if(!$db_f = fopen("db_config.php", "w")){die("无法新建db_config.php文件，可能没有权限。");}//新建db_config.php文件判断是否新建成功
				
				//将POST到的信息写db_config.php文件 start
				//需要写入的字符串
				$text_php_start = "<?php\n\n";
				$text_php_end = "\n\n>";
				$sn_text = "\$mysql_server_name = '$mysql_sn';	//数据库地址(默认为localhost)\n";
				$un_text = "\$mysql_username = '$mysql_un';			//数据库登录用户名\n";
				$pw_text = "\$mysql_password = '$mysql_pw';			//数据库登录密码\n";
				$db_text = "\$mysql_database = '$mysql_db';	//数据库名";
				$text_copy = "\n///////////////////////////////////////\n//JJ记事本_QQ1591216902_第一个php文件//\n///////////////////////////////////////\n\n";
				
				//将字符串写入文件db_config.php通过文件指针$dbf
				fwrite($db_f, $text_php_start);
				fwrite($db_f, $text_copy);
				fwrite($db_f, $sn_text);
				fwrite($db_f, $un_text);
				fwrite($db_f, $pw_text);
				fwrite($db_f, $db_text);
				fwrite($db_f, $text_php_start);
			
				//连接数据库并新建数据库
				@require('db_config.php');//引入登录数据库的信息
				$con = @mysql_connect($mysql_server_name, $mysql_username, $mysql_password);//访问数据库并将%con指向数据库
				
				mysql_query("CREATE DATABASE $mysql_database",$con);//在$con所指向的数据库中创建库$mysql_database

				mysql_select_db($mysql_database, $con);//进入到$mysql_database库
				mysql_query("SET NAMES UTF8");//设置数据库编码格式为UTF8 同网页格式
				//$sql为数据库语句
				//创建表JJLYB，在表中新建datetime类型的Time等等，UNSIGNED AUTO_INCREMENT PRIMARY KEY为设置为无符号自增主键
				$sql = "CREATE TABLE JJLYB
						(
						ID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
						Ip varchar(16),
						Time datetime,
						Name tinytext,
						Text text
						)";
				//标题表TITLE
				$sql_title = "CREATE TABLE TITLE(Title varchar(255))";
				//通过mysql_query()函数在$con中执行指定的语句
				mysql_query($sql, $con);
				mysql_query($sql_title, $con);

				//在title表中的Title行插入$jjlyb_name数据
				$sql = "INSERT INTO `title`(`Title`) VALUES ('$jjlyb_name')";
				//执行sql语句
				mysql_query($sql,$con);
				
				//关闭数据库
				mysql_close($con);
				
				//新建锁定安装文件
				if(!$f = fopen($lock_file, "w")){
					die("安装可能已经完成，但锁定安装文件没能新建成功，请在网站与此文件相同目录新建lock.install文件或删除本文件。");
					//关闭文件
					fclose($f);
				}
				echo "成功安装，1秒后自动跳转主页";
				header("refresh:1;url=index.php");	


			?>
			</div>
	</body>
</html>