<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Cache-Control" content="no-transform" />
	<meta http-equiv="Cache-Control" content="no-siteapp" />
	<meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no,minimal-ui">
	<meta name="author" content="Ayon Lee"/>
	<meta name="generator" content="ModPHP"/>
	<?php
	$host = 'http://modphp.hyurl.com/';
	$update = url() == site_url('install.php?update');
	$uninstall = url() == site_url('install.php?uninstall');
	$title = $update ? '更新' : ($uninstall ? '卸载' : '安装');
	echo "<title>{$title} - ModPHP</title>";
	?>
	<script>
	function $(id){
		return document.getElementById(id);
	}
	function go_home(){
		document.location.href = document.location.href.split('install.php')[0];
	}
	function send_ajax(act, data, btn){
		var xhr = new XMLHttpRequest(),
			str = '',
			texts = {install: '安装', update: '更新', uninstall: '卸载'},
			text = texts[act];
		btn = $(btn);
		btn.innerText = text+'中...';
		btn.setAttribute('disabled', 'disabled');
		if(typeof data == 'object'){
			for(var i in data){
				str += '&'+encodeURIComponent(i)+'='+encodeURIComponent(data[i]);
			}
			str = str.substring(1);
		}else{
			str = data;
		}
		xhr.open('POST', 'mod.php?mod::'+act, true);
		xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
		xhr.onreadystatechange = function(){
			var result = xhr.responseText;
			if(xhr.readyState == 4){
				if(xhr.status == 200){
					result = JSON.parse(result);
					alert(result.data);
					if(result.success){
						btn.innerText = text+'成功！';
						go_home();
					}else{
						btn.innerText = text+'失败！';
					}
				}else{
					alert(result);
					btn.innerText = text+'失败！';
				}
				setTimeout(function(){
					btn.innerText = text;
					btn.removeAttribute('disabled');
				}, 2000);
			}
		};
		xhr.send(str);
	}
	function install(){
		if(!confirm('即将安装 ModPHP，确定？')) return false;
		send_ajax('install', {
			'mod.database.host': $('db-host').value,
			'mod.database.name': $('db-name').value,
			'mod.database.port': $('db-port').value,
			'mod.database.username': $('db-user').value,
			'mod.database.password': $('db-pass').value,
			'mod.database.prefix': $('db-prefix').value,
			'site.name': $('site-name').value,
			'user_name': $('user-name').value,
			'user_password': $('user-password').value,
		}, 'install-button');
	}
	function update(arg){
		arg = arg || [];
		var note = arg['upgrade'] ? 'ModPHP 核心' : '数据库结构';
		if(!confirm('即将更新'+note+'，确定？')) return false;
		send_ajax('update', arg, arg['upgrade'] ? 'upgrade-button' : 'update-button');
	}
	function uninstall(){
		if(!confirm('即将卸载 ModPHP，确定？')) return false;
		send_ajax('uninstall', {
			'user_password': $('user-password').value,
			'drop_database': $('drop-database').checked
		}, 'uninstall-button');
	}
	</script>
	<style>
	body{font-family: 'arial';background: #eee;margin: 0;padding: 10px;}
	h2{text-align: center;margin: -5px -10px 0;padding: 15px 0;background: #7dc8da;}
	h3{border-bottom: solid 1px #ccc;margin: 10px 0 5px;}
	label{font-weight: bold;width: 70px;display: inline-block;}
	input{margin: 5px 0;width: 190px;padding: 2px 5px;}
	button{padding: 2px 10px;margin: 15px 0;}
	a{text-decoration: none;}
	a:hover{text-decoration: underline;}
	footer{font-size: 14px;color: #666;margin: 0 -10px -25px;padding: 10px;text-align: center;background: #ccc;}
	.container{max-width: 320px;margin: 0 auto;background: #fff;padding: 5px 10px 25px;border-radius: 10px;overflow: hidden;}
	.checkbox-label{font-weight: normal;width: auto;display: inline-block;margin-left: 20px;}
	input[type=checkbox]{width: auto;}
	header ul{margin: -5px -10px;padding: 10px 10px 5px;background-color: #2894ff;}
	header li{display: inline-block;width: 40px;overflow: hidden;white-space: nowrap;position: relative;margin-right: 20px;font-size: 20px;}
	header li.active{width: 150px;font-size:24px;color: white;border-bottom: 2px solid #fff;margin-bottom: -2px;}
	header a{color: inherit;}
	header a:hover{text-decoration: none;color: #fff;}
	header li:hover{border-bottom: 2px solid #fff;margin-bottom: -2px;}
	</style>
</head>
<body>
	<div class="container">
		<header>
			<ul>
				<li<?php echo !$update && !$uninstall ? ' class="active"' : '' ?>><a href="install.php">安装 ModPHP</a></li>
				<li<?php echo $update ? ' class="active"' : '' ?>><a href="install.php?update">更新 ModPHP</a></li>
				<li<?php echo $uninstall ? ' class="active"' : '' ?>><a href="install.php?uninstall">卸载 ModPHP</a></li>
			</ul>
		</header>
	<?php
	$go_home = '<a href="'.site_url().'">点击此处返回首页</a>。';
	if($update || $uninstall){
		if(config('mod.installed')){
			if(!is_logined()){
				echo "<p>用户未登录，无法进行操作，{$go_home}</p>";
			}elseif(!is_admin()){
				echo "<p>当前用户不是管理员，无法进行操作，{$go_home}</p>";
			}else{
				if($update){ ?>
					<div class="options">
						<h3>更新数据库结构</h3>
						<button onclick="update()" id="update-button">更新</button>
					</div>
					<div class="options">
						<h3>更新内核版本</h3>
						<?php
						if(function_exists('curl_version')){
							$ver = @json_decode(curl($host.'version'), true);
							if($ver){
								$gt = version_compare($ver['version'], MOD_VERSION);
								if($gt > 0){
									echo '<p style="margin-bottom: 0">有新版本可用：<code>'.$ver['version'].'</code>'.(!empty($ver['url']) ? '，<a href="'.$ver['url'].'" target="_blank">新版说明</a>' : '').'</p>';
								}else{
									echo '<p>暂无可用更新。</p>';
								}
								if($gt >= 0){
									echo '<button onclick="update({upgrade: true, src: \''.$ver['src'].'\', md5: \''.$ver['md5'].'\'})" id="upgrade-button">'.($gt ? '更新' : '重新安装当前版本').'</button>';
								}
							}
						}
						?>
					</div>
					<p><small style="color: gray">注意：更新过程可能会导致网站暂时不可访问。</small></p>
					<p>放弃更新并<?php echo $go_home ?></p>
			<?php }else{ 
					if(me_id() != 1){
						echo "<p>当前用户不是超级管理员，无法进行操作，{$go_home}</p>";
					}else{
			?>
					<form onsubmit="uninstall(); return false;">
						<div class="options">
							<h3>验证管理员身份</h3>
							<div>
								<label for="user-name">当前用户</label>
								<div style="display: inline-block"><?php echo me_name() ?></div>
							</div>
							<div>
								<label for="user-password">密码</label>
								<input type="password" id="user-password" placeholder="管理员密码" required />
							</div>
							<button type="submit" id="uninstall-button">卸载</button>
							<label class="checkbox-label">
								<input type="checkbox" id="drop-database"> 清除数据库记录
							</label>
							<p>放弃卸载并<?php echo $go_home ?></p>
						</div>
					</form>
			<?php }
				}
			}
		}else{
			echo '<p>系统未安装，<a href="'.site_url('install.php').'">点击此处进行安装</a>。</p>';
		}
	}else{
		if(config('mod.installed')){
			echo "<p>系统已安装，{$go_home}</p>";
		}else{ ?>
			<form onsubmit="install(); return false;">
				<div class="options">
					<h3>数据库设置</h3>
					<?php
					$conf = array(
						'db-host' => array('主机', '数据库主机地址，默认 localhost'),
						'db-name' => array('数据库名', '数据库名称，默认 modphp'),
						'db-port' => array('端口', '数据库连接端口，默认 3306'),
						'db-user' => array('用户名', '数据库登录用户名称，默认 root'),
						'db-pass' => array('密码', '数据库登录密码，默认为空'),
						'db-prefix' => array('表前缀', '数据表前缀，默认 mod_')
						);
					foreach($conf as $k => $v){
						echo '<div>
								<label for="'.$k.'">'.$v[0].'</label>
								<input type="'.($k == 'db-pass' ? 'password' : 'text').'" id="'.$k.'" placeholder="'.$v[1].'" />
							  </div>';
					}
					?>
				</div>
				<div class="options">
					<h3>站点设置</h3>
					<div>
						<label for="site-name">网站名称</label>
						<input type="text" id="site-name" placeholder="网站名称，默认 ModPHP" />
					</div>
				</div>
				<div class="options">
					<h3>管理员设置</h3>
					<div>
						<label for="user-name">用户名</label>
						<input type="text" id="user-name" placeholder="网站管理员名称，必填" required />
					</div>
					<div>
						<label for="user-password">密码</label>
						<input type="password" id="user-password" placeholder="网站管理员密码，必填" required />
					</div>
				</div>
				<button type="submit" id="install-button">安装</button>
			</form>
<?php 
		}
	} 
	echo "<footer>&copy;".date('Y').' <a href="'.$host.'" target="_blank">ModPHP</a> '.MOD_VERSION.'. <a href="http://www.apache.org/licenses/LICENSE-2.0" target="_blank">Apache License 2.0</a></footer>';
?>
	<div>
</body>
</html>