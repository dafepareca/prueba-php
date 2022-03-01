<?php
use Cake\Routing\Router;

/** @var  $users \App\Model\Entity\User[] */
/** @var  $Html \Cake\View\Helper\HtmlHelper */
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h3 class="pull-left"><?= $this->Html->icon('users') . ' ' . __('Perfil').' ' ?>|
        <small><?= __('Asesor').' - '.$business->name ?></small></h3>
    <div class="btn-group pull-right" role="group">
        <?= $this->Html->link($this->Html->icon('chevron-left') . ' ' . $this->Html->tag('span', __('Back'), ['class' => 'hidden-xs hidden-sm']),
            ['controller' => 'business','action' => 'index'], ['escape' => false, 'class' => 'load-ajax btn btn-sm btn-default', 'update' => '#page-content-wrapper', 'title' => __('Back')]) ?>
        <?= $this->Html->link($this->Html->icon('plus') . ' ' . $this->Html->tag('span', __('Add'), ['class' => 'hidden-xs hidden-sm']),
            ['controller' => 'asesor','action' => 'add','busines_id' => $business->id], ['update' => '#page-content-wrapper', 'escape' => false, 'class' => 'load-ajax btn btn-sm btn-info', 'title' => __('Add')]) ?>
        <?= $this->Html->link($this->Html->icon('refresh') . ' ' . $this->Html->tag('span', __('Refresh'), ['class' => 'hidden-xs hidden-sm']),
            ['action' => 'index','busines_id' => $business->id],
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
                    <?= $this->element('search/users'); ?>
                    <?= $this->element('paging_links'); ?>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover table-condensed">
                        <thead>
                        <tr>
                            <th width="30"></th>
                            <th><?= $this->Paginator->sort('name') ?></th>
                            <th><?= __('Mobile') ?></th>
                            <th><?= $this->Paginator->sort('identification') ?></th>
                            <th><?= __('Status') ?></th>
                            <th><?= __('Access Group') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($users as $user): ?>
                            <?php
                            if($user->role_id == \App\Model\Table\RolesTable::Admin) {
                                $controller = 'admin';
                            }elseif($user->role_id == \App\Model\Table\RolesTable::Asesor){
                                $controller = 'asesor';
                            }elseif($user->role_id == \App\Model\Table\RolesTable::Coordinador){
                                $controller = 'coordinador';
                            }
                            ?>
                        <?php if($user->user_status_id <> \App\Model\Table\UserStatusesTable::Archived): ?>
                            <tr class="<?= $user->user_status->label; ?>">
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-xs btn-info dropdown-toggle"
                                                data-toggle="dropdown">
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu flat">
                                            <?php if($user->user_status_id <> \App\Model\Table\UserStatusesTable::Archived): ?>
                                            <li>
                                                <?= $this->Html->link($this->Html->icon('edit') . ' ' . __('Edit'),
                                                    ['controller' => $controller, 'action' => 'edit', $user->id,'busines_id' => $business->id],
                                                    ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]);
                                                ?>
                                            </li>
                                            <?php endif; ?>
                                            <li>
                                                <?= $this->Html->link($this->Html->icon('eye') . ' ' . __('View'),
                                                    ['controller' => $controller, 'action' => 'view', $user->id,'busines_id' => $business->id],
                                                    ['data-toggle' => 'modal', 'data-target' => '#viewModal', 'escape' => false]);
                                                ?>
                                            </li>
                                            <li class="divider"></li>
                                            <?php if(is_null($user->last_login)): ?>
                                            <li>
                                                <?= $this->Html->link($this->Html->icon('trash') . ' ' . __('Delete'),
                                                    ['action' => '#'],
                                                    ['escape' => false,
                                                        'class' => 'btn-confirm',
                                                        'data-id' => $user->id,
                                                        'data-toggle' => 'modal',
                                                        'data-target' => '#ConfirmDelete',
                                                        'data-action' => Router::url(
                                                            [
                                                                'controller' => $controller,
                                                                'action' => 'delete',
                                                                $user->id,
                                                                'busines_id' => $business->id
                                                            ], false
                                                        ),
                                                    ]
                                                );
                                                ?>
                                            </li>
                                            <?php elseif($user->user_status_id <> \App\Model\Table\UserStatusesTable::Archived): ?>
                                            <li>
                                                <?= $this->Html->link($this->Html->icon('folder') . ' ' . __('Archive'),
                                                    ['action' => '#'],
                                                    ['escape' => false,
                                                        'class' => 'btn-confirm',
                                                        'data-id' => $user->id,
                                                        'data-toggle' => 'modal',
                                                        'data-target' => '#ConfirmArchive',
                                                        'data-action' => Router::url(
                                                            [
                                                                'controller' => $controller,
                                                                'action' => 'archive',
                                                                $user->id,
                                                                'busines_id' => $business->id
                                                            ], false),
                                                    ]
                                                );
                                                ?>
                                            </li>
                                            <?php endif; ?>
                                            <li>
                                                <?= $this->Html->link($this->Html->icon('key') . ' ' . __('Reset password'),
                                                    ['action' => '#'],
                                                    ['escape' => false,
                                                        'class' => 'btn-confirm reset_password',
                                                        'data-id' => $user->id,
                                                        'data-name' => $user->name,
                                                        'data-action' => Router::url(
                                                            [
                                                                'controller' => $controller,
                                                                'action' => 'reset_password',
                                                                $user->id,
                                                                'busines_id' => $business->id
                                                            ], false
                                                        ),
                                                    ]
                                                );
                                                ?>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td><?= h($user->name) ?></td>
                                <td><?= h($user->mobile) ?></td>
                                <td><?= h($user->identification) ?></td>
                                <td><?= $this->Html->label($user->user_status->name, $user->user_status->label) ?> </td>
                                <td><?= h($user->access_group->name) ?></td>
                            </tr>
                            <?php endIf; ?>
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