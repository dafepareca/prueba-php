<?php
/** @var  $annualEffectiveRateTdc \App\Model\Entity\Annual Effective Rate Tdc */
/** @var  $Html \Cake\View\Helper\HtmlHelper*/
?>
<div class="modal-header modal-header-info">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><?= $this->Html->icon('info-circle').' '.__('Annual Effective Rate Tdc Information') ?></h4>
</div>
<!-- /modal-header -->
<div class="modal-body">
    <dl class="dl-horizontal">
        <dt><?= __('Rate') ?></dt>
        <dd><?= $this->Number->format($annualEffectiveRateTdc->rate) ?></dd>
        <dt><?= __('Fecha') ?></dt>
        <dd><?= $annualEffectiveRateTdc->fecha->format('Y-m-d h:i:s A') ?></dd>
        <dt><?= __('Created') ?></dt>
        <dd><?= $annualEffectiveRateTdc->created->format('Y-m-d h:i:s A') ?></dd>
        <dt><?= __('Modified') ?></dt>
        <dd><?= $annualEffectiveRateTdc->modified->format('Y-m-d h:i:s A') ?></dd>
    </dl>
</div>
<!-- /.modal-body -->
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Close') ?></button>
</div>
<!-- /modal-footer -->