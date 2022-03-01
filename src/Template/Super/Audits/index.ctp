<?php
use Cake\Routing\Router;
/** @var  $audits  \App\Model\Entity\Audit[] */
/** @var  $Html \Cake\View\Helper\HtmlHelper */
/** @var  $AuditLog \App\View\Helper\AuditLogHelper */
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="pull-left"><?= $this->Html->icon('users') . ' ' . __('Audit') ?></h1>
    <div class="btn-group pull-right">
        <?= $this->Html->link($this->Html->icon('file-code-o') . ' ' . $this->Html->tag('span', __('Exportar'), ['class' => 'hidden-xs hidden-sm']),
                array_merge(['action' => 'export'], $this->request->getQuery()),
                ['update' => '#page-content-wrapper', 'escape' => false,'target'=>'_blank', 'class' => 'btn btn-sm btn-info', 'title' => __('Exportar')]) ?>
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
                    <?= $this->element('search/audits'); ?>
                    <?= $this->element('paging_links'); ?>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover table-condensed table-striped table-bordered">
                        <thead>
                        <tr>
                            <th width="30"></th>
                            <th width="120"><?= $this->Paginator->sort('created') ?></th>
                            <th width="50"><?= $this->Paginator->sort('event') ?></th>
                            <th><?= __('User') ?></th>
                            <th width="120"><?= $this->Paginator->sort('model', __('Resource')) ?></th>
                            <th width="60"><?= __('Identifier') ?></th>
                            <th width="60"><?= __('Changes') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        /** @var  $audit \App\Model\Entity\Audit*/
                        foreach ($audits as $audit):
                            $accessLog = $audit->access_log;
                            ?>
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
                                    <?= $this->Html->link($this->Html->icon('eye'), ['action' => 'view', $audit->id],
                                        ['data-toggle' => 'modal', 'data-target' => '#viewModal', 'class' => 'btn btn-xs btn-info', 'title' => __('Details'),  'escape' => false]);
                                    ?>
                                </td>
                                <td><?= $audit->created->format('Y-m-d h:i:s A') ?></td>
                                <td><?= $this->AuditLog->getEvent($audit) ?></td>
                                <td>
                                    <?= $this->Html->link(h($audit->access_log->user->name), ['controller' => $controller,
                                        'action' => 'view', $audit->access_log->user_id],
                                        ['data-toggle' => 'modal', 'data-target' => '#viewModal', 'escape' => false]);
                                    ?>
                                    <br><?= h($audit->access_log->user->email) ?>
                                    <br><?= $this->Html->label(h($audit->access_log->user->role->name)) ?>
                                </td>
                                <td><?= $this->Html->link(
                                        $audit->model,
                                        [
                                            'action' => 'index',
                                            '?' => [
                                                'model' => $audit->model
                                            ]
                                        ],['class' => 'load-ajax', 'update' => '#page-content-wrapper']
                                    ); ?></td>
                                <td class="text-center"><?= $this->Html->link(
                                        $this->AuditLog->getIdentifier($audit),
                                        [
                                            'action' => 'index',
                                            '?' => [
                                                'model' => $audit->model,
                                                'entity_id' => $audit->entity_id
                                            ]
                                        ],['class' => 'load-ajax', 'update' => '#page-content-wrapper']
                                    ); ?></td>
                                <td class="text-center"><?= $this->Number->format($audit->delta_count) ?></td>
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