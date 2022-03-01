<?php
/** @var  $ticket \App\Model\Entity\Tickets */
/** @var  $Html \Cake\View\Helper\HtmlHelper*/
/** @var \App\View\AppView $this */
/** @var  $ticket \App\Model\Entity\Ticket*/
?>
<div class="modal-header modal-header-info">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><?= $this->Html->icon('info-circle').' '.__('Ticket Information') ?></h4>
</div>
<!-- /modal-header -->
<div class="modal-body">
    <dl class="dl-horizontal">
        <dt><?= __('Title') ?></dt>
        <dd><?= h($ticket->title) ?></dd>
        <dt><?= __('User') ?></dt>
        <dd><?= $ticket->has('user') ? $ticket->user->name : '' ?></dd>
        <dt><?= __('Tickets Status') ?></dt>
        <dd><?= $ticket->has('tickets_status') ? $ticket->tickets_status->state : '' ?></dd>
        <dt><?= __('Solved By') ?></dt>
        <dd><?= $ticket->has('solved') ? $ticket->solved->name : '' ?></dd>
        <dt><?= __('Approved By') ?></dt>
        <dd><?= $ticket->has('approved') ? $ticket->approved->name : '' ?></dd>
        <dt><?= __('Priority') ?></dt>
        <dd><?= $ticket->priorities[$ticket->priority] ?></dd>
        <dt><?= __('Description') ?></dt>
        <dd><?= $this->Text->autoParagraph(h($ticket->description)) ?></dd>
        <dt><?= __('Resolved Detail') ?></dt>
        <dd><?= $this->Text->autoParagraph(h($ticket->resolved_detail)) ?></dd>
        <dt><?= __('Modified') ?></dt>
        <dd><?= $ticket->modified->format('Y-m-d h:i:s A') ?></dd>
        <dt><?= __('Created') ?></dt>
        <dd><?= $ticket->created->format('Y-m-d h:i:s A') ?></dd>
    </dl>
    <h4>Recursos</h4>
        <?php foreach ($ticket->tickets_resources as $resource): ?>
           <a href="<?='img/'.$resource->file_dir.$resource->img; ?>" target="_blank">
                <?= $this->Nested->_thumb('img', $resource, null, ['id' => 'avatar', 'class' => 'img-thumbnail', 'style' => 'width:150px; margin:6px']) ?>
           </a>
        <?php endforeach; ?>
    </div>
</div>
<!-- /.modal-body -->
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Close') ?></button>
</div>
<!-- /modal-footer -->