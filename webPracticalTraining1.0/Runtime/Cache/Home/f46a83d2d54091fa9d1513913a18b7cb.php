<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><%$Think.config.sitename%></title>
		<link href="/test/Public/dwz/themes/css/login.css" rel="stylesheet" type="text/css" />
		<script src="/test/Public/dwz/js/jquery-1.7.1.min.js" type="text/javascript"></script>
		<script language="JavaScript" type="text/javascript">
			function fleshVerify(type){
				//重载验证码
				var timenow = new Date().getTime();
				if (type){
					$('#verifyImg').attr("src", '/test/index.php/Home/Public/verify/adv/1/'+timenow);
				}else{
					$('#verifyImg').attr("src", '/test/index.php/Home/Public/verify/'+timenow);
				}
			}
			</script>
		<script language="javascript" for="palm_login" event="feature_got(strFeature, strMd5)" type="text/javascript">
			featureMatch(strFeature, strMd5);
		</script>
	</head>
	<body>
		<OBJECT id="palm_login"
				style="width:0px; height:0px;"
				classid="CLSID:A03B497B-4889-4BFD-808E-0B076BB06ED5">
		</OBJECT>
		<div id="login">
			<div id="login_header">
				<h1 class="login_logo">
					<a href="#"><img src="/test/Public/dwz/themes/default/images/login_logo.gif" /></a>
				</h1>
				<div class="login_headerContent">
					<div class="navList">
						<ul>
							<li><a href="#">设为首页</a></li>
							<li><a href="#">升级说明</a></li>
							<li><a href="#">反馈</a></li>
							<li><a href="#">帮助</a></li>
						</ul>
					</div>
					<h2 class="login_title"><img src="/test/Public/dwz/themes/default/images/login_title.png" /></h2>
				</div>
			</div>
			<div id="login_content">
				<div class="loginForm">
					<form method="post" action="/test/index.php/Home/Public/checkLogin/">
						<p>
							<label>帐号：</label>
							<input type="text" name="account" size="20" class="login_input" />
						</p>
						<p>
							<label>密码：</label>
							<input type="password" name="password" size="20" class="login_input" />
						</p>
						<p>
							<label>验证码：</label>
							<input class="code" name="verify" type="text" size="5" />
							<span><img id="verifyImg" SRC="/test/index.php/Home/Public/verify/" onClick="fleshVerify()" border="0" alt="点击刷新验证码" style="cursor:pointer" align="absmiddle"></span>
						</p>
						<div class="login_bar">
							<input class="sub" type="submit" value=" " /><br>
							<a href="<?php echo U('Public/register');?>">注册</a>
						</div>
					</form>
				</div>
				<div class="login_banner"><img src="/test/Public/dwz/themes/default/images/login_banner.jpg" /></div>

			</div>
			<div id="login_footer">
				Copyright &copy; 2017 长春工程学院软件1541. All Rights Reserved.
			</div>
		</div>
	</body>
</html>