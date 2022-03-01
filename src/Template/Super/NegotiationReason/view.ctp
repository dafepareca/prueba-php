<?php
/** @var  $negotiationReason \App\Model\Entity\Negotiation Reason */
/** @var  $Html \Cake\View\Helper\HtmlHelper*/
?>
<div class="modal-header modal-header-info">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><?= $this->Html->icon('info-circle').' '.__('Negotiation Reason Information') ?></h4>
</div>
<!-- /modal-header -->
<div class="modal-body">
    <dl class="dl-horizontal">
        <dt><?= __('Descption Reason') ?></dt>
        <dd><?= h($negotiationReason->Descption_reason) ?></dd>
        <dt><?= __('Code Reason') ?></dt>
        <dd><?= h($negotiationReason->code_reason) ?></dd>
        <dt><?= __('Id Reason') ?></dt>
        <dd><?= $this->Number->format($negotiationReason->id_reason) ?></dd>
        <dt><?= __('Codigo Terceros') ?></dt>
        <dd><?= h($negotiationReason->codigo_terceros) ?></dd>
    </dl>
</div>
<!-- /.modal-body -->
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Close') ?></button>
</div>
<!-- /modal-footer -->