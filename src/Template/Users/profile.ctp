<?php
/** @var  $user \App\Model\Entity\User */
/** @var  $Html \Cake\View\Helper\HtmlHelper */
?>
<section class="content-header">
    <h1 class="pull-left"><?= $this->Html->icon('users') . ' ' . __('Users') ?> |
        <small><?= __('Profile') ?></small>
    </h1>
    <div class="btn-group pull-right">
        <?= $this->Html->link($this->Html->icon('chevron-left') . ' ' . $this->Html->tag('span', __('Back'), ['class' => 'hidden-xs hidden-sm']),
            $this->Url->build('/' . strtolower($current_user['role']['name']), true), ['escape' => false, 'class' => 'load-ajax btn btn-sm btn-default', 'update' => '#page-content-wrapper', 'title' => __('Back')]) ?>
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
                    <h3 class="box-title"><?= __('Profile Information') ?></h3>
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
                        <div class="form-group">
                            <label class="control-label col-md-4"><?= __('Name') ?></label>
                            <div class="col-md-8">
                                <p class="form-control-static"><?= h($user->name) ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4"><?= __('Email') ?></label>
                            <div class="col-md-8">
                                <p class="form-control-static"><?= h($user->email) ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-4"><?= __('Mobile') ?></label>
                            <div class="col-md-8">
                                <p class="form-control-static"><?= h($user->mobile) ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4"><?= __('Role') ?></label>
                            <div class="col-md-8">
                                <p class="form-control-static"><?= h($user->role->name) ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-offset-4 col-md-8">
                            <?= $this->Nested->_thumb('photo', $user->attachment, 'large', ['id' => 'avatar', 'class' => 'img-thumbnail', 'style' => 'margin-bottom:6px']) ?>
                        </div>
                        <?= $this->Form->input('attachment.photo', ['type' => 'file',
                            '_button' => ['class' => 'btn-info'], 'button-label' => __('Search')]) ?>
                        <?= $this->Form->input('attachment.file_dir', ['type' => 'hidden']) ?>
                        <?= $this->Form->input('attachment.file_type', ['type' => 'hidden']) ?>
                        <?= $this->Form->input('attachment.file_size', ['type' => 'hidden']) ?>
                        <?= $this->Form->input('attachment.model', ['type' => 'hidden', 'value' => 'User']) ?>
                    </div>
                    <div class="col-md-6">
                        <div class="callout callout-info" style="overflow: hidden;">
                            <div class="row" >
                                    <?= $this->Form->input('password_update', ['type' => 'password', 'label' => __('New Password'), 'prepend' => $this->Html->icon('key')]) ?>
                                    <?= $this->Form->input('password_confirm_update', ['type' => 'password', 'label' => __('Confirm Password'), 'prepend' => $this->Html->icon('key')]) ?>
                                <div class="clearfix"></div>
                                <p class="text-center"><?= $this->Html->icon('info-circle').' '.__('Leave empty if you do not want to change') ?></p>
                            </div>
                        </div>
                        <div class="clearfix"></div>
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
<script>
    $("#attachment-photo").change(function () {
        readURL(this, 'avatar');
    });
</script>