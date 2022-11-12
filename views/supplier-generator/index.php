<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap4\Modal;

$this->title = Yii::t('app', '供应商管理');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="supplier-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?=  Html::a('导出', '#', [

            'class' => 'btn btn-success',

            'data-toggle' => 'modal',

            'data-target' => '#page-modal'    //此处对应Modal组件中设置的id

        ]) ?>
        <?= Html::a(Yii::t('app', '新增'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(['id' => 'suppliers']); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'id'   => 'mySupplierGridView',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //复选框列
            [
                'class' => 'yii\grid\CheckboxColumn',
                'name' => 'id',
            ],
            [
                //设置字段显示标题
                'label' => 'ID',
                //字段名
                'attribute' => 'id',
                //格式化
                'format' => 'raw',
                //设置单元格样式
                'headerOptions' => [
                    'style' => 'width:120px;',
                ],
                'footerOptions' => ['class'=>'hide']
            ],
            [
                'label' => '供应商名称',
                'attribute' => 'name',
                'format' => 'raw',
                'footerOptions' => ['class'=>'hide']
            ],
            [
                'label' => '供应商编码',
                'attribute' => 'code',
                'format' => 'raw',
                'footerOptions' => ['class'=>'hide']
            ],
            [
                'label' => '状态',
                //设置筛选选项
                'filter' => ['ok' => '通过', 'hold' => '待审核'],
                'attribute' => 't_status',
                'format' => 'raw',
                'value' => function ($data) {
                    return ($data->t_status == 'ok') ? '通过' : '待审核';
                },
                'footerOptions' => ['class'=>'hide']
            ],
        ],
        'pager' => [
            'linkContainerOptions' => [
                'class' => 'page-item'
            ],
            'linkOptions' => [
                'class' => 'page-link'
            ],
            'disabledListItemSubTagOptions' => [
                'tag' => 'a',
                'href' => 'javascript:;',
                'tabindex' => '-1',
                'class' => 'page-link'
            ]
        ],
    ]); ?>
    <?php


    Modal::begin([
        'id' => 'page-modal',
        'title' => '<h5>选择要导出的字段</h5>',
//        'toggleButton' => ['label' => 'click me'],
    ]);

    echo '<div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" name="export_field" id="inlineCheckbox1" value="name">
        <label class="form-check-label" for="inlineCheckbox1">名称</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="checkbox"   name="export_field" id="inlineCheckbox2" value="code">
      <label class="form-check-label" for="inlineCheckbox2">供应商编码</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="checkbox" name="export_field"  id="inlineCheckbox3" value="t_status">
      <label class="form-check-label" for="inlineCheckbox3">状态</label>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
        <button type="button" class="btn btn-primary" id="export">确定</button>
      </div>';

    Modal::end();
    ?>
    <?
    Modal::begin([
    'id' => 'download-modal',
    'title' => '<h5>下载</h5>',
    ]);

    echo '<a id="download" >点击下载</a>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
    </div>';

    Modal::end();
    ?>
</div>
<?php echo Html::jsFile('@web/js/jquery-3.3.1.min.js'); ?>
<script type="text/javascript">
    $("#export").on("click", function () {
        var ids = $("#mySupplierGridView").yiiGridView('getSelectedRows');
        var export_field = []; // 需要导出的字段
        if (ids.length == 0) {
            alert('请选择要导出的数据行');
            return false;
        }
        $('input[name="export_field"]:checked').each(function() {
            export_field.push($(this).val());
        })
        if(export_field.length == 0) {
            alert('请选择要导出的字段');
            return false;
        }
        var data = {'ids': ids, 'export_field': export_field};
        $.post("index.php?r=supplier-generator/export", data, function(result){

            var res = JSON.parse(result)
            if (res.code != 0) {
                alert(res.message);
                return false;
            }
            if (res.data.file_name) {
                $('#page-modal').modal('hide'); // 关闭选择字段对话框
                $('#download-modal').modal('show'); // 打开下载文件对话框
                // 给a标签赋值跳转链接
                $('#download').attr('href', 'index.php?r=supplier-generator/download&file_name=' + res.data.file_name)
            }

        });

    });
</script>
