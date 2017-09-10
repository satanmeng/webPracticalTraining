<?php

// 后台用户模块
class UserAction extends CommonAction {

    function _filter(&$map) {
        $map['id'] = array('egt', 2);
        $map['account'] = array('like', "%" . $_POST['account'] . "%");
    }

    public function _before_add() {
        $roles = M('role')->field('id,name')->order('id asc')->select();
        $shops = M('shop')->field('id,name')->select();
        $this->assign('shops', $shops);
        $this->assign('roles', $roles);
    }

    public function _before_edit() {
        $roles = M('role')->field('id,name')->order('id asc')->select();
        $shops = M('shop')->field('id,name')->select();
        $where['user_id'] = $_REQUEST['id'];
        $role_id = M('role_user')->where($where)->getField('role_id');
        $this->assign('role_id', $role_id);
        $this->assign('shops', $shops);
        $this->assign('roles', $roles);
    }

    // 检查帐号
    public function checkAccount() {
        if (!preg_match('/^[a-z]\w{4,}$/i', $_POST['account'])) {
            $this->error('用户名必须是字母，且5位以上！');
        }
        // 检测用户名是否冲突
        $name = $_REQUEST['account'];
        $result1 = M("User")->getByAccount($name);
        $result2 = M("Users")->getByAccount($name);
        if ($result1 || $result2) {
            $this->error('该用户名已经存在！');
        } else {
            $this->success('该用户名可以使用！');
        }
    }

    //插入数据
    public function insert() {
        $User = D("User");
        if (!$User->create()) {
            $this->error($User->getError());
        } else {
            // 写入帐号数据
            if ($result = $User->add()) {
                $this->addRole($result);
                $this->success('用户添加成功！');
            } else {
                $this->error('用户添加失败！');
            }
        }
    }

    protected function addRole($userId) {
        //新增用户自动加入相应权限组
        $RoleUser = M("RoleUser");
        $RoleUser->user_id = $userId;
        // 默认加入平台管理员组
        if (!empty($_POST['role'])) {
            $RoleUser->role_id = $_POST['role'];
        } else {
            $RoleUser->role_id = 1;
        }
        $RoleUser->add();
    }

    //重置密码
    public function resetPwd() {
        $id = $_POST['id'];
        $password = $_POST['password'];
        if ('' == trim($password)) {
            $this->error('密码不能为空！');
        }
        if (session('verify') != md5($_POST['verify'])) {
            $this->error('验证码错误！');
        }
        $User = M('User');
        $User->password = md5($password);
        $User->id = $id;
        $result = $User->save();
        if (false !== $result) {
            $this->success("密码修改为$password");
        } else {
            $this->error('重置密码失败！');
        }
    }

    function foreverdelete() {
        $name = $this->getActionName();
        $model = D($name);
        if (!empty($model)) {
            $pk = $model->getPk();
            $id = $_REQUEST [$pk];
            if (isset($id)) {
                $map = array('user_id' => array('in', explode(',', $id)));
                if (false === M('role_user')->where($map)->delete()) {
                    $this->error('删除用户关联角色失败！');
                }
                $condition = array($pk => array('in', explode(',', $id)));
                if (false !== $model->where($condition)->delete()) {
                    //echo $model->getlastsql();
                    $this->success('删除成功！');
                } else {
                    $this->error('删除失败！');
                }
            } else {
                $this->error('非法操作');
            }
        }
        $this->forward();
    }

}

?>