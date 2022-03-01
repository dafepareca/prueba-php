<?php
/** @var  $user \App\Model\Entity\User */
/** @var  $accessGroups \App\Model\Entity\AccessGroup[] */
/** @var  \App\View\AppView $this */
?>
<section class="content-header">
    <h1 class="pull-left"><?= $this->Html->icon('users') . ' ' . __('Administrators') ?> |
        <small><?= __('Edit') ?></small>
    </h1>
    <div class="btn-group pull-right">
        <?= $this->Html->link($this->Html->icon('chevron-left') . ' ' . $this->Html->tag('span', __('Back'), ['class' => 'hidden-xs hidden-sm']),
            ['controller'=>'Users','action' => 'index'], ['escape' => false, 'class' => 'load-ajax btn btn-sm btn-default', 'update' => '#page-content-wrapper', 'title' => __('Back')]) ?>
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
                    <h3 class="box-title"><?= __('Administrator Information') ?></h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <?= $this->Form->create($user, [
                    'class' => 'form-ajax',
                    'update' => '#page-content-wrapper',
                    'horizontal' => true,
                    'type' => 'file'
                ]) ?>
                <div class="box-body">
                    <div class="col-md-6">
                        <?= $this->Form->control('name') ?>
                        <?= $this->Form->control('identification') ?>
                        <?= $this->Form->control('email', ['prepend' => $this->Html->icon('at')]) ?>
                        <?= $this->Form->control('mobile', ['prepend' => $this->Html->icon('phone')]) ?>
                        <?= $this->Form->control('access_group_id', ['prepend' => $this->Html->icon('ban'), 'options' => $accessGroups, 'empty' => __('Select Item')]) ?>
                        <?= $this->Form->control('busines_id', ['required' => true, 'prepend' => $this->Html->icon('ban'), 'options' => $businessGroups, 'empty' => __('Select Item')]) ?>
                        <?= $this->Form->control('user_status_id', ['options' => $userStatuses]) ?>
                    </div>
                    <div class="col-md-6">
                        <?php $this->Form->horizontal = false; ?>
                            <?=$this->Form->label(null,__('Habilitar Accesos'))?>
                            <?=$this->Form->control('admin_permission.data',['type' => 'checkbox'])?>
                            <?=$this->Form->control('admin_permission.create_profile',['type' => 'checkbox'])?>
                            <?=$this->Form->control('admin_permission.report',['type' => 'checkbox'])?>
                            <?=$this->Form->control('admin_permission.params',['type' => 'checkbox'])?>
                        <?php $this->Form->horizontal = true; ?>
                    </div>
                    <div class="clearfix"></div>
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
<script>
    $("#attachment-photo").change(function(){
        readURL(this, 'avatar');
    });

    $('input#name').bind('keyup', function(event) {
        var $this = $(this);
        var str = $this.val().toLowerCase().replace(/\b[a-z]/g, function(letter) {
            return letter.toUpperCase();
        });
        $this.val(function() {
            return str;
        });
    });
</script>