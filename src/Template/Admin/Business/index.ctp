<?php
use Cake\Routing\Router;
/** @var  $business \App\Model\Entity\Busines[] */
/** @var  $Html \Cake\View\Helper\HtmlHelper*/
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="pull-left"><?= $this->Html->icon('list') . ' ' . __('Business') ?></h1>
    <div class="btn-group pull-right">
        <?= $this->Html->link($this->Html->icon('users') . ' ' . $this->Html->tag('span', __('Todos los Usuarios'), ['class' => 'hidden-xs hidden-sm']),
            ['controller' => 'users', 'action' => 'index'],
            ['update' => '#page-content-wrapper', 'escape' => false, 'class' => 'load-ajax btn btn-sm btn-info', 'title' => __('Refresh')]) ?>

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
                    <table class="table table-hover table-condensed table-striped table-bordered">
                        <thead>
                            <tr>
                                <th width="30"></th>
                                <th><?= $this->Paginator->sort('name') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($business as $busines): ?>
                            <tr>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-xs btn-info dropdown-toggle" data-toggle="dropdown">
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu flat">
                                            <li>
                                                <?= $this->Html->link($this->Html->icon('eye').' '.__('View'), ['action' => 'view', $busines->id],
                                                ['data-toggle' => 'modal', 'data-target' => '#viewModal', 'escape' => false]) ?>
                                            </li>
                                            <li class="divider"></li>
                                            <li>
                                                <?=$this->Html->link($this->Html->icon('users') . ' ' . __('Coordinadores'),
                                                    ['controller' => 'coordinador', 'action' => 'index', 'busines_id' => $busines->id],
                                                    ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]);
                                                ?>
                                            </li>
                                            <li>
                                                <?=$this->Html->link($this->Html->icon('users') . ' ' . __('Asesores'),
                                                    ['controller' => 'asesor', 'action' => 'index', 'busines_id' => $busines->id],
                                                    ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]);
                                                ?>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td><?= h($busines->name) ?></td>
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