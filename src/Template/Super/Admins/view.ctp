<?php
/** @var  $user \App\Model\Entity\User */
/** @var  $Html \Cake\View\Helper\HtmlHelper */
?>
<div class="modal-header modal-header-default">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><?= $this->Html->icon('info-circle') . ' ' . __('Administrator Information') ?></h4>
</div>
<!-- /modal-header -->
<div class="modal-body">
    <div class="box box-widget widget-user-2" style="margin-bottom: 0">
        <!-- Add the bg color to the header using any of the bg-* classes -->
        <div class="widget-user-header bg-gray">
            <div class="widget-user-image">
                <?= $this->Nested->_thumb('photo', $user->attachment, 'large', ['id' => 'avatar', 'class' => 'img-thumbnail']) ?>
            </div>
            <!-- /.widget-user-image -->
            <h3 class="widget-user-username"><?= h($user->name) ?></h3>
            <h5 class="widget-user-desc"><?= h($user->role->name) ?></h5>
            <h5 class="widget-user-desc"><?= $this->Html->label($user->user_status->name, $user->user_status->label) ?></h5>
            <div class="clearfix"></div>
        </div>
        <div class="box-footer no-padding">
            <ul class="nav nav-stacked">
                <li><a href="#"><?= $this->Html->icon('at').' '.__('Email') ?>
                    <span class="pull-right text-info"><?= h($user->email) ?></span></a></li>
                <li><a href="#"><?= $this->Html->icon('key').' '.__('Token') ?>
                        <span class="pull-right text-info"><?= is_null($user->token_visible) ? 'N/A' : $user->token_visible ?></span></a></li>
                <li><a href="#"><?= $this->Html->icon('phone').' '.__('Mobile') ?>
                    <span class="pull-right text-info"><?= h($user->mobile) ?></span></a></li>
                <li><a href="#"><?= $this->Html->icon('ban').' '.__('Access Group') ?>
                    <span class="pull-right text-info"><?= h($user->access_group->name) ?></span></a></li>
                <li><a href="#"><?= $this->Html->icon('clock-o').' '.__('Last Login') ?>
                    <span class="pull-right text-info"><?= is_null($user->last_login) ? '' : $user->last_login->format('Y-m-d h:i:s A') ?></span></a></li>
                <li><a href="#"><?= $this->Html->icon('clock-o').' '.__('Created') ?>
                        <span class="pull-right text-info"><?= $user->created->format('Y-m-d h:i:s A') ?></span></a></li>
                <li><a href="#"><?= $this->Html->icon('clock-o').' '.__('Modified') ?>
                        <span class="pull-right text-info"><?= $user->modified->format('Y-m-d h:i:s A') ?></span></a></li>

            </ul>
        </div>
    </div>
</div>
<!-- /.modal-body -->
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Close') ?></button>
</div>
<!-- /modal-footer -->