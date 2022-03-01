<?php
use Cake\Routing\Router;
/** @var  $trainings \App\Model\Entity\Training[] */
/** @var  $Html \Cake\View\Helper\HtmlHelper*/
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="pull-left"><?= $this->Html->icon('list') . ' ' . __('Trainings') ?></h1>

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
                            <?php foreach ($trainings as $training): ?>
                            <tr>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-xs btn-info dropdown-toggle" data-toggle="dropdown">
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu flat">
                                            <li>
                                                <?= $this->Html->link($this->Html->icon('eye').' '.__('View'), ['action' => 'view', $training->id],
                                                ['data-toggle' => 'modal', 'data-target' => '#viewModalTraining', 'escape' => false]) ?>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td><?= h($training->name) ?></td>
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

    <div class="modal modal-danger" id="viewModalTraining">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">
                        <?=__('Historial Cliente')?>
                    </h4>
                </div>
                <div class="modal-body">


                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>