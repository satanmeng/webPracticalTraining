<?php if (!defined('THINK_PATH')) exit();?><form id="pagerForm" action="__URL__" method="post">
    <input type="hidden" name="pageNum" value="1"/>
    <input type="hidden" name="_order" value="<?php echo ($_REQUEST["_order"]); ?>"/>
    <input type="hidden" name="trains" value="<?php echo ($_REQUEST["trains"]); ?>"/>
    <input type="hidden" name="_sort" value="<?php echo ($_REQUEST["_sort"]); ?>"/>
    <input type="hidden" name="relKeyword" value="<?php echo ($_REQUEST['relKeyword']); ?>"/>
</form>
<div class="pageHeader">
    <form onsubmit="return navTabSearch(this);" action="__URL__" method="post">
        <div class="searchBar">
            <ul class="searchContent">
                <li>
                    <label>列车名：</label>
                    <input type="text" name="trains" class="medium" value="<?php echo ($_REQUEST["trains"]); ?>">
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
           <li><a class="add" href="__URL__/add" target="dialog" mask="true"><span>新增</span></a></li>
            <li><a class="delete" href="__URL__/delete/nkey/{sid_class}/navTabId/__MODULE__" target="ajaxTodo" calback="navTabAjaxDone" title="你确定要删除吗？" warn="请选择节点"><span>删除</span></a></li>
            <li><a class="edit" href="__URL__/edit/nkey/{sid_class}" target="dialog" mask="true" warn="请选择节点"><span>修改</span></a></li>
        </ul>
    </div>
    <table class="table" width="100%" layoutH="138">
        <thead>
            <tr>
                <th width="5%">编号</th>
                <th width="15%">列车号</th>
                <th width="20%">起始站</th>
                <th width="20%">终点站</th>
                <th width="20%">始发时间</th>
                <th width="15%">票价</th>
                <th width="15%">票数</th>
            </tr>
        </thead>
        <tbody>
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr target="sid_class" rel="<?php echo ($vo['nkey']); ?>">
                <td><?php echo ($vo['nkey']); ?></td>
                <td><?php echo ($vo['trains']); ?></td>
                <td><?php echo ($vo['start']); ?></td>
                <td><?php echo ($vo['end']); ?></td>
                <td><?php echo ($vo['stime']); ?></td>
                <td><?php echo ($vo['inprice']); ?></td>
                <td><?php echo ($vo['ticket']); ?></td>
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