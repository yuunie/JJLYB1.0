<!DOCTYPE html>
<html>
	<head>
		<title>
			<?php 
			//获取网页标题
			//bug 连接数据库出现问题 只会在title中显示 页面空白
				$lock_file = "lock.install";
				if(file_exists($lock_file)){
					//连接数据库
					@require('db_config.php');
					$con = @mysql_connect($mysql_server_name, $mysql_username, $mysql_password);
					if(!$con){
						//在主函数中显示错误
						die("连接数据库出错");
					}
					//进入$mysql_database数据库
					mysql_select_db($mysql_database, $con);
					//设置数据库编码格式为UTF8 同网页
					mysql_query("SET NAMES UTF8");
					//搜索title在所有表中
					$sql_title = "SELECT * FROM `title`";
					//执行上述数据库语句 并将返回的资源信息保存到$result
					$result = mysql_query($sql_title,$con);
					if(!$result ){
						//在主函数中显示错误
						die( '执行SQL语句失败' );
					}
					//获得$result中一行的数据
					$title = mysql_fetch_assoc($result);//mysql_fetch_assoc() 函数从结果集中取得一行作为关联数组。返回根据从结果集取得的行生成的关联数组，如果没有更多行，则返回 false。
				}
				//如果成功获得数据则输出数据或者输出JJ留言板-QQ1591216902
				if(isset($title)){
					echo "$title[Title]";
				}else{
					echo "JJ留言板-QQ1591216902";
				} 
				//关闭数据库
				//@mysql_close($con);<body>中还会用到 暂不关闭

			?>
		</title>
		<meta charset="utf-8">
	</head>
	<body>
		<?php
		//文件：主页 主要用来展示留言的内容 和各种链接
			//检测是否安装
			$counter_file = "lock.install";
			if(!file_exists($counter_file)){
				die("可能没有安装，<a href='install.html'>去安装</a>");
			}
			//<title>中已经打开数据库不重复打开
			///读取留言
			//连接数据库
			@require('db_config.php');
			$con = @mysql_connect($mysql_server_name, $mysql_username, $mysql_password);
			if(!$con){die("连接数据库出错");}
			//进入$mysql_database库
			mysql_select_db($mysql_database, $con);
			//设置数据库编码为UTF8 同网页
			mysql_query("SET NAMES UTF8");
			
			//找到jjlyb在所有表中并通过id逆序排序
			$sql = "SELECT * FROM `jjlyb` order by id desc";
			$rsa = mysql_query($sql,$con);
			if(!$rsa) die( '执行SQL语句失败' ); 
			
			echo "$title[Title]";
			echo "<a href='lw.html'>留言</a>";
			//while循环输出所有 数据库留言
			//mysql_fetch_array() 函数从结果集中取得一行作为关联数组，或数字数组，或二者兼有返回根据从结果集取得的行生成的数组，如果没有更多行则返回 false。
			//也可使用mysql_fetch_object() 函数从结果集（记录集）中取得一行作为对象。若成功的话，本函数从 mysql_query() 获得一行，并返回一个对象。如果失败或没有更多的行，则返回 false。
			//mysql_fetch_array() 函数 使用时：变量[下标]
			//mysql_fetch_object() 函数 使用时：变量->名称
			while($rs = mysql_fetch_array($rsa)){
				echo "<p>";
				echo "<ul>";
				echo "<li>ID:$rs[0]</li>";
				echo "<li>IP:$rs[1]</li>";
				echo "<li>时间：$rs[2]</li>";
				echo "<li>名字：$rs[3]</li>";
				echo "<li>内容：$rs[4]</li>";
				echo "</ul>";
				echo "</p>";
			}; 


			//关闭数据库
			mysql_close($con);

		?>
	</body>
</html>