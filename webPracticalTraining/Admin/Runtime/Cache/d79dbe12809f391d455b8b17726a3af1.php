<?php if (!defined('THINK_PATH')) exit();?><form id="pagerForm" action="__URL__" method="post">
    <input type="hidden" name="pageNum" value="1"/>
    <input type="hidden" name="_order" value="<?php echo ($_REQUEST["_order"]); ?>"/>
    <input type="hidden" name="_sort" value="<?php echo ($_REQUEST["_sort"]); ?>"/>
    <input type="hidden" name="title" value="<?php echo ($_REQUEST["username"]); ?>"/>
</form>
<div class="pageHeader">
    <form onsubmit="return navTabSearch(this);" action="__URL__" method="post">
        <div class="searchBar">
            <ul class="searchContent">
                <li>
                    <label>用户名：</label>
                    <input type="text" name="username" class="medium" value="<?php echo ($_REQUEST["username"]); ?>"/>
                </li>
            </ul>
            <div class="subBar">
                <ul>
                    <li><div class="buttonActive"><div class="buttonContent"><button type="submit">查询</button></div></div></li>
                </ul>
            </div>
        </div>
    </form>
</div>

<div class="pageContent">
    <div class="panelBar">
        <ul class="toolBar">
            <li><a class="delete" href="__URL__/foreverdelete/nkey/{sid_class}/navTabId/__MODULE__" target="ajaxTodo" calback="navTabAjaxDone" title="你确定要删除吗？" warn="请选择节点"><span>删除</span></a></li>
        </ul>
    </div>
    <table class="table" width="100%" layoutH="138">
        <thead>
        <tr>
        <th width="5%">编号</th>
        <th width="5%">用户名</th>
        <th width="10%">密码</th>
        <th width="5%">个签</th>
        <th width="20%">简介</th>
        <th width="5%">真实姓名</th>
        <th width="5%">头像</th>
        <th width="10%">手机号码</th>
        <th width="10%">身份证ID</th>
        </tr>
        </thead>
        <tbody>
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr target="sid_class" rel="<?php echo ($vo['nkey']); ?>">
                <td><?php echo ($vo['nkey']); ?></td>
                <td><?php echo ($vo['username']); ?></td>
                <td><?php echo ($vo['password']); ?></td>
                <td><?php echo ($vo['detail']); ?></td>
                <td><?php echo ($vo['resume']); ?></td>
                <td><?php echo ($vo['realname']); ?></td>
                <td height="50em"><img height="50em" src="<?php echo ($vo['pic']); ?>" /></td>
                <td><?php echo ($vo['tel']); ?></td>
                <td><?php echo ($vo['oid']); ?></td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>
    <div class="panelBar">
        <div class="pages">
            <span>共<?php echo ($totalCount); ?>条</span>
        </div>
        <div class="pagination" targetType="navTab" totalCount="<?php echo ($totalCount); ?>" numPerPage="<?php echo ($numPerPage); ?>" pageNumShown="10" currentPage="<?php echo ($currentPage); ?>"></div>
    </div>
</div>