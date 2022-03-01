<?php
/** @var  $annualEffectiveRateUvr \App\Model\Entity\Annual Effective Rate Uvr */
/** @var  $Html \Cake\View\Helper\HtmlHelper*/
?>
<div class="modal-header modal-header-info">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><?= $this->Html->icon('info-circle').' '.__('UVR Rate Information') ?></h4>
</div>
<!-- /modal-header -->
<div class="modal-body">
    <dl class="dl-horizontal">
        <dt><?= __('Rate') ?></dt>
        <dd><?= $this->Number->format($annualEffectiveRateUvr->rate) ?></dd>
        <dt><?= __('Value') ?></dt>
        <dd><?= $this->Number->format($annualEffectiveRateUvr->value) ?></dd>
        <dt><?= __('Fecha') ?></dt>
        <dd><?= $annualEffectiveRateUvr->month_date->format('Y-m-d h:i:s A') ?></dd>
        <dt><?= __('Created') ?></dt>
        <dd><?= $annualEffectiveRateUvr->created->format('Y-m-d h:i:s A') ?></dd>
        <dt><?= __('Modified') ?></dt>
        <dd><?= $annualEffectiveRateUvr->modified->format('Y-m-d h:i:s A') ?></dd>
    </dl>
</div>
<!-- /.modal-body -->
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Close') ?></button>
</div>
<!-- /modal-footer -->