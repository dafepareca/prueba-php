<?php
/** @var  $customer \App\Model\Entity\Customer */
/** @var  $businessUnits \App\Model\Entity\BusinessUnit[] */
/** @var  $Html \Cake\View\Helper\HtmlHelper */
?>
<section class="content-header">
    <h1 class="pull-left"><?= $this->Html->icon('list') . ' ' . __('Customers') ?> |
        <small><?= __('Edit') ?></small>
    </h1>
    <div class="btn-group pull-right">
        <?= $this->Html->link($this->Html->icon('chevron-left') . ' ' . $this->Html->tag('span', __('Back'), ['class' => 'hidden-xs hidden-sm']),
            ['action' => 'index'], ['escape' => false, 'class' => 'load-ajax btn btn-sm btn-default', 'update' => '#page-content-wrapper', 'title' => __('Back')]) ?>
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
                    <h3 class="box-title"><?= __('Customer Information') ?></h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <?php $this->Form->setConfig('columns', [
                    'sm' => [
                        'label' => 3,
                        'input' => 9,
                        'error' => 0
                    ],
                    'md' => [
                        'label' => 4,
                        'input' => 8,
                        'error' => 0
                    ]
                ]);
                ?>
                <?= $this->Form->create($customer, [
                    'class' => 'form-ajax',
                    'update' => '#page-content-wrapper',
                    'horizontal' => true
                ]) ?>
                <div class="box-body">
                    <div class="box-body">
                        <div class="col-sm-6">
                            <?= $this->Form->input('name') ?>
                            <?= $this->Form->input('contact_name') ?>
                            <?= $this->Form->input('contact_email', ['type' => 'email', 'prepend' => $this->Html->icon('at')]) ?>
                            <?= $this->Form->input('contact_position'); ?>
                            <?= $this->Form->input('contact_phone', ['prepend' => $this->Html->icon('phone')]) ?>
                        </div>
                        <div class="col-sm-6">
                            <?= $this->Form->input('business_units._ids', ['options' => $businessUnits, 'multiple' => 'checkbox']) ?>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="pull-left">
                        <?= $this->Form->button($this->Html->icon('save') . ' ' . __('Save'), ['class' => 'btn-success', 'escape' => false]) ?>
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