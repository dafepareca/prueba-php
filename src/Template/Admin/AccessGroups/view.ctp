<?php
/** @var  $accessGroup \App\Model\Entity\AccessGroup */
/** @var  $Html \Cake\View\Helper\HtmlHelper */
?>
<div class="modal-header modal-header-info">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><?= $this->Html->icon('info-circle') . ' ' . __('Access Group Information') ?></h4>
</div>
<!-- /modal-header -->
<div class="modal-body">
    <dl class="dl-horizontal">
        <dt><?= __('Name') ?></dt>
        <dd><?= h($accessGroup->name) ?></dd>
        <dt><?= __('All Ips') ?></dt>
        <dd><?= $accessGroup->all_ips ? __('Yes') : __('No') ?></dd>
        <dt><?= __('Description') ?></dt>
        <dd><?= $this->Text->autoParagraph(h($accessGroup->description)) ?></dd>
        <?php if ($accessGroup->all_ips == 0): ?>
            <dt><?= __('Ip Address') ?></dt>
            <dd>
                <?php foreach ($accessGroup->ips_groups as $ips): ?>
                    <?= h($ips->name) . ': ' . $ips->ip_address . '<br>' ?>
                <?php endforeach; ?>
            </dd>
        <?php endif; ?>
        <dt><?= __('Created') ?></dt>
        <dd><?= $accessGroup->created->format('Y-m-d h:i:s A') ?></dd>
        <dt><?= __('Modified') ?></dt>
        <dd><?= $accessGroup->modified->format('Y-m-d h:i:s A') ?></dd>
    </dl>
</div>
<!-- /.modal-body -->
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Close') ?></button>
</div>
<!-- /modal-footer -->