<?php
/** @var  $office \App\Model\Entity\Offices */
/** @var  $Html \Cake\View\Helper\HtmlHelper*/
?>
<div class="modal-header modal-header-info">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><?= $this->Html->icon('info-circle').' '.__('Office Information') ?></h4>
</div>
<!-- /modal-header -->
<div class="modal-body">
    <dl class="dl-horizontal">
        <dt><?= __('Name') ?></dt>
        <dd><?= h($office->name) ?></dd>
        <dt><?= __('Address') ?></dt>
        <dd><?= h($office->address) ?></dd>
        <dt><?= __('City Office') ?></dt>
        <dd><?= $office->has('city_office') ? $office->city_office->name : '' ?></dd>
        <dt><?= __('State') ?></dt>
        <dd><?= $office->state ? __('Yes') : __('No') ?></dd>
    </dl>
</div>
<!-- /.modal-body -->
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Close') ?></button>
</div>
<!-- /modal-footer -->