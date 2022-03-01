<?php
/** @var  $legalCode \App\Model\Entity\Legal Codes */
/** @var  $Html \Cake\View\Helper\HtmlHelper*/
?>
<div class="modal-header modal-header-info">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><?= $this->Html->icon('info-circle').' '.__('Legal Code Information') ?></h4>
</div>
<!-- /modal-header -->
<div class="modal-body">
    <dl class="dl-horizontal">
        <dt><?= __('Code') ?></dt>
        <dd><?= h($legalCode->code) ?></dd>
        <dt><?= __('Apply Mortgage Credit') ?></dt>
        <dd><?= $legalCode->apply_mortgage_credit ? __('Yes') : __('No') ?></dd>
        <dt><?= __('Apply Consumer Credit') ?></dt>
        <dd><?= $legalCode->apply_consumer_credit ? __('Yes') : __('No') ?></dd>
        <dt><?= __('Description') ?></dt>
        <dd><?= $this->Text->autoParagraph(h($legalCode->description)) ?></dd>
        <dt><?= __('Modified') ?></dt>
        <dd><?= $legalCode->modified->format('Y-m-d h:i:s A') ?></dd>
        <dt><?= __('Created') ?></dt>
        <dd><?= $legalCode->created->format('Y-m-d h:i:s A') ?></dd>
    </dl>
</div>
<!-- /.modal-body -->
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Close') ?></button>
</div>
<!-- /modal-footer -->