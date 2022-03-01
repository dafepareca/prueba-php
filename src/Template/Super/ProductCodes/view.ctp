<?php
/** @var  $productCode \App\Model\Entity\Product Codes */
/** @var  $Html \Cake\View\Helper\HtmlHelper*/
?>
<div class="modal-header modal-header-info">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><?= $this->Html->icon('info-circle').' '.__('Product Code Information') ?></h4>
</div>
<!-- /modal-header -->
<div class="modal-body">
    <dl class="dl-horizontal">
        <dt><?= __('Description') ?></dt>
        <dd><?= h($productCode->description) ?></dd>
        <dt><?= __('Code') ?></dt>
        <dd><?= $this->Number->format($productCode->code) ?></dd>
        <dt><?= __('Exclud Offer') ?></dt>
        <dd><?= $productCode->exclud_offer ? __('Yes') : __('No') ?></dd>
        <dt><?= __('Without Garment') ?></dt>
        <dd><?= $productCode->without_garment ? __('Yes') : __('No') ?></dd>
    </dl>
</div>
<!-- /.modal-body -->
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Close') ?></button>
</div>
<!-- /modal-footer -->