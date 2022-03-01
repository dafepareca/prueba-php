<?php
/** @var  $busines \App\Model\Entity\Business */
/** @var  $Html \Cake\View\Helper\HtmlHelper*/
?>
<div class="modal-header modal-header-info">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><?= $this->Html->icon('info-circle').' '.__('Busines Information') ?></h4>
</div>
<!-- /modal-header -->
<div class="modal-body">
    <dl class="dl-horizontal">
        <dt><?= __('Name') ?></dt>
        <dd><?= h($busines->name) ?></dd>
        <dt><?= __('nit') ?></dt>
        <dd><?= h($busines->nit) ?></dd>
        <dt><?= __('name_contact') ?></dt>
        <dd><?= h($busines->name_contact) ?></dd>
        <dt><?= __('phone_contact') ?></dt>
        <dd><?= h($busines->phone_contact) ?></dd>
        <dt><?= __('Created') ?></dt>
        <dd><?= $busines->created->format('Y-m-d h:i:s A') ?></dd>
        <dt><?= __('Modified') ?></dt>
        <dd><?= $busines->modified->format('Y-m-d h:i:s A') ?></dd>
    </dl>
</div>
<!-- /.modal-body -->
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Close') ?></button>
</div>
<!-- /modal-footer -->