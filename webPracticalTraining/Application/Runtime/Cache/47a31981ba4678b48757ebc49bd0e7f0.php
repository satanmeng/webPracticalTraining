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
                <a class="navbar-brand" href="index.html">
                    <img src="__PUBLIC__/Trains/img/logo.png" />
                </a>

            </div>

            <div class="left-div">
                <i class="fa fa-user-plus login-icon" ></i>
        </div>
            </div>
        </div>
    <!-- LOGO HEADER END-->
   
    <!-- MENU SECTION END-->
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line">请登录</h4>

                </div>

            </div>
            <div class="row">
                <div class="col-md-3">
                </div>
                <div class="col-md-6">
                   <h4> 登 录 用 微 信<strong> / </strong>Q Q :</h4>
                    <br />
                    <a href="index.html" class="btn btn-social btn-facebook">
                            <i class="fa fa-wechat"></i>&nbsp; 微信 登录</a>
                    &nbsp;OR&nbsp;
                    <a href="index.html" class="btn btn-social btn-google">
                            <i class="fa fa-qq"></i>&nbsp; QQ 登录</a>
                    <hr />
                     <h4> 或者用 <strong>火车订票账号登录  :</strong></h4>
                    <br />
                    <form method="post" action="__URL__/checklogin/">
                     <label>输入账号 : </label>
                        <input type="text" class="form-control" name="username"/>
                        <label>输入密码 :  </label>
                        <input type="password" class="form-control" name="password"/>
                        <hr />
                        <input type="submit" name="" class="btn btn-info" value="登 录">
                        &nbsp;&nbsp;&nbsp;<a href="__URL__/register/" class="btn btn-info"><span class="glyphicon glyphicon-user"></span> &nbsp;注册 </a>&nbsp;
                    </form>
                </div>
                <div class="col-md-3">
                </div>

            </div>
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