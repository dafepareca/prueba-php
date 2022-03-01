<?php
use Cake\Routing\Router;
/** @var  $committees \App\Model\Entity\Committee[] */
/** @var  $Html \Cake\View\Helper\HtmlHelper*/
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="pull-left"><?= $this->Html->icon('list') . ' ' . __('Committees') ?></h1>
    <div class="btn-group pull-right">
        <?= $this->Html->link($this->Html->icon('refresh') . ' ' . $this->Html->tag('span', __('Refresh'), ['class' => 'hidden-xs hidden-sm']),
            ['action' => 'index'],
            ['id' => 'refresh', 'update' => '#page-content-wrapper', 'escape' => false, 'class' => 'load-ajax btn btn-sm btn-default', 'title' => __('Refresh')]) ?>
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
                    <?= $this->element('paging_links') ?>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover table-condensed table-striped table-bordered">
                        <thead>
                            <tr>
                                <th width="30"></th>
                                <th><?=__('Numero identificación') ?></th>
                                <th><?= __('Name') ?></th>
                                <th><?= __('State') ?></th>
                                <th><?= __('Fecha') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($committees as $committee): ?>
                            <tr>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-xs btn-info dropdown-toggle" data-toggle="dropdown">
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu flat">
                                            <li>
                                                <?= $this->Html->link($this->Html->icon('eye').' '.__('View'), ['action' => 'view', $committee->id],
                                                ['data-toggle' => 'modal', 'data-target' => '#viewModalDetail', 'escape' => false]) ?>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td><?= $committee->has('history_customer') ? $committee->history_customer->customer_id : '' ?></td>
                                <td><?= $committee->has('history_customer') ? $committee->history_customer->customer_name : '' ?></td>
                                <td><?= $committee->history_customer->history_status->name ?></td>
                                <td><?= $committee->has('history_customer') ? $committee->created->format('Y-m-d H:m:s') : '' ?></td>
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


<div class="example-modal">
    <div class="modal modal-danger" id="viewModalDetail">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">



            </div>
        </div>
    </div>
</div>