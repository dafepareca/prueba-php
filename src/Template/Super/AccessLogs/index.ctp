<?php
use Cake\Routing\Router;

/** @var  $accessLogs \App\Model\Entity\AccessLog[] */
/** @var  $Html \Cake\View\Helper\HtmlHelper */
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="pull-left"><?= $this->Html->icon('users') . ' ' . __('Logs Sessions') ?></h1>
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
                    <?= $this->element('search/access_logs'); ?>
                    <?= $this->element('paging_links'); ?>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover table-condensed table-striped">
                        <thead>
                        <tr>
                            <th width="30"></th>
                            <th width="130"><?= $this->Paginator->sort('date_login') ?></th>
                            <th width="130"><?= $this->Paginator->sort('date_logout') ?></th>
                            <th><?= $this->Paginator->sort('User.name', __('User')) ?></th>
                            <th width="80"><?= __('Ip Address') ?></th>
                            <th width="100"><?= __('Logout Type') ?></th>
                            <th><?= __('User Agent') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($accessLogs as $accessLog): ?>
                            <?php
                            if($accessLog->user->role_id == \App\Model\Table\RolesTable::Admin) {
                                $controller = 'admin';
                            }elseif($accessLog->user->role_id == \App\Model\Table\RolesTable::Asesor){
                                $controller = 'asesor';
                            }elseif($accessLog->user->role_id == \App\Model\Table\RolesTable::Coordinador){
                                $controller = 'coordinador';
                            }
                            elseif($accessLog->user->role_id == \App\Model\Table\RolesTable::Super){
                                $controller = 'super';
                            }
                            ?>
                            <tr>
                                <td>
                                    <?= $this->Html->link($this->Html->icon('wpexplorer'), ['action' => 'view', $accessLog->id],
                                        ['data-toggle' => 'modal', 'data-target' => '#viewModal', 'class' => 'btn btn-xs btn-info', 'title' => __('Details'),  'escape' => false]);
                                    ?>
                                </td>
                                <td><?= $accessLog->date_login->format('Y-m-d h:i:s A') ?></td>
                                <td><?= is_null($accessLog->date_logout) ? '' : $accessLog->date_logout->format('Y-m-d h:i:s A') ?></td>
                                <td>
                                    <?= $this->Html->link(h($accessLog->user->name), ['controller' => $controller,  'action' => 'view', $accessLog->user_id],
                                        ['data-toggle' => 'modal', 'data-target' => '#viewModal', 'escape' => false]);
                                    ?>
                                    <br><?= h($accessLog->user->email) ?>
                                    <br><?= $this->Html->label(h($accessLog->user->role->name)) ?>
                                </td>
                                <td><?= h($accessLog->ip) ?></td>
                                <td><?= h($accessLog->logouts_type->name) ?></td>
                                <td><?= h($accessLog->user_agent) ?></td>
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
