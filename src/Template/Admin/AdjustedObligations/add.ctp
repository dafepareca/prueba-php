<?php
/** @var  $adjustedObligation \App\Model\Entity\Adjusted Obligation */
/** @var  $Html \Cake\View\Helper\HtmlHelper */
?>
<section class="content-header">
    <h1 class="pull-left"><?= $this->Html->icon('list') . ' ' . __('Adjusted Obligations') ?> |
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
                    <h3 class="box-title"><?= __('Adjusted Obligation Information') ?></h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <?= $this->Form->create($adjustedObligation, [
                    'class' => 'form-ajax',
                    'update' => '#page-content-wrapper',
                    'horizontal' => true
                ]) ?>
                <div class="box-body">
                    <?= $this->Form->input('type_identification') ?>
                    <?= $this->Form->input('identification') ?>
                    <?= $this->Form->input('obligation') ?>
                    <?= $this->Form->input('strategy') ?>
                    <?= $this->Form->input('total_debt') ?>
                    <?= $this->Form->input('annual_effective_rate') ?>
                    <?= $this->Form->input('nominal_rate') ?>
                    <?= $this->Form->input('months_term') ?>
                    <?= $this->Form->input('customer_revenue') ?>
                    <?= $this->Form->input('customer_paid_capacity') ?>
                    <?= $this->Form->input('payment_agreed') ?>
                    <?= $this->Form->input('previous_minimum_payment') ?>
                    <?= $this->Form->input('initial_fee') ?>
                    <?= $this->Form->input('new_fee') ?>
                    <?= $this->Form->input('documentation_date', ['empty' => true, 'default' => '']) ?>
                    <?= $this->Form->input('office_name') ?>
                    <?= $this->Form->input('customer_email') ?>
                    <?= $this->Form->input('customer_telephone') ?>
                    <?= $this->Form->input('date_negotiation') ?>
                    <?= $this->Form->input('user_dataweb') ?>
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