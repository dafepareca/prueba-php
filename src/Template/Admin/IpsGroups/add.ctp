<?php
/** @var  $ipsGroup \App\Model\Entity\IpsGroup */
/** @var  $accessGroup \App\Model\Entity\AccessGroup */
/** @var  $Html \Cake\View\Helper\HtmlHelper */
?>
<section class="content-header">
    <h1 class="pull-left"><?= $this->Html->icon('list') . ' ' . __('Ips Groups') ?>
        <span class="label lb-md label-default"><?= h($accessGroup->name ) ?></span>
        <small><?= __('Add') ?></small>
    </h1>
    <div class="btn-group pull-right">
        <?= $this->Html->link($this->Html->icon('chevron-left') . ' ' . $this->Html->tag('span', __('Back'), ['class' => 'hidden-xs hidden-sm']),
            array_merge(['action' => 'index'], $this->request->getQuery()),
            ['escape' => false, 'class' => 'load-ajax btn btn-sm btn-default', 'update' => '#page-content-wrapper', 'title' => __('Back')]) ?>
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
                    <h3 class="box-title"><?= __('Ips Group Information') ?></h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <?= $this->Form->create($ipsGroup, [
                    'class' => 'form-ajax',
                    'update' => '#page-content-wrapper',
                    'horizontal' => true
                ]) ?>
                <div class="box-body">
                    <div class="cols col-md-8">
                        <?= $this->Form->input('name') ?>
                        <?= $this->Form->input('ip_address', ['prepend' => $this->Html->icon('laptop'),
                            'pattern' => '\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}',
                            'title' => 'IPv4 0.0.0.0'
                        ]) ?>
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