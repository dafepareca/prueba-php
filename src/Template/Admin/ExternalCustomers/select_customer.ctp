<?php
/** @var  $externalCustomer \App\Model\Entity\ExternalCustomer */
/** @var  $Html \Cake\View\Helper\HtmlHelper */
?>
<?= $this->Form->create($externalCustomer, [
    'class' => 'form-ajax form-generic',
    'update' => '#page-content-wrapper',
    'horizontal' => true
]) ?>

<div class="modal-header modal-header-default">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><?= $this->Html->icon('info-circle') . ' ' . __('Select customer by create External Customer') ?></h4>
</div>
<!-- /modal-header -->
<div class="modal-body">
    <?= $this->Form->input('customer_id', ['empty' => __('Select Customer') ]) ?>
</div>
<!-- /.modal-body -->
<div class="modal-footer">
    <?= $this->Form->button($this->Html->icon('angle-double-right') . ' ' . __('Next'), ['class' => 'btn-success', 'escape' => false]) ?>
</div>
<!-- /modal-footer -->
<?= $this->Form->end() ?>
