<?php
/** @var  $schedule \App\Model\Entity\Schedules */
/** @var  $Html \Cake\View\Helper\HtmlHelper*/
?>
<div class="modal-header modal-header-info">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><?= $this->Html->icon('info-circle').' '.__('Schedule Information') ?></h4>
</div>
<!-- /modal-header -->
<div class="modal-body">
    <dl class="dl-horizontal">
        <dt><?= __('Commentary') ?></dt>
        <dd><?= h($schedule->commentary) ?></dd>
        <dt><?= __('Office') ?></dt>
        <dd><?= $schedule->has('office') ? $schedule->office->name : '' ?></dd>
        <dt><?= __('Start Time') ?></dt>
        <dd><?= $schedule->start_time->format('Y-m-d h:i:s A') ?></dd>
        <dt><?= __('End Time') ?></dt>
        <dd><?= $schedule->end_time->format('Y-m-d h:i:s A') ?></dd>
    </dl>
</div>
<!-- /.modal-body -->
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Close') ?></button>
</div>
<!-- /modal-footer -->