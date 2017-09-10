<?php

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

class PublicAction extends Action {

    // 检查用户是否登录
    protected function checkUser() {
        if (!session('?' . C('USER_AUTH_KEY'))) {
            $this->assign('jumpUrl', 'Public/login');
            $this->error('没有登录');
        }
    }

    // 顶部页面
    public function top() {
        C('SHOW_RUN_TIME', false);   // 运行时间显示
        C('SHOW_PAGE_TRACE', false);
        $model = M("Group");
        $list = $model->where('status=1')->getField('id,title');
        $this->assign('nodeGroupList', $list);
        $this->display();
    }

    // 尾部页面
    public function footer() {
        C('SHOW_RUN_TIME', false);   // 运行时间显示
        C('SHOW_PAGE_TRACE', false);
        $this->display();
    }

    // 菜单页面
    public function menu() {
        $this->checkUser();
        if (session('?' . C('USER_AUTH_KEY'))) {
            //显示菜单项
            $menu = array();
            if (session('?menu' . session(C('USER_AUTH_KEY')))) {
                //如果已经缓存，直接读取缓存
                $menu = session('menu' . session(C('USER_AUTH_KEY')));
            } else {
                //读取数据库模块列表生成菜单项
                $node = M("Node");
                $id = $node->getField("id");
                $where['level'] = 2;
                $where['status'] = 1;
                $where['pid'] = $id;
                $list = $node->where($where)->field('id,name,group_id,title')->order('sort asc')->select();
                $accessList = session('_ACCESS_LIST');
                foreach ($list as $key => $module) {
                    if (isset($accessList[strtoupper(APP_NAME)][strtoupper($module['name'])]) || session('administrator')) {
                        //设置模块访问权限
                        $module['access'] = 1;
                        $menu[$key] = $module;
                    }
                }
                //缓存菜单访问
                session('menu' . session(C('USER_AUTH_KEY')), $menu);
            }
            if (!empty($_GET['tag'])) {
                $this->assign('menuTag', $_GET['tag']);
            }
            //dump($menu);
            $this->assign('menu', $menu);
        }
        C('SHOW_RUN_TIME', false);   // 运行时间显示
        C('SHOW_PAGE_TRACE', false);
        $this->display();
    }

    // 后台首页 查看系统信息
    public function main() {
        $info = array(
            '操作系统' => PHP_OS,
            '运行环境' => $_SERVER["SERVER_SOFTWARE"],
            'PHP运行方式' => php_sapi_name(),
            'ThinkPHP版本' => THINK_VERSION . ' [ <a href="http://thinkphp.cn" target="_blank">查看最新版本</a> ]',
            '上传附件限制' => ini_get('upload_max_filesize'),
            '执行时间限制' => ini_get('max_execution_time') . '秒',
            '服务器时间' => date("Y年n月j日 H:i:s"),
            '北京时间' => gmdate("Y年n月j日 H:i:s", time() + 8 * 3600),
            '服务器域名/IP' => $_SERVER['SERVER_NAME'] . ' [ ' . gethostbyname($_SERVER['SERVER_NAME']) . ' ]',
            '剩余空间' => round((@disk_free_space(".") / (1024 * 1024)), 2) . 'M',
            'register_globals' => get_cfg_var("register_globals") == "1" ? "ON" : "OFF",
            'magic_quotes_gpc' => (1 === get_magic_quotes_gpc()) ? 'YES' : 'NO',
            'magic_quotes_runtime' => (1 === get_magic_quotes_runtime()) ? 'YES' : 'NO',
        );
        $this->assign('info', $info);
        $this->display();
    }

    // 用户登录页面
    public function login() {
        if (!session('?' . C('USER_AUTH_KEY'))) {
            $this->display();
        } else {
            $this->redirect('Index/index');
        }
    }

    public function index() {
        //如果通过认证跳转到首页
        redirect(__APP__);
    }

    // 用户登出
    public function logout() {
        if (session('?' . C('USER_AUTH_KEY'))) {
//            session(NULL);
            session_destroy();
            $this->assign("jumpUrl", __URL__ . '/login/');
            $this->success('登出成功！');
        } else {
            $this->error('已经登出！');
        }
    }

