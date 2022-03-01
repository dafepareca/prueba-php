<?php
use Cake\Routing\Router;
/** @var  $charges \App\Model\Entity\Charge[] */
/** @var  $Html \Cake\View\Helper\HtmlHelper*/
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="pull-left"><?= $this->Html->icon('list') . ' ' . __('Charges') ?></h1>
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
                    <?= $this->element('search/charges') ?>
                    <?= $this->element('paging_links') ?>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover table-condensed table-striped table-bordered">
                        <thead>
                            <tr>
                                <!--<th><?= 'state' ?></th>-->
                                <th><?= $this->Paginator->sort('name_file') ?></th>
                               <!-- <th><?= $this->Paginator->sort('records_obligation') ?></th>
                                <th><?= $this->Paginator->sort('failed_obligation') ?></th>
                                <th><?= $this->Paginator->sort('records_customer') ?></th>
                                <th><?= $this->Paginator->sort('failed_customer') ?></th>-->
                                <th><?= $this->Paginator->sort('Processed') ?></th>
                                <th><?= $this->Paginator->sort('Created') ?></th>
                                <th><?= $this->Paginator->sort('Modified') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($charges as $charge): ?>
                            <tr>
                                <!--<td><?= $charge->estados[$charge->state] ?></td>-->
                                <td><?= h($charge->name_file) ?></td>
                                <!--<td><?= $this->Number->format($charge->records_obligation) ?></td>
                                <td><?= $this->Number->format($charge->failed_obligation) ?></td>
                                <td><?= $this->Number->format($charge->records_customer) ?></td>
                                <td><?= $this->Number->format($charge->failed_customer) ?></td>-->
                                <td><?= ($charge->processed)?'Si':'No'?></td>
                                <td><?= $charge->created->format('Y-m-d H:i:s') ?></td>
                                <td><?= $charge->modified ?></td>
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