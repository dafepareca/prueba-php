<?php
use Cake\Routing\Router;
/** @var  $adjustedObligations \App\Model\Entity\Adjusted Obligation[] */
/** @var  $Html \Cake\View\Helper\HtmlHelper*/
?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h3 class="pull-left"><?= $this->Html->icon('list') . ' ' . __('Obligaciones modificadas') ?></h3>
    <div class="btn-group pull-right">
        <?= $this->Html->link($this->Html->icon('chevron-left') . ' ' . $this->Html->tag('span', __('Back'), ['class' => 'hidden-xs hidden-sm']),
            ['controller' => 'pages','action' => 'reports'], ['escape' => false, 'class' => 'load-ajax btn btn-sm btn-default', 'update' => '#page-content-wrapper', 'title' => __('Back')]) ?>
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
                    <?= $this->element('search/negociaciones') ?>
                    <?= $this->element('paging_links') ?>
                </div>
                <div class="clearfix"></div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover table-condensed">
                        <thead>
                            <tr>
                                <th width="30"></th>
                                <th><?= $this->Paginator->sort('type_identification') ?></th>
                                <th><?= $this->Paginator->sort('identification') ?></th>
                                <th><?= $this->Paginator->sort('customer_name') ?></th>
                                <th><?= $this->Paginator->sort('user_dataweb') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($adjustedObligations as $adjustedObligation): ?>
                            <tr>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-xs btn-info dropdown-toggle" data-toggle="dropdown">
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu flat">
                                            <li>
                                                <?= $this->Html->link($this->Html->icon('eye').' '.__('View'), ['action' => 'view', $adjustedObligation->id],
                                                ['data-toggle' => 'modal', 'data-target' => '#viewModal', 'escape' => false]) ?>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td><?= $adjustedObligation->type_identification ?></td>
                                <td><?= $adjustedObligation->identification ?></td>
                                <td><?= $adjustedObligation->customer_name ?></td>
                                <td><?= $adjustedObligation->user_dataweb ?></td>
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