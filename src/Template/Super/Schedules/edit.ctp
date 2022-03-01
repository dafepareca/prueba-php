<?php
/**
  * @var \App\View\AppView $this
  */
?>
<?php
/** @var  $schedule \App\Model\Entity\Schedule */
/** @var  $Html \Cake\View\Helper\HtmlHelper */
?>
<section class="content-header">
    <h1 class="pull-left"><?= $this->Html->icon('list') . ' ' . __('Schedules') ?> |
        <small><?= __('Edit') ?></small>
    </h1>
    <div class="btn-group pull-right">
        <?= $this->Html->link($this->Html->icon('chevron-left') . ' ' . $this->Html->tag('span', __('Back'), ['class' => 'hidden-xs hidden-sm']),
            ['action' => 'index','office_id' => $office->id], ['escape' => false, 'class' => 'load-ajax btn btn-sm btn-default', 'update' => '#page-content-wrapper', 'title' => __('Back')]); ?>
    </div>
    <div class="clearfix"></div>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= __('Schedule Information') ?></h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <?= $this->Form->create($schedule, [
                    'class' => 'form-ajax',
                    'update' => '#page-content-wrapper',
                    'horizontal' => true
                ]) ?>
                <div class="box-body">
                    <?= $this->Form->input('start_time',['class' => 'timepicker', 'type' => 'text']) ?>
                    <?= $this->Form->input('end_time',['class' => 'timepicker', 'type' => 'text']) ?>
                    <?= $this->Form->input('commentary') ?>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="pull-left">
                        <?= $this->Form->button($this->Html->icon('save').' '.__('Save'), ['class' => 'btn-success', 'escape' => false]) ?>
                    </div>
                    <div class="pull-right">
                        <i>* <?= __('Required fields') ?></i>
                    </div>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</section>

<script>
    $('.timepicker').datetimepicker({
        locale: 'LT',
        format: 'LT'
    });
</script>