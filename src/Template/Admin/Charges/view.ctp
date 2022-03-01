<?php
/** @var  $charge \App\Model\Entity\Charges */
/** @var  $Html \Cake\View\Helper\HtmlHelper*/
?>
<div class="modal-header modal-header-info">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><?= $this->Html->icon('info-circle').' '.__('Charge Information') ?></h4>
</div>
<!-- /modal-header -->
<div class="modal-body">
    <dl class="dl-horizontal">
        <dt><?= __('Name File') ?></dt>
        <dd><?= h($charge->name_file) ?></dd>
        <dt><?= __('State') ?></dt>
        <dd><?= $this->Number->format($charge->state) ?></dd>
        <dt><?= __('Records Obligation') ?></dt>
        <dd><?= $this->Number->format($charge->records_obligation) ?></dd>
        <dt><?= __('Failed Obligation') ?></dt>
        <dd><?= $this->Number->format($charge->failed_obligation) ?></dd>
        <dt><?= __('Records Customer') ?></dt>
        <dd><?= $this->Number->format($charge->records_customer) ?></dd>
        <dt><?= __('Failed Customer') ?></dt>
        <dd><?= $this->Number->format($charge->failed_customer) ?></dd>
        <dt><?= __('Created') ?></dt>
        <dd><?= $charge->created->format('Y-m-d h:i:s A') ?></dd>
    </dl>
</div>
<!-- /.modal-body -->
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Close') ?></button>
</div>
<!-- /modal-footer -->