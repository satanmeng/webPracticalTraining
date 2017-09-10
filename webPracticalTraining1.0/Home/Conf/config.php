<?php
return array(
	'DB_TYPE' => 'oracle', // 数据库类型
	'DB_HOST' => 'localhost', // 服务器地址
	'DB_NAME' => 'STUDY', // 数据库名
	/*'DB_NAME' => '(DESCRIPTION =
	    (ADDRESS_LIST =
	      (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.191.1)(PORT = 1521))
	    )
	    (CONNECT_DATA =
	      (SERVICE_NAME = STUDY)
	    )
	  )',*/
	'DB_USER' => 'test', // 用户名
	'DB_PWD' => 'test', // 密码
	'DB_PORT' => '1521', // 端口
	'APP_DEBUG' => true,
	'SHOW_PAGE_TRACE' => true, // 显示页面Trace信息
	'persist' => true, //注意，这一个必须写
	'USER_AUTH_GATEWAY' => '/Public/login', // 默认认证网关
	'NOT_AUTH_MODULE' => 'Public', // 默认无需认证模块
);