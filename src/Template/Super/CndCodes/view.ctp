<?php
/** @var  $cndCode \App\Model\Entity\Cnd Codes */
/** @var  $Html \Cake\View\Helper\HtmlHelper*/
?>
<div class="modal-header modal-header-info">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><?= $this->Html->icon('info-circle').' '.__('Cnd Code Information') ?></h4>
</div>
<!-- /modal-header -->
<div class="modal-body">
    <dl class="dl-horizontal">
        <dt><?= __('Code') ?></dt>
        <dd><?= $this->Number->format($cndCode->code) ?></dd>
        <dt><?= __('Not Negotiate') ?></dt>
        <dd><?= $this->Number->format($cndCode->not_negotiate) ?></dd>
        <dt><?= __('Message') ?></dt>
        <dd><?= $this->Text->autoParagraph(h($cndCode->message)) ?></dd>
    </dl>
</div>
<!-- /.modal-body -->
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Close') ?></button>
</div>
<!-- /modal-footer -->