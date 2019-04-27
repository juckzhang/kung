<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\services\UploadService;
use common\services\UserService;

$userService = UserService::getService();
$career_name = '';
?>

<h2 class="contentTitle">视频编辑</h2>
<div class="pageContent">
    <form method="post"
          action="<?= Url::to(['service/edit-order', 'id' => ArrayHelper::getValue($model, 'id', '')]) ?>"
          class="pageForm required-validate" onsubmit="return validateCallback(this,navTabAjaxDone)">
        <div class="pageFormContent nowrap" layoutH="97">
            <p>
                <label>订单编号</label>
                <input type="text" name="ServiceOrderModel[order_no]"
                       value="<?= ArrayHelper::getValue($model, 'order_no') ?>" readonly="true">
            </p>
            <p>
                <label>租用时间:</label>
                <input type="text" class="form-control"  name="ServiceOrderModel[start_time]" id="qBeginTime" value="<?= date('Y-m-d',ArrayHelper::getValue($model, 'start_time', '')) ?>"/>
                <label style="width: 20px;">至</label>
                <input type="text" class="form-control" name="ServiceOrderModel[end_time]" id="qEndTime" value="<?= date('Y-m-d',ArrayHelper::getValue($model, 'end_time', '')) ?>"/>
            </p>

            <p>
                <label>订单金额:</label>
                <input type="text" name="ServiceOrderModel[amount]"
                       value="<?= ArrayHelper::getValue($model, 'amount') ?>" readonly="true">
            </p>

            <p>
                <label>审核状态:</label>
                <select name="ServiceOrderModel[status]">
                    <option value="0" <?= ArrayHelper::getValue($model, 'status') == 0?'selected':''?>>待审核</option>
                    <option value="1" <?= ArrayHelper::getValue($model, 'status') == 1?'selected':''?>>审核通过</option>
                    <option value="2" <?= ArrayHelper::getValue($model, 'status') == 2?'selected':''?>>审核不通过</option>
                    <option value="3" <?= ArrayHelper::getValue($model, 'status') == 3?'selected':''?>>已支付</option>
                    <option value="4" <?= ArrayHelper::getValue($model, 'status') == 4?'selected':''?>>未支付</option>
                    <option value="5" <?= ArrayHelper::getValue($model, 'status') == 5?'selected':''?>>已结束</option>
                </select>
            </p>

        </div>
        <div class="formBar">
            <ul>
                <li>
                    <div class="buttonActive">
                        <div class="buttonContent">
                            <button type="submit">提交</button>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="button">
                        <div class="buttonContent">
                            <button type="button" class="close">取消</button>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </form>
</div>

<script type="text/javascript">
    $('#qBeginTime').datepicker({
        format: "yyyy-mm-dd",
        //language: "zh-CN",
        todayBtn : "linked",
        autoclose : true,
        todayHighlight : true,
        beforeShowDay: disableSpecificDays,
        //endDate : new Date()
    }).on('changeDate',function(e){
        var startTime = e.date;
        $('#qEndTime').datepicker('setStartDate',startTime);
    });

    //结束时间：
    $('#qEndTime').datepicker({
        format: "yyyy-mm-dd",
        //language: "zh-CN",
        todayBtn : "linked",
        autoclose : true,
        todayHighlight : true,
        beforeShowDay: disableSpecificDays,
        //endDate : new Date()
    }).on('changeDate',function(e){
        var endTime = e.date;
        $('#qBeginTime').datepicker('setEndDate',endTime);
    });

    var disabledDays = <?=$disbales?>;//格式要与datepicker中的日期格式一致（yyyy/mm/dd）
    console.log(disabledDays);
    function disableSpecificDays(date) {
        //var date = new Date();
        var m = date.getMonth(), d = date.getDate(), y = date.getFullYear();
        var disabledDays = <?=$disbales?>;
        if (typeof(disabledDays) != "undefined") {
            for (var i = 0; i < disabledDays.length; i++) {
                if($.inArray(y + '-' + Appendzero(m+1) + '-' +Appendzero(d) ,disabledDays) != -1) {
                    return false;
                }
            }
        }
        return true;
    }

    function Appendzero(obj)
    {
        if(obj<10) return "0" +""+ obj;
        else return obj;
    }
</script>