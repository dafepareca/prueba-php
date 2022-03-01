<?php
/** @var  $trainingResource \App\Model\Entity\Training Resources */
/** @var  $Html \Cake\View\Helper\HtmlHelper*/
?>
<div class="modal-header modal-header-info">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><?= $this->Html->icon('info-circle').' '.__('Training Resource Information') ?></h4>
</div>
<!-- /modal-header -->
<div class="modal-body">
    <dl class="dl-horizontal">
        <dt><?= __('File Dir') ?></dt>
        <dd><?= h($trainingResource->file_dir) ?></dd>
        <dt><?= __('File Size') ?></dt>
        <dd><?= h($trainingResource->file_size) ?></dd>
        <dt><?= __('File Type') ?></dt>
        <dd><?= h($trainingResource->file_type) ?></dd>
        <dt><?= __('Training') ?></dt>
        <dd><?= $trainingResource->has('training') ? $trainingResource->training->name : '' ?></dd>
    </dl>
</div>
<!-- /.modal-body -->
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Close') ?></button>
</div>
<!-- /modal-footer -->