<?php
/** @var  $typeObligation \App\Model\Entity\Type Obligations */
/** @var  $Html \Cake\View\Helper\HtmlHelper*/
?>
<div class="modal-header modal-header-info">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><?= $this->Html->icon('info-circle').' '.__('Type Obligation Information') ?></h4>
</div>
<!-- /modal-header -->
<div class="modal-body">
    <dl class="dl-horizontal">
        <dt><?= __('Type') ?></dt>
        <dd><?= h($typeObligation->type) ?></dd>
        <dt><?= __('Description') ?></dt>
        <dd><?= h($typeObligation->description) ?></dd>
        <dt><?= __('Term') ?></dt>
        <dd><?= $this->Number->format($typeObligation->term) ?></dd>
    </dl>
</div>
<!-- /.modal-body -->
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Close') ?></button>
</div>
<!-- /modal-footer -->