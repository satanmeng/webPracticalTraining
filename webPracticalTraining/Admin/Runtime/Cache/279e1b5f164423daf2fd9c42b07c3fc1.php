<?php if (!defined('THINK_PATH')) exit();?><div class="pageContent">

   <form method="post" action="__URL__/update/navTabId/__MODULE__?callbackType=closeCurrent" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone)">
        <input type="hidden" name="user_id" value="<?php echo $_SESSION[C('USER_AUTH_KEY')] ?>">
        <input type="hidden" name="nkey" value="<?php echo ($vo["nkey"]); ?>">
        <input type="hidden" name="ajax" value="1">
        <div class="pageFormContent" layoutH="58">
            <div class="unit">
                <label>列车号：</label>
                <input type="text" class="required" name="trains" value="<?php echo ($vo["trains"]); ?>">
            </div>
            <div class="unit">
                <label>起始站：</label>
                <input type="text" class="required" name="start" value="<?php echo ($vo["start"]); ?>">
            </div>
            <div class="unit">
                <label>终点站：</label>
                <input type="text" class="required" name="end" value="<?php echo ($vo["end"]); ?>">
            </div>
            <div class="unit">
                <label>始发时间：</label>
                <input type="text" class="date" name="stime" dateFmt="yyyy年MM月dd日 HH:mm" readonly="true" value="<?php echo ($vo["stime"]); ?>">
                <<a class="inputDateButton" href="javascript:;">选择</a>
            </div>
            <div class="unit">
                <label>票价：</label>
                <input type="text" class="required" name="inprice" value="<?php echo ($vo["inprice"]); ?>">
            </div>
            <div class="unit">
                <label>票数：</label>
                <input type="text" class="required" name="ticket" value="<?php echo ($vo["ticket"]); ?>">
            </div>
            <div class="unit">
                <label>状 态：</label>
                <SELECT name="show">
                    <option <?php if(($vo["show"]) == "1"): ?>selected<?php endif; ?> value="1">显示 </option>
                    <option <?php if(($vo["show"]) == "0"): ?>selected<?php endif; ?> value="0">隐藏 </option>
                </SELECT>
            </div>
        </div>
        <div class="formBar">
            <ul>
                <li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
                <li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
            </ul>
        </div>
    </form>
</div>