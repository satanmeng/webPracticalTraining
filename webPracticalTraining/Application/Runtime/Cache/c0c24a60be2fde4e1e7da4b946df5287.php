<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>火车订票管理系统</title>
    <link href="__PUBLIC__/Trains/css/bootstrap.css" rel="stylesheet" />
    <link href="__PUBLIC__/Trains/css/font-awesome.css" rel="stylesheet" />
    <link href="__PUBLIC__/Trains/css/style.css" rel="stylesheet" />

    <script type="text/javascript">
          function info(){
            $.post("__URL__/head",function(data){
                data = eval("(" + data + ")");
                if (data != null) {
                    $("#username").html(data['username']);
                    $("#detail").html(data['detail']);
                    $("#resume").html(data['resume']);
                } else {
                    $("#username").html("无");
                    $("#detail").html("无");
                    $("#resume").html("无");
                }
            });
          }
    </script>
</head>

<body>
    <header>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <strong>Email: </strong>info@yourdomain.com
                    &nbsp;&nbsp;
                    <strong>Support: </strong>12306
                </div>

            </div>
        </div>
    </header>
    <div class="navbar navbar-inverse set-radius-zero">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">

                    <img src="__PUBLIC__/Trains/img/logo.png" />
                </a>

            </div>

            <div class="left-div">
                <div class="user-settings-wrapper" >
                    <ul class="nav">

                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown"  aria-expanded="false">
                                <span class="glyphicon glyphicon-user" style="font-size: 25px;"></span>
                            </a>
                            <div class="dropdown-menu dropdown-settings">
                                <div class="media">
                                    <a class="media-left" href="#">
                                        <img src="<?php echo head(4) ?>" alt="" class="img-rounded" height="64px" width="64px"/>
                                    </a>
                                    <div class="media-body">
                                        <h4 class="media-heading" id="username"><?php echo head(1) ?></h4>
                                        <h5 id="detail"><?php echo head(2) ?></h5>

                                    </div>
                                </div>
                                <hr />
                                <h5><strong>个人简介 : </strong></h5>
                                <?php echo head(3) ?>
                                <hr />
                                <a href="__URL__/ui/" class="btn btn-info btn-sm">全部信息</a>&nbsp; <a href="__URL__/logout/" class="btn btn-danger btn-sm">退出</a>

                            </div>
                        </li>


                    </ul>
                </div>
            </div>
        </div>
    </div>
<script type="text/javascript">
          function back(nkey){
            $.post("__URL__/tuipiao",{nkey:nkey},function(data){
                    alert(data);
                    document.location.reload();
            });
          }

    </script>
    <section class="menu-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="navbar-collapse collapse ">
                        <ul id="menu-top" class="nav navbar-nav navbar-right">
                            <li><a href="index.html">首页</a></li>
                            <li><a href="ui.html">个人信息</a></li>
                            <li><a class="menu-top-active" href="table.html">个人订单</a></li>

                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-head-line">订单详情</h1>
                    </div>
                </div>
                <?php if(is_array($order)): $i = 0; $__LIST__ = $order;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="row">
            <div class="col-md-3">
                
            </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                        </div>
                        <div class="panel-body">
                            <center>
                                <div>
                                    <?php echo (getfieldbyid($vo['tid'],"trains")); ?>&nbsp;&nbsp;&nbsp;<?php echo (getfieldbyid($vo['tid'],"start")); ?> -------> <?php echo (getfieldbyid($vo['tid'],"end")); ?>
                                    <br>
                                    <?php echo (getfieldbyid($vo['tid'],"stime")); ?>开
                                    <br>
                                    持票人：<?php echo ($realname); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;总张数：1
                                    <br>
                                    <a onclick="back(<?php echo ($vo['nkey']); ?>)" class="btn btn-danger btn-sm">退票</a>
                                </div>
                            </center>
                        </div>
                    </div>
                </div>
            </div><?php endforeach; endif; else: echo "" ;endif; ?>

        </div>
    </div>
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    &copy; 2017 火车订票管理系统 | By : <a href="" target="_blank">lly</a>
                </div>

            </div>
        </div>
    </footer>
    <script src="__PUBLIC__/Trains/js/jquery-1.11.1.js"></script>
    <script src="__PUBLIC__/Trains/js/bootstrap.js"></script>
</body>
</html>