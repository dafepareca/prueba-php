<?php
/** @var  $accessLog \App\Model\Entity\AccessLog */
/** @var  $Html \Cake\View\Helper\HtmlHelper */
?>
<div class="modal-header modal-header-default">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><?= $this->Html->icon('info-circle') . ' ' . __('Access Log Information') ?></h4>
</div>
<!-- /modal-header -->
<div class="modal-body">
    <div class="box box-widget widget-user-2" style="margin-bottom: 0">
        <!-- Add the bg color to the header using any of the bg-* classes -->
        <div class="widget-user-header bg-gray">
            <div class="widget-user-image">
                <?= $this->Nested->_thumb('photo', $accessLog->user->attachment, 'large', ['id' => 'avatar', 'class' => 'img-thumbnail']) ?>
            </div>
            <!-- /.widget-user-image -->
            <h3 class="widget-user-username"><?= h($accessLog->user->name) ?></h3>
            <h5 class="widget-user-desc"><?= h($accessLog->user->role->name) ?> <small><?= h($accessLog->user->email) ?></small></h5>
            <div class="clearfix"></div>
        </div>
        <div class="box-footer no-padding">
            <ul class="nav nav-stacked">
                <li><a href="#"><?= $this->Html->icon('clock-o').' '.__('Date Login') ?>
                    <span class="pull-right text-info"><?= $accessLog->date_login->format('Y-m-d h:i:s A') ?></span></a></li>
                <li><a href="#"><?= $this->Html->icon('clock-o').' '.__('Date Logout') ?>
                        <span class="pull-right text-info"><?= is_null($accessLog->date_logout) ? '' : $accessLog->date_logout->format('Y-m-d h:i:s A') ?></span></a></li>

                <li><a href="#"><?= $this->Html->icon('ban').' '.__('Ip Address') ?>
                        <span class="pull-right text-info"><?= $accessLog->ip ?></span></a></li>

            </ul>
        </div>
    </div>
    <?php if($accessLog->access_activity_log_count > 0):?>
    <br>
    <div class="box no-border">
        <div class="box-body no-padding" style="max-height:200px; overflow:auto;" >
            <table class="table table-hover table-condensed table-striped">
                <thead>
                    <tr>
                        <th width="120"><?= __('Date') ?></th>
                        <th><?= __('Model') ?></th>
                        <th><?= __('Resource') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($accessLog->audits as $audit):?>
                    <tr>
                        <td><?= $audit->created->format('Y-m-d h:i:s A') ?></td>
                        <td><?= h($audit->model) ?></td>
                        <td><?= h($audit->event) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="box-footer text-right">
            <?= __('Activities by session').': '.$accessLog->access_activity_log_count ?>
        </div>
    </div>
    <?php endif; ?>
</div>
<!-- /.modal-body -->
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Close') ?></button>
</div>
<!-- /modal-footer -->