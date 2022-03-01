<?php
/** @var  $legalCode \App\Model\Entity\Legal Code */
/** @var  $Html \Cake\View\Helper\HtmlHelper */
?>
<section class="content-header">
    <h1 class="pull-left"><?= $this->Html->icon('list') . ' ' . __('Legal Codes') ?> |
        <small><?= __('Add') ?></small>
    </h1>
    <div class="btn-group pull-right">
        <?= $this->Html->link($this->Html->icon('chevron-left') . ' ' . $this->Html->tag('span', __('Back'), ['class' => 'hidden-xs hidden-sm']),
            ['action' => 'index'], ['escape' => false, 'class' => 'load-ajax btn btn-sm btn-default', 'update' => '#page-content-wrapper', 'title' => __('Back')]); ?>
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
                    <h3 class="box-title"><?= __('Legal Code Information') ?></h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <?= $this->Form->create($legalCode, [
                    'class' => 'form-ajax',
                    'update' => '#page-content-wrapper',
                    'horizontal' => true
                ]) ?>
                <div class="box-body">
                    <div class="col-md-8">
                    <?= $this->Form->input('code') ?>
                    <?= $this->Form->input('description', ['type' => 'textarea']) ?>
                    <?= $this->Form->input('apply_mortgage_credit') ?>
                    <?= $this->Form->input('apply_consumer_credit') ?>
                    <?= $this->Form->input('probability') ?>
                    <?= $this->Form->input('term') ?>
                    <?= $this->Form->control('minimum_payment',['options'=>array(
                        1 => 'No aplica',
                        2 => 'Promedio entre saldo total y contable',
                        3 => 'El mayor valor entre la liquidación aprobada y el saldo contable'
                    )])
                    ?>
                    </div>
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