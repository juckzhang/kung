<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\services\UploadService;
use common\models\mysql\AdminModel;

$admin = AdminModel::find()->select('id,username')->asArray()->all();
?>
<?php $this->beginBody() ?>
<style>
    .pageFormContent .textInput {
        width: 100%;
    }
</style>
<h2 class="contentTitle">代金券生成</h2>
<div class="pageContent">
    <form method="post"
          action="<?= Url::to(['coupon/edit-coupon']) ?>"
          class="pageForm required-validate" onsubmit="return validateCallbacks(this,navTabAjaxDone)">
        <div class="pageFormContent nowrap" layoutH="97">
            <table class="table" width="1200" layoutH="138">
                <thead>
                <tr>
                    <th width="22"><input type="checkbox" group="ids[]" class="checkboxCtrl"></th>
                    <th width="120">模板名字</th>
                    <th width="80">金额</th>
                    <th width="80">数量</th>

                </tr>
                </thead>
                <tbody>
                <?php if($dataList):?>
                    <?php foreach($dataList as $key => $data):?>
                        <tr target="card-id" rel="<?=$data['id']?>">
                            <td><input name="ids" value="<?=$data['id']?>" type="checkbox"></td>
                            <td><?=$data['name']?></td>
                            <td><?=$data['sum']?></td>
                            <td><input type="text" name="num" value="0"/></td>
                        </tr>
                    <?php endforeach;?>
                <?php endif;?>
                </tbody>
            </table>

        </div>
        <div class="formBar">
            <ul>
                <li>
                    <div class="buttonActive">
                        <div class="buttonContent">
                            <button type="submit">生成</button>
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
    function validateCallbacks(form, callback, confirmMsg) {
        var $form = $(form);
        var chk_value = [];

        if (!$form.valid()) {
            return false;
        }
        if($("input[name='ids']:checked").length>0){
            $("input[name='ids']:checked").each(function () {
                var num = $(this).parent().parent().parent().find("input[name='num']").val();
                var template_id = $(this).val();
                if(num <= 0){
                    alert("数量不能为零");
                    return false;
                }else {
                    var res = {
                        template_id:template_id,
                        num:num,
                    }
                    chk_value.push(res);
                }
            });
        }else {
            alert('经勾选代金券模板');
            return false;
        }
        //console.log(chk_value);


        var _submitFn = function(){
            $form.find(':focus').blur();

            $.ajax({
                type: form.method || 'POST',
                url:$form.attr("action"),
                data:{coupon:chk_value},
                dataType:"json",
                cache: false,
                success: callback || DWZ.ajaxDone,
                error: DWZ.ajaxError
            });
        }

        if (confirmMsg) {
            alertMsg.confirm(confirmMsg, {okCall: _submitFn});
        } else {
            _submitFn();
        }

        return false;
    }
</script>
<?php $this->endBody() ?>
<?php $this->endPage() ?>

