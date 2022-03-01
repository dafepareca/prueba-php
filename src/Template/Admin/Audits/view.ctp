<?php
/** @var  $audit \App\Model\Entity\Audit */
/** @var  $Html \Cake\View\Helper\HtmlHelper */
/** @var  $AuditLog \App\View\Helper\AuditLogHelper */
?>
<div class="modal-header modal-header-info">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><?= $this->Html->icon('info-circle') . ' ' . __('Audit Detail Information') ?></h4>
</div>
<!-- /modal-header -->
<div class="modal-body">
    <div>
        <dl class="dl-horizontal">
            <dt><?= __('Id'); ?></dt>
            <dd><?= h($audit->id) ?></dd>

            <dt><?= __('Event type'); ?></dt>
            <dd><?= h($audit->event) ?></dd>

            <dt><?= __('Model'); ?></dt>
            <dd><?= h($audit->model) ?></dd>

            <dt><?= __('Model id'); ?></dt>
            <dd><?= h($audit->entity_id) ?></dd>

            <dt><?= __('Description'); ?></dt>
            <dd><?= h($audit->description) ?></dd>

            <dt><?= __('Source Id'); ?></dt>
            <dd><?= h($audit->source_id) ?></dd>

            <dt><?= __('Source Ip'); ?></dt>
            <dd><?= h($audit->source_ip) ?></dd>

            <dt><?= __('Source Url'); ?></dt>
            <dd><?= h($audit->source_url) ?></dd>

            <dt><?= __('Deltas'); ?></dt>
            <dd><?=
                $this->Number->format($audit->delta_count)
                ?></dd>

            <dt><?= __('Created'); ?></dt>
            <dd><?= $audit->created ?></dd>
        </dl>
    </div>
    <?php if (!empty($audit->audit_deltas)): ?>
    <br>
    <div class="box no-border">
        <div class="box-body no-padding" style="max-height:200px; overflow:auto;" >
            <table class="table table-hover table-condensed table-striped">
                <thead>
                <tr>
                    <th width="120"><?= __('Field') ?></th>
                    <th><?= __('Old Value') ?></th>
                    <th><?= __('New Value') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($audit->audit_deltas as $auditDeltas): ?>
                    <tr>
                        <td><?= h($auditDeltas->property_name) ?></td>
                        <td><?= h($auditDeltas->old_value) ?></td>
                        <td><?= h($auditDeltas->new_value) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>
<!-- /.modal-body -->
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Close') ?></button>
</div>
<!-- /modal-footer -->