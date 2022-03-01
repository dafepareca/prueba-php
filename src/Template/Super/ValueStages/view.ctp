<?php
/** @var  $valueStage \App\Model\Entity\Value Stages */
/** @var  $Html \Cake\View\Helper\HtmlHelper*/
?>
<div class="modal-header modal-header-info">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><?= $this->Html->icon('info-circle').' '.__('Value Stage Information') ?></h4>
</div>
<!-- /modal-header -->
<div class="modal-body">
    <dl class="dl-horizontal">
        <dt><?= __('Legal Code') ?></dt>
        <dd><?= $valueStage->has('legal_code') ? $valueStage->legal_code->id : '' ?></dd>
        <dt><?= __('City Office') ?></dt>
        <dd><?= $valueStage->has('city_office') ? $valueStage->city_office->name : '' ?></dd>
        <dt><?= __('Value') ?></dt>
        <dd><?= $this->Number->format($valueStage->value) ?></dd>
    </dl>
</div>
<!-- /.modal-body -->
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Close') ?></button>
</div>
<!-- /modal-footer -->