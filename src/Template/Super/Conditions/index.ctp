<?php
use Cake\Routing\Router;
/** @var  $conditions \App\Model\Entity\Condition[] */
/** @var  $Html \Cake\View\Helper\HtmlHelper*/
/** @var  $typeCondition \App\Model\Entity\TypesCondition*/
/** @var  $condition \App\Model\Entity\Condition*/
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="pull-left"><?= $this->Html->icon('list') . ' ' . __('Conditions') ?> | <?=$typeCondition->type; ?> | <?=(!empty($condition))?$condition->types_condition->label_value.' '.$condition->value:''?> </h1>
    <div class="btn-group pull-right">
        <?= (!empty($condition))?$this->Html->link($this->Html->icon('chevron-left') . ' ' . $this->Html->tag('span', __('Back'), ['class' => 'hidden-xs hidden-sm']),
            ['action' => 'index','type_condition_id'=>$condition->type_condition_id],
            ['update' => '#page-content-wrapper', 'escape' => false, 'class' => 'load-ajax btn btn-sm btn-default', 'title' => __('Add')]):'' ?>

        <?= $this->Html->link($this->Html->icon('plus') . ' ' . $this->Html->tag('span', __('Add'), ['class' => 'hidden-xs hidden-sm']),
            array_merge(['action' => 'add'], $this->request->getQuery()),
            ['update' => '#page-content-wrapper', 'escape' => false, 'class' => 'load-ajax btn btn-sm btn-default', 'title' => __('Add')]) ?>
        <?= $this->Html->link($this->Html->icon('refresh') . ' ' . $this->Html->tag('span', __('Refresh'), ['class' => 'hidden-xs hidden-sm']),
            array_merge(['action' => 'index'], $this->request->getQuery()),
            ['update' => '#page-content-wrapper', 'escape' => false, 'class' => 'load-ajax btn btn-sm btn-default', 'title' => __('Refresh')]) ?>

        <?= $this->Html->link($this->Html->icon('sort') . ' ' . $this->Html->tag('span', __('Sort'), ['class' => 'hidden-xs hidden-sm']),
            array_merge(['action' => 'sort'], $this->request->getQuery()),
            ['update' => '#page-content-wrapper', 'escape' => false, 'class' => 'load-ajax btn btn-sm btn-default', 'title' => __('Sort')]) ?>

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
                                <th><?= $this->Paginator->sort('operator') ?></th>
                                <th><?= $label1 ?></th>
                                <th><?= $label2 ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($conditions as $condition): ?>
                            <tr>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-xs btn-info dropdown-toggle" data-toggle="dropdown">
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu flat">
                                            <li>
                                                <?= $this->Html->link($this->Html->icon('edit').' '.__('Edit'),
                                                    array_merge([$condition->id,'action' => 'edit'], $this->request->getQuery()),
                                                ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
                                            </li>
                                            <li>
                                                <?= $this->Html->link($this->Html->icon('eye').' '.__('View'),
                                                array_merge([$condition->id,'action' => 'view'], $this->request->getQuery()),
                                                ['data-toggle' => 'modal', 'data-target' => '#viewModal', 'escape' => false]) ?>
                                            </li>
                                            <li>
                                                <?= $this->Html->link($this->Html->icon('trash').' '.__('Delete'),
                                                ['action' => '#'],
                                                ['escape' => false,
                                                    'class' => 'btn-confirm',
                                                    'data-id' => $condition->id,
                                                    'data-toggle' => 'modal',
                                                    'data-target' => '#ConfirmDelete',
                                                    'data-action' => Router::url(
                                                            array_merge([$condition->id,'action' => 'delete'], $this->request->getQuery())
                                                            , false),]
                                                ) ?>
                                            </li>
                                            <?php if($condition->type_condition_id == \App\Model\Table\TypesConditionsTable::PORCENTAJECONDONACION): ?>
                                                <li class="divider"></li>
                                                <li>
                                                    <?= $this->Html->link($this->Html->icon('list').' '.__('Porcentaje pago minimo'), ['action' => 'index', 'condition_id' => $condition->id,'type_condition_id'=>\App\Model\Table\TypesConditionsTable::PORCENTAJEPAGOMINIMO],
                                                        ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </td>
                                <td><?= h(\App\Model\Table\ConditionsTable::getOperators()[$condition->operator]) ?></td>
                                <td><?= h($condition->compare) ?></td>
                                <td><?= h($condition->value) ?></td>
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