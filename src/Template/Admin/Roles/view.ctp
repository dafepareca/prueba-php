<?php
/** @var  $role \App\Model\Entity\Role */
/** @var  $Html \Cake\View\Helper\HtmlHelper */
?>
<div class="modal-header modal-header-info">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><?= $this->Html->icon('info-circle') . ' ' . __('Role Information') ?></h4>
</div>
<!-- /modal-header -->
<div class="modal-body">
    <dl class="dl-horizontal">
        <dt><?= __('Name') ?></dt>
        <dd><?= h($role->name) ?></dd>
        <dt><?= __('Prefix') ?></dt>
        <dd><?= h($role->prefix) ?></dd>
        <dt><?= __('Created') ?></dt>
        <dd><?= $role->created->format('Y-m-d h:i:s A') ?></dd>
        <dt><?= __('Modified') ?></dt>
        <dd><?= $role->modified->format('Y-m-d h:i:s A') ?></dd>
    </dl>
</div>
<!-- /.modal-body -->
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Close') ?></button>
</div>
<!-- /modal-footer -->