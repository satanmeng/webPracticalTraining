<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <meta http-equiv="refresh" content="2;url=__URL__/login">  

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
                    <h4 class="page-head-line">提示消息</h4>

                </div>

            </div>
                <center><h2 style="color: #F0677C;"><?php echo ($weizi); ?></h2>
                </center>
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