<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html>
<head>
<title>页面提示</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<style type="text/css">
* { font-size:12px}
.message { width:600px; height:150px; top:50%; left:50%; margin-left:-300px; margin-top:-75px; position:absolute; border:#690 solid 1px; padding:5px}
.message .title{ background:#690; padding:5px; color:#fff; font-weight:bold}
.message .content { padding:5px}
.message .content .left { float:left}
.message .content .right { float:right; margin-right:50px}
.message .content .right .row { margin:10px 0px}
</style>
</head>
<body>
	<div class="message">
		<div class="title"><?php echo ($msgTitle); ?></div>
		<div class="content">
			<div class="left">
			<img src="__PUBLIC__/dwz/themes/default/images/message-error.jpg" />
			</div>
			<div class="right">
			<?php if(isset($message)): ?><div class="row"><?php echo ($message); ?></div><?php endif; ?>
			<?php if(isset($error)): ?><div class="row"><?php echo ($error); ?></div><?php endif; ?>
			<?php if(isset($closeWin)): ?><div class="row">系统将在 <span id="wait1" style="color:blue;font-weight:bold"><?php echo ($waitSecond); ?></span> 秒后自动关闭，如果不想等待,直接点击 <a href="<?php echo ($jumpUrl); ?>">这里</a> 关闭</div><?php endif; ?>
			<?php if(!isset($closeWin)): ?><div class="row">系统将在 <span id="wait2" style="color:blue;font-weight:bold"><?php echo ($waitSecond); ?></span> 秒后自动跳转,如果不想等待,直接点击 <a href="<?php echo ($jumpUrl); ?>">这里</a> 跳转</div><?php endif; ?>
			</div>
		</div>
	</div>
    <script type="text/javascript">
        var wait2 = document.getElementById("wait2");
        setInterval(function(){
              var time2 = --wait2.innerHTML;
              if(time2 <= 0) {
                      location.href = "<?php echo ($jumpUrl); ?>";
                      clearInterval(interval);
              };
        },1000);
</script>
</body>
</html>