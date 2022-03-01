<?php
use Cake\Routing\Router;

/** @var  $accessActivities \App\Model\Entity\AccessActivity[] */
/** @var  $Html \Cake\View\Helper\HtmlHelper */
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="pull-left"><?= $this->Html->icon('users') . ' ' . __('Access Activities') ?></h1>
    <div class="btn-group pull-right">
        <?= $this->Html->link($this->Html->icon('refresh') . ' ' . $this->Html->tag('span', __('Refresh'), ['class' => 'hidden-xs hidden-sm']),
            ['action' => 'index'],
            ['update' => '#page-content-wrapper', 'escape' => false, 'class' => 'load-ajax btn btn-sm btn-default', 'title' => __('Refresh')]);
        ?>
    </div>
    <div class="clearfix"></div>
</section>
<!-- Main content -->
<?= $this->element('paging_options'); ?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <?= $this->element('search/access_activities'); ?>
                    <?= $this->element('paging_links'); ?>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover table-condensed table-striped">
                        <thead>
                        <tr>
                            <th width="120"><?= $this->Paginator->sort('date') ?></th>
                            <th><?= __('User') ?></th>
                            <th width="120"><?= __('Model') ?></th>
                            <th width="100"><?= $this->Paginator->sort('type_activity_id', 'Activity Type') ?></th>
                            <th><?= __('Description') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($accessActivities as $accessActivity): ?>
                            <?php
                            if($accessActivity->access_log->user->role_id == \App\Model\Table\RolesTable::Admin) {
                                $controller = 'users';
                            }elseif($accessActivity->access_log->user->role_id == \App\Model\Table\RolesTable::Manager){
                                $controller = 'unitManagers';
                            }elseif($accessActivity->access_log->user->role_id == \App\Model\Table\RolesTable::Internal){
                                $controller = 'internalCustomers';
                            }elseif($accessActivity->access_log->user->role_id == \App\Model\Table\RolesTable::External){
                                $controller = 'externalCustomers';
                            }elseif($accessActivity->access_log->user->role_id == \App\Model\Table\RolesTable::Bi){
                                $controller = 'biUsers';
                            }
                            ?>
                            <tr>
                                <td><?= $accessActivity->date->format('Y-m-d h:i:s A') ?></td>
                                <td>
                                    <?= $this->Html->link(h($accessActivity->access_log->user->name), ['controller' => $controller,  'action' => 'view', $accessActivity->access_log->user_id],
                                        ['data-toggle' => 'modal', 'data-target' => '#viewModal', 'escape' => false]);
                                    ?>
                                    <br><?= h($accessActivity->access_log->user->email) ?>
                                    <br><?= $this->Html->label(h($accessActivity->access_log->user->role->name)) ?>
                                </td>
                                <td><?= h($accessActivity->model) ?></td>
                                <td><?= h($accessActivity->access_types_activity->type) ?></td>
                                <td><?= h($accessActivity->description) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix">
                    <?= $this->element('paging_counter') ?>
                </div>
            </div>
            <!-- /.box -->
        </div>
    </div>
</section>
<!-- /.content -->
