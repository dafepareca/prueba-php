<?php
use Cake\Routing\Router;

/** @var  $accessGroups \App\Model\Entity\AccessGroup[] */
/** @var  $Html \Cake\View\Helper\HtmlHelper */
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="pull-left"><?= $this->Html->icon('ban') . ' ' . __('Access Groups') ?></h1>
    <div class="btn-group pull-right">
        <?= $this->Html->link($this->Html->icon('plus') . ' ' . $this->Html->tag('span', __('Add'), ['class' => 'hidden-xs hidden-sm']),
            ['action' => 'add'],
            ['update' => '#page-content-wrapper', 'escape' => false, 'class' => 'load-ajax btn btn-sm btn-default', 'title' => __('Add')]) ?>
        <?= $this->Html->link($this->Html->icon('refresh') . ' ' . $this->Html->tag('span', __('Refresh'), ['class' => 'hidden-xs hidden-sm']),
            ['action' => 'index'],
            ['update' => '#page-content-wrapper', 'escape' => false, 'class' => 'load-ajax btn btn-sm btn-default', 'title' => __('Refresh')]) ?>
    </div>
    <div class="clearfix"></div>
</section>

<!-- Main content -->
<?= $this->element('paging_options') ?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <?= $this->element('search/generic') ?>
                    <?= $this->element('paging_links') ?>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover table-condensed table-striped">
                        <thead>
                        <tr>
                            <th width="30"></th>
                            <th><?= $this->Paginator->sort('name') ?></th>
                            <th><?= __('Description') ?></th>
                            <th width="100"><?= $this->Paginator->sort('all_ips') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($accessGroups as $accessGroup): ?>
                            <tr>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-xs btn-info dropdown-toggle"
                                                data-toggle="dropdown">
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu flat">
                                            <li>
                                                <?php
                                                echo $this->Html->link($this->Html->icon('edit') . ' ' . __('Edit'), ['action' => 'edit', $accessGroup->id],
                                                    ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]);
                                                ?>
                                            </li>
                                            <li>
                                                <?php
                                                echo $this->Html->link($this->Html->icon('eye') . ' ' . __('View'), ['action' => 'view', $accessGroup->id],
                                                    ['data-toggle' => 'modal', 'data-target' => '#viewModal', 'escape' => false]);
                                                ?>
                                            </li>
                                            <li>
                                                <?php
                                                echo $this->Html->link($this->Html->icon('trash') . ' ' . __('Delete'),
                                                    ['action' => '#'],
                                                    ['escape' => false,
                                                        'class' => 'btn-confirm',
                                                        'data-id' => $accessGroup->id,
                                                        'data-toggle' => 'modal',
                                                        'data-target' => '#ConfirmDelete',
                                                        'data-action' => Router::url(['action' => 'delete', $accessGroup->id], false),]
                                                );
                                                ?>
                                            </li>
                                            <?php if ($accessGroup->all_ips == 0): ?>
                                                <li class="divider"></li>
                                                <li>
                                                    <?php
                                                    echo $this->Html->link($this->Html->icon('list') . ' ' . __('Ip Address'),
                                                        ['controller' => 'IpsGroups', 'action' => 'index', 'access_group_id' => $accessGroup->id],
                                                        ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]);
                                                    ?>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </td>
                                <td><?= h($accessGroup->name) ?></td>
                                <td><?= h($accessGroup->description) ?></td>
                                <td><?= $accessGroup->all_ips ? __('Yes') : __('No'); ?></td>
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