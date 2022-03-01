<?php
/** @var  $condition \App\Model\Entity\Conditions */
/** @var  $Html \Cake\View\Helper\HtmlHelper*/
?>
<div class="modal-header modal-header-info">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><?= $this->Html->icon('info-circle').' '.__('Condition Information') ?></h4>
</div>
<!-- /modal-header -->
<div class="modal-body">
    <dl class="dl-horizontal">
        <dt><?= __('Types Condition') ?></dt>
        <dd><?= $condition->has('types_condition') ? $condition->types_condition->type : '' ?></dd>
        <dt><?= __('Operator') ?></dt>
        <dd><?= h(\App\Model\Table\ConditionsTable::getOperators()[$condition->operator]) ?></dd>
        <dt><?= $label2 ?></dt>
        <dd><?= h($condition->value) ?></dd>
        <dt><?= $label1 ?></dt>
        <dd><?= h($condition->compare) ?></dd>
    </dl>
</div>
<!-- /.modal-body -->
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Close') ?></button>
</div>
<!-- /modal-footer -->