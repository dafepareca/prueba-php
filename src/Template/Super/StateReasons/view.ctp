<?php
/** @var  $stateReason \App\Model\Entity\State Reasons */
/** @var  $Html \Cake\View\Helper\HtmlHelper*/
?>
<div class="modal-header modal-header-info">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><?= $this->Html->icon('info-circle').' '.__('State Reason Information') ?></h4>
</div>
<!-- /modal-header -->
<div class="modal-body">
    <dl class="dl-horizontal">
        <dt><?= __('State') ?></dt>
        <dd><?= h($stateReason->state) ?></dd>
        <dt><?= __('Created') ?></dt>
        <dd><?= $stateReason->created->format('Y-m-d h:i:s A') ?></dd>
        <dt><?= __('Modified') ?></dt>
        <dd><?= $stateReason->modified->format('Y-m-d h:i:s A') ?></dd>
    </dl>
</div>
<!-- /.modal-body -->
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Close') ?></button>
</div>
<!-- /modal-footer -->