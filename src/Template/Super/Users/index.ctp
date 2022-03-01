<?php
use Cake\Routing\Router;

/** @var  $users \App\Model\Entity\User[] */
/** @var  $Html \Cake\View\Helper\HtmlHelper */
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="pull-left"><?= $this->Html->icon('users') . ' ' . __('Users') ?></h1>
    <div class="btn-group pull-right" role="group">
        <?= $this->Html->link($this->Html->icon('plus') . ' ' . $this->Html->tag('span', __('Add'), ['class' => 'hidden-xs hidden-sm']),
            '#', [ 'escape' => false, 'class' => 'btn btn-sm btn-info', 'data-toggle' => 'dropdown', 'title' => __('Add')]) ?>
        <ul class="dropdown-menu dropdown-menu-left " role="menu">
            <li>
                <?= $this->Html->link($this->Html->icon('user') . ' ' . __('Super'),
                    ['action' => 'add'], ['update' => '#page-content-wrapper', 'escape' => false, 'class' => 'load-ajax']) ?>
            </li>
            <li>
                <?= $this->Html->link($this->Html->icon('user') . ' ' . __('Administrator'),
                    ['controller' => 'admins', 'action' => 'add'], ['update' => '#page-content-wrapper', 'escape' => false, 'class' => 'load-ajax']) ?>
            </li>
        </ul>
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
                    <?= $this->element('search/users'); ?>
                    <?= $this->element('paging_links'); ?>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover table-condensed">
                        <thead>
                        <tr>
<!--                            <th width="30"></th>-->
                            <th width="60"></th>
                            <th><?= $this->Paginator->sort('name') ?></th>
                            <th><?= $this->Paginator->sort('email') ?></th>
                            <th width="70"><?= __('Mobile') ?></th>
                            <th><?= $this->Paginator->sort('role_id') ?></th>
                            <th width="70"><?= __('Status') ?></th>
                            <th width="100"><?= __('Access Group') ?></th>
                            <th width="100"><?= __('Employer') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($users as $user): ?>
                            <?php
                            if($user->role_id == \App\Model\Table\RolesTable::Admin) {
                                $controller = 'admins';
                            }elseif($user->role_id == \App\Model\Table\RolesTable::Asesor){
                                $controller = 'asesor';
                            }
                            elseif($user->role_id == \App\Model\Table\RolesTable::Coordinador){
                                $controller = 'coordinador';
                            }
                            ?>
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
                                                <?= $this->Html->link($this->Html->icon('edit') . ' ' . __('Edit'), ['controller' => $controller, 'action' => 'edit', $user->id],
                                                    ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]);
                                                ?>
                                            </li>
                                            <?php endif; ?>
                                            <li>
                                                <?= $this->Html->link($this->Html->icon('eye') . ' ' . __('View'), ['controller' => $controller, 'action' => 'view', $user->id],
                                                    ['data-toggle' => 'modal', 'data-target' => '#viewModal', 'escape' => false]);
                                                ?>
                                            </li>
                                            <?php if(is_null($user->last_login)): ?>
                                            <li>
                                                <?= $this->Html->link($this->Html->icon('trash') . ' ' . __('Delete'),
                                                    ['action' => '#'],
                                                    ['escape' => false,
                                                        'class' => 'btn-confirm',
                                                        'data-id' => $user->id,
                                                        'data-toggle' => 'modal',
                                                        'data-target' => '#ConfirmDelete',
                                                        'data-action' => Router::url(['controller' => $controller, 'action' => 'delete', $user->id], false),]
                                                );
                                                ?>
                                            </li>
                                                <li class="divider"></li>
                                            <?php elseif($user->user_status_id <> \App\Model\Table\UserStatusesTable::Archived): ?>
                                            <li>
                                                <?= $this->Html->link($this->Html->icon('folder') . ' ' . __('Archive'),
                                                    ['action' => '#'],
                                                    ['escape' => false,
                                                        'class' => 'btn-confirm',
                                                        'data-id' => $user->id,
                                                        'data-toggle' => 'modal',
                                                        'data-target' => '#ConfirmArchive',
                                                        'data-action' => Router::url(['controller' => $controller, 'action' => 'archive', $user->id], false),]
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
                                                                'controller' => 'users',
                                                                'action' => 'reset_password',
                                                                $user->id
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
                                <td><?= h($user->email) ?></td>
                                <td><?= h($user->mobile) ?></td>
                                <td><?= h($user->role->name) ?></td>
                                <td align="center"><?= $this->Html->label($user->user_status->name, $user->user_status->label) ?> </td>
                                <td><?= h($user->access_group->name) ?></td>
                                <td><?= h($user->busines->name) ?></td>
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