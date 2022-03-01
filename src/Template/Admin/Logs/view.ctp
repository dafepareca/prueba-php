<?php
/** @var  $log \App\Model\Entity\Logs */
/** @var  $Html \Cake\View\Helper\HtmlHelper*/
?>
<div class="modal-header modal-header-info">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><?= $this->Html->icon('info-circle').' '.__('Log Information') ?></h4>
</div>
<!-- /modal-header -->
<div class="modal-body">
    <dl class="dl-horizontal">
        <dt><?= __('User') ?></dt>
        <dd><?= $log->has('user') ? $log->user->name : '' ?></dd>
        <dt><?= __('Customer Type Identification Id') ?></dt>
        <dd><?= $this->Number->format($log->customer_type_identification_id) ?></dd>
        <dt><?= __('Customer Identification') ?></dt>
        <dd><?= $this->Number->format($log->customer_identification) ?></dd>
        <dt><?= __('Answer') ?></dt>
        <dd><?= $log->answer ? __('Yes') : __('No') ?></dd>
        <dt><?= __('Created') ?></dt>
        <dd><?= $log->created->format('Y-m-d h:i:s A') ?></dd>
    </dl>
</div>
<!-- /.modal-body -->
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Close') ?></button>
</div>
<!-- /modal-footer -->