<?php
/** @var  $rejected \App\Model\Entity\Logs */
/** @var  $Html \Cake\View\Helper\HtmlHelper*/
?>
<div class="modal-header modal-header-info">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><?= $this->Html->icon('info-circle').' '.__('Información Rechazo') ?></h4>
</div>
<!-- /modal-header -->
<div class="modal-body">
    <dl class="dl-horizontal">
        <dt><?= __('Created') ?></dt>
        <dd><?= $rejected->created->format('Y-m-d H:i:s') ?></dd>
        <dt><?= __('Rechazo ID') ?></dt>
        <dd><?= $rejected->history_customer_id ?></dd>
        <dt><?= __('Tipo Rechazo') ?></dt>
        <dd><?= $rejected->type_rejected->description ?></dd>
        <hr>
        <dt><?= __('User') ?></dt>
        <dd><?= $rejected->user->name ?></dd>
        <dt><?= __('User ID') ?></dt>
        <dd><?= $rejected->user->identification ?></dd>
        <dt><?= __('User Email') ?></dt>
        <hr>
        <dd><?= $rejected->user->email ?></dd>
        <dt><?= __('Customer Type Identification Id') ?></dt>
        <dd><?= $rejected->customer_type_identification->type ?></dd>
        <dt><?= __('Customer Identification') ?></dt>
        <dd><?= $rejected->customer_identification ?></dd>
        <hr>
        <dt><?= __('Detalle') ?></dt>
        <dd><?= $rejected->details ?></dd>
    </dl>
</div>
<!-- /.modal-body -->
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Close') ?></button>
</div>
<!-- /modal-footer -->