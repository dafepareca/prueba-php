<?php
/** @var  $setting \App\Model\Entity\Setting */
/** @var  $Html \Cake\View\Helper\HtmlHelper */
?>
<section class="content-header">
    <h1 class="pull-left"><?= $this->Html->icon('cog') . ' ' . __('Settings') ?> |
        <small><?= __('Edit') ?></small>
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
                    <h3 class="box-title"><?= __('Setting Information') ?></h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <?= $this->Form->create($setting, [
                    'class' => 'form-ajax',
                    'update' => '#page-content-wrapper',
                    'horizontal' => true
                ]) ?>
                <div class="box-body">
                    <div class="col-md-6">
                        <?= $this->Form->input('setting_category_id', ['options' => $settingCategories]) ?>
                        <?= $this->Form->input('name') ?>
                        <?= $this->Form->input('value', ['type' => 'textarea']) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $this->Form->input('label') ?>
                        <?= $this->Form->input('type_key', ['options' => $types]) ?>
                        <?= $this->Form->input('options') ?>
                        <?= $this->Form->input('description', ['rows' => 2]) ?>
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