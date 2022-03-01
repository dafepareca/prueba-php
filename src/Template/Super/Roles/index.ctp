<?php
use Cake\Routing\Router;

/** @var  $roles \App\Model\Entity\Role[] */
/** @var  $Html \Cake\View\Helper\HtmlHelper */
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="pull-left"><?= $this->Html->icon('list') . ' ' . __('Roles') ?></h1>
    <div class="btn-group pull-right">
        <?php
        echo $this->Html->link($this->Html->icon('plus') . ' ' . $this->Html->tag('span', __('Add'), ['class' => 'hidden-xs hidden-sm']),
            ['action' => 'add'],
            ['update' => '#page-content-wrapper', 'escape' => false, 'class' => 'load-ajax btn btn-sm btn-default', 'title' => __('Add')]);
        echo $this->Html->link($this->Html->icon('refresh') . ' ' . $this->Html->tag('span', __('Refresh'), ['class' => 'hidden-xs hidden-sm']),
            ['action' => 'index'],
            ['update' => '#page-content-wrapper', 'escape' => false, 'class' => 'load-ajax btn btn-sm btn-default', 'title' => __('Refresh')]);
        ?>
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
                            <th><?= $this->Paginator->sort('prefix') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($roles as $role): ?>
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
                                                echo $this->Html->link($this->Html->icon('edit') . ' ' . __('Edit'), ['action' => 'edit', $role->id],
                                                    ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]);
                                                ?>
                                            </li>
                                            <li>
                                                <?php
                                                echo $this->Html->link($this->Html->icon('eye') . ' ' . __('View'), ['action' => 'view', $role->id],
                                                    ['data-toggle' => 'modal', 'data-target' => '#viewModal', 'escape' => false]);
                                                ?>
                                            </li>
                                            <li>
                                                <?php
                                                echo $this->Html->link($this->Html->icon('trash') . ' ' . __('Delete'),
                                                    ['action' => '#'],
                                                    ['escape' => false,
                                                        'class' => 'btn-confirm',
                                                        'data-id' => $role->id,
                                                        'data-toggle' => 'modal',
                                                        'data-target' => '#ConfirmDelete',
                                                        'data-action' => Router::url(['action' => 'delete', $role->id], false),]
                                                );
                                                ?>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td><?= h($role->name) ?></td>
                                <td><?= h($role->prefix) ?></td>
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