    //登录检测
    public function checkLogin() {
        if (empty($_POST['account'])) {
            $this->error('帐号错误！');
        } elseif (empty($_POST['password'])) {
            $this->error('密码必须！');
        } elseif (empty($_POST['verify'])) {
            $this->error('验证码必须！');
        }
        //生成认证条件
        $map = array();
        $map['account'] = $_POST['account'];
        $map['shop_id'] = 0; //平台用户
        $map["status"] = array('gt', 0);
        if (session('verify') != md5($_POST['verify'])) {
            $this->error('验证码错误！');
        }
        import('@.ORG.Util.RBAC');
        $authInfo = RBAC::authenticate($map);
        //使用用户名、密码和状态的方式进行认证
        if (!$authInfo) {
            $this->error('帐号不存在或已禁用！');
        } else {
            if ($authInfo['password'] != md5($_POST['password'])) {
                $this->error('密码错误！');
            }
            session(C('USER_AUTH_KEY'), $authInfo['id']);
            session('email', $authInfo['email']);
            session('loginUserName', $authInfo['nickname']);
            session('lastLoginTime', $authInfo['last_login_time']);
            session('login_count', $authInfo['login_count']);
            if ($authInfo['account'] == 'admin') {
                session('administrator', true);
            }
            //保存登录信息
            $User = M('User');
            $ip = get_client_ip();
            $time = time();
            $data = array();
            $data['id'] = $authInfo['id'];
            $data['last_login_time'] = $time;
            $data['login_count'] = array('exp', 'login_count+1');
            $data['last_login_ip'] = $ip;
            $User->save($data);

            // 缓存访问权限
            RBAC::saveAccessList();
            $this->success('登录成功！');
        }
    }

    // 更换密码
    public function changePwd() {
        $this->checkUser();
        //对表单提交处理进行处理或者增加非表单数据
        if (md5($_POST['verify']) != session('verify')) {
            $this->error('验证码错误！');
        }
        $map = array();
        $map['password'] = pwdHash($_POST['oldpassword']);
        if (isset($_POST['account'])) {
            $map['account'] = $_POST['account'];
        } elseif (session('?' . C('USER_AUTH_KEY'))) {
            $map['id'] = session(C('USER_AUTH_KEY'));
        }
        //检查用户
        $User = M("User");
        if (!$User->where($map)->field('id')->find()) {
            $this->error('旧密码不符或者用户名错误！');
        } else {
            $User->password = pwdHash($_POST['password']);
            $User->save();
            $this->success('密码修改成功！');
        }
    }

    public function profile() {
        $this->checkUser();
        $User = M("User");
        $vo = $User->getById(session(C('USER_AUTH_KEY')));
        $this->assign('vo', $vo);
        $this->display();
    }

    public function verify() {
        $type = isset($_GET['type']) ? $_GET['type'] : 'gif';
        import("@.ORG.Util.Image");
        Image::buildImageVerify(4, 1, $type);
//        log::write(print_r($_SESSION, TRUE), LOG::INFO);
    }

    // 修改资料
    public function change() {
        $this->checkUser();
        $User = D("User");
        if (!$User->create()) {
            $this->error($User->getError());
        }
        $result = $User->save();
        if (false !== $result) {
            $this->success('资料修改成功！');
        } else {
            $this->error('资料修改失败!');
        }
    }

    //编辑器 图片上传  
    public function upload() {
//        log::write(print_r($_FILES, true), LOG::INFO);
        $this->checkUser();
        if (!empty($_FILES['upfile']['name'])) {
            import("@.ORG.Util.UploadFile");
            $upload = new UploadFile(); // 实例化上传类
            $upload->maxSize = 5 * 1024 * 1024; // 设置附件上传大小：5M
            $upload->saveRule = 'uniqid';
            $upload->allowExts = array('jpg', 'gif', 'png', 'jpeg'); // 设置附件上传类型
            $upload->autoSub = true;
            $upload->subType = 'date';
            $upload->dateFormat = 'Y/m/d';
            // 上传文件
            if (!$upload->upload()) {// 上传错误提示错误信息
                echo json_encode(array('state' => $upload->getErrorMsg()));
            } else {// 上传成功 获取上传文件信息
                $info = $upload->getUploadFileInfo();
//                log::write(print_r($info, true), LOG::INFO);
                foreach ($info as $_one) {
                    //返回json数据被百度Umeditor编辑器   
                    echo json_encode(array(
                        'originalName' => $_one['name'],
                        'name' => basename($_one['savename']),
                        'url' => $_one['savename'],
                        'size' => $_one['size'],
                        'type' => $_one['extension'],
                        'state' => 'SUCCESS'
                    ));
                }
            }
        }
    }

}

?>