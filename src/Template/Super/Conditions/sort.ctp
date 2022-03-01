<?php
/** @var  $conditions \App\Model\Entity\Condition[] */
/** @var \App\View\AppView $this */
?>
<?= $this->Html->script('/plugins/jQueryUI/jquery-ui.min'); ?>
<section class="content-header">
    <h1 class="pull-left"><?= $this->Html->icon('list') . ' ' . __('Conditions') ?>
        <small><?= __('Sort') ?></small>
    </h1>
    <div class="btn-group pull-right">
        <?= $this->Html->link($this->Html->icon('chevron-left') . ' ' . $this->Html->tag('span', __('Back'), ['class' => 'hidden-xs hidden-sm']),
            ['action' => 'index','type_condition_id'=>$typeCondition->id], ['escape' => false, 'class' => 'load-ajax btn btn-sm btn-default', 'update' => '#page-content-wrapper', 'title' => __('Back')]); ?>
    </div>
    <div class="clearfix"></div>
</section>
<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-default">
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="col-md-12 col-lg-8 no-padding" style="max-height:300px; overflow: hidden; overflow-y:auto;">
                        <ul class="todo-list" id="my-list">
                            <?php foreach ($conditions as $condition): ?>
                            <li id="condition_<?= $condition->id ?>" class="list-group-item ellipsis">
                                <!-- drag handle -->
                                <span class="handle">
                                    <i class="fa fa-ellipsis-v"></i>
                                    <i class="fa fa-ellipsis-v"></i>
                                </span>

                                <span class="text ellipsis-item">
                                    <?= __('Operator').' : '?>
                                </span><?=' ( '.\App\Model\Table\ConditionsTable::getOperators()[$condition->operator].' ), '?>

                                <span class="text ellipsis-item">
                                    <?= $label1.': '?>
                                </span><?=' ( '.$condition->compare.' ), '?>

                                <span class="text ellipsis-item">
                                    <?= $label2.': '?>
                                </span><?=' ( '.$condition->value.' )'?>


                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="pull-left">
                        <?= $this->Form->button($this->Html->icon('save') . ' ' . __('Save'), ['type' => 'button', 'id' => 'save', 'class' => 'btn-success', 'escape' => false]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $(function () {
        $("#save").click(function () {
            $.ajax({
                url: "<?= $this->Url->build(['controller' => 'conditions', 'action' => 'sort','type_condition_id'=>$typeCondition->id])?>",
                method: "post",
                data: $('#my-list').sortable("serialize"),
                success: function (data) {
                    $("#page-content-wrapper").html(data);
                },
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', getCookie('csrfToken'));
                }
            });
            return false;
        });
    });
    $('#my-list').sortable({
        placeholder         : 'sort-highlight',
        handle              : '.handle',
        forcePlaceholderSize: true,
        zIndex              : 999999,
        update: function (event, ui){
            var data = $(this).sortable('serialize');
        }
    });
</script>