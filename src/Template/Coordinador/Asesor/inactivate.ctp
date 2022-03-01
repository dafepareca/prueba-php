<?php
/** @var  $user \App\Model\Entity\User */
/** @var  $accessGroups \App\Model\Entity\AccessGroup[] */
/** @var  $Html \Cake\View\Helper\HtmlHelper */
?>
<section class="content-header">
    <h1 class="pull-left"><?= $this->Html->icon('users') . ' ' . __('Administrators') ?> |
        <small><?= __('Inactivate') ?></small>
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
                        <div class="form-group">
                            <label class="control-label col-md-4"><?= __('Mobile') ?></label>
                            <div class="col-md-8">
                                <p class="form-control-static"><?= h($user->mobile) ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4"><?= __('Access Group') ?></label>
                            <div class="col-md-8">
                                <p class="form-control-static"><?= h($user->access_group->name) ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4"><?= __('Status') ?></label>
                            <div class="col-md-8">
                                <p class="form-control-static"><?= $user->status? 'Active': 'Inactive' ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-offset-4 col-md-8">
                            <?= $this->Nested->_thumb('photo', $user->attachment, 'large', ['id' => 'avatar', 'class' => 'img-thumbnail', 'style' => 'margin-bottom:6px']) ?>
                        </div>
                        <?= $this->Form->input('description', ['rows' => 3, 'required' => true, 'label' => __('Inactivation reason')]) ?>
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
</script>