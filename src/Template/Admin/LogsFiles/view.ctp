<?php
/** @var  $logsFile \App\Model\Entity\Logs Files */
/** @var  $Html \Cake\View\Helper\HtmlHelper*/
?>
<div class="modal-header modal-header-info">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><?= $this->Html->icon('info-circle').' '.__('Logs File Information') ?></h4>
</div>
<!-- /modal-header -->
<div class="modal-body">
    <dl class="dl-horizontal">
        <dt><?= __('Name File') ?></dt>
        <dd><?= h($logsFile->name_file) ?></dd>
        <dt><?= __('File Dir') ?></dt>
        <dd><?= h($logsFile->file_dir) ?></dd>
        <dt><?= __('Created') ?></dt>
        <dd><?= $logsFile->created->format('Y-m-d h:i:s A') ?></dd>
    </dl>
</div>
<!-- /.modal-body -->
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Close') ?></button>
</div>
<!-- /modal-footer -->