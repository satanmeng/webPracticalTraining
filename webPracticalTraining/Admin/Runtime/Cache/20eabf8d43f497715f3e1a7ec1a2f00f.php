<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo (C("sitename")); ?></title>

<link href="__PUBLIC__/dwz/themes/default/style.css" rel="stylesheet" type="text/css" />
<link href="__PUBLIC__/dwz/themes/css/core.css" rel="stylesheet" type="text/css" />
<!--[if IE]>
<link href="__PUBLIC__/dwz/themes/css/ieHack.css" rel="stylesheet" type="text/css" />
<![endif]-->

<script src="__PUBLIC__/dwz/js/speedup.js" type="text/javascript"></script>
<script src="__PUBLIC__/dwz/js/jquery-1.7.2.min.js" type="text/javascript"></script>
<script src="__PUBLIC__/dwz/js/jquery.cookie.js" type="text/javascript"></script>
<script src="__PUBLIC__/dwz/js/jquery.validate.js" type="text/javascript"></script>
<script src="__PUBLIC__/dwz/js/jquery.bgiframe.js" type="text/javascript"></script>

<!-- umeditor1.2.3 编辑器 -->
<link href="__PUBLIC__/umeditor/themes/default/css/umeditor.min.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="__PUBLIC__/umeditor/third-party/template.min.js"></script>
<script src="__PUBLIC__/umeditor/umeditor.config.js" type="text/javascript"></script>
<script src="__PUBLIC__/umeditor/umeditor.min.js" type="text/javascript"></script>
<script src="__PUBLIC__/umeditor/lang/zh-cn/zh-cn.js" type="text/javascript"></script>

<script src="__PUBLIC__/dwz/js/dwz.min.js" type="text/javascript"></script>
<script src="__PUBLIC__/dwz/js/dwz.regional.zh.js" type="text/javascript"></script>

<script type="text/javascript">
function fleshVerify(){
	//重载验证码
	$('#verifyImg').attr("src", '__APP__/Public/verify/'+new Date().getTime());
}
function dialogAjaxMenu(json){
	dialogAjaxDone(json);
	if (json.statusCode == DWZ.statusCode.ok){
		$("#sidebar").loadUrl("__APP__/Public/menu");
	}
}
function navTabAjaxMenu(json){
	navTabAjaxDone(json);
	if (json.statusCode == DWZ.statusCode.ok){
		$("#sidebar").loadUrl("__APP__/Public/menu");
	}
}
$(function(){
	DWZ.init("__PUBLIC__/dwz/dwz.frag.xml", {
		loginUrl:"__APP__/Public/login_dialog", loginTitle:"登录",	// 弹出登录对话框
		statusCode:{ok:1,error:0},
		keys:{statusCode:"status", message:"info"},
		pageInfo:{pageNum:"pageNum", numPerPage:"numPerPage", orderField:"_order", orderDirection:"_sort"}, //【可选】
		debug:false,	// 调试模式 【true|false】
		callback:function(){
			initEnv();
			$("#themeList").theme({themeBase:"__PUBLIC__/dwz/themes"});
		}
	});
});
</script>
</head>

<body scroll="no">
	<div id="layout">
		<div id="header">
			<div class="headerNav">
				<a class="logo" href="__APP__">Logo</a>
				<ul class="nav">
					<li><a href="__APP__/Public/main" target="dialog" width="580" height="360" rel="sysInfo">系统消息</a></li>
					<li><a href="__APP__/Public/password/" target="dialog" mask="true">修改密码</a></li>
					<li><a href="__APP__/Public/profile/" target="dialog" mask="true">修改资料</a></li>
					<li><a href="__APP__/Public/logout/">退出</a></li>
				</ul>
				<ul class="themeList" id="themeList">
					<li theme="default"><div class="selected">蓝色</div></li>
				</ul>
			</div>
		</div>
		
		<div id="leftside">
			<div id="sidebar_s">
				<div class="collapse">
					<div class="toggleCollapse"><div></div></div>
				</div>
			</div>
			
			<div id="sidebar">
				<div class="toggleCollapse"><h2>主菜单</h2><div>收缩</div></div>
					
<div class="accordion" fillSpace="sideBar">
	<?php if(is_array($menus)): $i = 0; $__LIST__ = $menus;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="accordionHeader">
		<h2><span>Folder</span><?php echo ($vo['title']); ?></h2>
		</div>
		
		<div class="accordionContent">
			<ul class="tree treeFolder">
				<?php if(is_array($vo['item'])): $i = 0; $__LIST__ = $vo['item'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i; if((strtolower($item['name'])) != "public"): if((strtolower($item['name'])) != "index"): if(($item['access']) == "1"): ?><li><a href="__APP__/<?php echo ($item['name']); ?>/index/" target="navTab" rel="<?php echo ($item['name']); ?>"><?php echo ($item['title']); ?></a></li><?php endif; endif; endif; endforeach; endif; else: echo "" ;endif; ?>
			</ul>
		</div><?php endforeach; endif; else: echo "" ;endif; ?>
</div>

			</div>
		</div>

		<div id="container">
			<div id="navTab" class="tabsPage">
				<div class="tabsPageHeader">
					<div class="tabsPageHeaderContent"><!-- 显示左右控制时添加 class="tabsPageHeaderMargin" -->
						<ul class="navTab-tab">
							<li tabid="main" class="main"><a href="javascript:void(0)"><span><span class="home_icon">我的主页</span></span></a></li>
						</ul>
					</div>
					<div class="tabsLeft">left</div><!-- 禁用只需要添加一个样式 class="tabsLeft tabsLeftDisabled" -->
					<div class="tabsRight">right</div><!-- 禁用只需要添加一个样式 class="tabsRight tabsRightDisabled" -->
					<div class="tabsMore">more</div>
				</div>
				<ul class="tabsMoreList">
					<li><a href="javascript:void(0)">我的主页</a></li>
				</ul>
				<div class="navTab-panel tabsPageContent layoutBox">
					<div class="page unitBox">
						<div class="accountInfo">

							<div class="right">
								<p><?php echo (date('Y-m-d g:i a',time())); ?></p>
							</div>
							<p><span><?php echo (C("sitename")); ?></span></p>
							<p>Welcome, <?php echo (session('loginUserName')); ?></p>
						</div>

					</div>
				</div>
			</div>
		</div>

	</div>
	
	<div id="footer">Copyright &copy; 2017<a href="" target="_blank">火车订票系统</a></div>


</body>
</html>