<?php
/** @var  $customer \App\Model\Entity\Customer */
/** @var  $Html \Cake\View\Helper\HtmlHelper */
?>
<div class="modal-header modal-header-info">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><?= $this->Html->icon('info-circle') . ' ' . __('Customer Information'); ?></h4>
</div>
<!-- /modal-header -->
<div class="modal-body">
    <dl class="dl-horizontal">
        <dt><?= __('Name') ?></dt>
        <dd><?= h($customer->name) ?></dd>
        <dt><?= __('Contact Name') ?></dt>
        <dd><?= h($customer->contact_name) ?></dd>
        <dt><?= __('Contact Email') ?></dt>
        <dd><?= h($customer->contact_email) ?></dd>
        <dt><?= __('Contact Position') ?></dt>
        <dd><?= h($customer->contact_position) ?></dd>
        <dt><?= __('Contact Phone') ?></dt>
        <dd><?= h($customer->contact_phone) ?></dd>
        <dt><?= __('Business Unit Count') ?></dt>
        <dd><?= $this->Number->format($customer->business_units_count) ?></dd>
        <dt><?= __('Business Units') ?></dt>
        <dd><?= $this->Nested->label($customer->business_units, 'name') ?></dd>
        <dt><?= __('Created') ?></dt>
        <dd><?= $customer->created->format('Y-m-d h:i:s A') ?></dd>
        <dt><?= __('Modified') ?></dt>
        <dd><?= $customer->modified->format('Y-m-d h:i:s A') ?></dd>
    </dl>
</div>
<!-- /.modal-body -->
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Close') ?></button>
</div>
<!-- /modal-footer -->