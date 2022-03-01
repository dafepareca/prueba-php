<?php
/** @var  $accessLogs \App\Model\Entity\AccessLog[]  */
/** @var  $Html \Cake\View\Helper\HtmlHelper */
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1><?= __('Control panel') ?></h1>
</section>
<?php //pr($current_user);?>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= __('Latest Logins') ?></h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                    <ul class="users-list clearfix">
                        <?php foreach ($accessLogs as $accessLog): ?>
                            <?php
                            $controller = '';
                            if($accessLog->user->role_id == \App\Model\Table\RolesTable::Admin) {
                                $controller = 'users';
                            }elseif($accessLog->user->role_id == \App\Model\Table\RolesTable::Manager){
                                $controller = 'unitManagers';
                            }elseif($accessLog->user->role_id == \App\Model\Table\RolesTable::Internal){
                                $controller = 'internalCustomers';
                            }elseif($accessLog->user->role_id == \App\Model\Table\RolesTable::External){
                                $controller = 'externalCustomers';
                            }elseif($accessLog->user->role_id == \App\Model\Table\RolesTable::Bi){
                                $controller = 'biUsers';
                            }
                            ?>
                        <li>
                            <?= $this->Nested->_thumb('photo', $accessLog->user->attachment, 'small') ?>
                            <?= $this->Html->link(h($accessLog->user->name), ['controller' => $controller,  'action' => 'view', $accessLog->user_id],
                                ['class' => 'users-list-name', 'data-toggle' => 'modal', 'data-target' => '#viewModal', 'escape' => false]) ?>
                            <span class="users-list-date"><?= $this->Time->timeAgoInWords($accessLog['date_login']) ?></span>
                            <span class="users-list-date"><?= h($accessLog->logouts_type->name)?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <!-- /.users-list -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer text-center">
                    <?= $this->Html->link( __('View All Sessions'),
                        ['controller' => 'access_logs'],
                        ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
                </div>
                <!-- /.box-footer -->
            </div>
        </div>
    </div>
</section>