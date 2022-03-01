<?php
use Cake\Routing\Router;
/** @var  $logs \App\Model\Entity\Log[] */
/** @var  $Html \Cake\View\Helper\HtmlHelper*/
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="pull-left"><?= $this->Html->icon('list') . ' ' . __('Rechazos') ?></h1>
    <div class="btn-group pull-right">
        <?= $this->Html->link($this->Html->icon('file-code-o') . ' ' . $this->Html->tag('span', __('Exportar'), ['class' => 'hidden-xs hidden-sm']),
            array_merge(['action' => 'export'], $this->request->getQuery()),
            ['update' => '#page-content-wrapper', 'escape' => false,'target'=>'_blank', 'class' => 'btn btn-sm btn-info', 'title' => __('Exportar')]) ?>
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
                    <?= $this->element('search/rejected') ?>
                    <?= $this->element('paging_links') ?>
                </div>

                <!-- <pre><?php // debug($rejected, true)?></pre> -->
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover table-condensed table-striped table-bordered">
                        <thead>
                            <tr>
                                <th width="30"></th>
                                <th><?= $this->Paginator->sort('type_rejected_id') ?></th>
                                <th><?= $this->Paginator->sort('history_customer_id') ?></th>
                                <th>Usuario</th>
                                <th><?= $this->Paginator->sort('customer_identification') ?></th>
                                <th><?= $this->Paginator->sort('created') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rejected as $register): ?>
                            <tr>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-xs btn-info dropdown-toggle" data-toggle="dropdown">
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu flat">
                                            <li>
                                                <?= $this->Html->link($this->Html->icon('eye').' '.__('View'), ['action' => 'view', $register->id],
                                                    ['data-toggle' => 'modal', 'data-target' => '#viewModal', 'escape' => false]) ?>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <!-- <td><?= h($register->type_rejected_id) ?></td> -->
                                <td><?= $register->has('type_rejected') ? $register->type_rejected->description : '' ?></td>
                                <td><?= h($register->history_customer_id) ?></td>
                                <td><?= $register->user->has('name') ? $register->user->name : $register->user_id ?></td>
                                <td><?= h($register->customer_identification) ?></td>
                                <td><?= h($register->created) ?></td>
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