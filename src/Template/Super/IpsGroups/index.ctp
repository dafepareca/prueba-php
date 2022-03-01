<?php
use Cake\Routing\Router;

/** @var  $ipsGroups \App\Model\Entity\IpsGroup[] */
/** @var  $Html \Cake\View\Helper\HtmlHelper */
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="pull-left"><?= $this->Html->icon('list') . ' ' . __('Ips Groups') ?>
        <span class="label lb-md label-default"><?= h($accessGroup->name) ?></span>
    </h1>
    <div class="btn-group pull-right">
        <?php
        echo $this->Html->link($this->Html->icon('chevron-left') . ' ' . $this->Html->tag('span', __('Back'), ['class' => 'hidden-xs hidden-sm']),
            ['controller' => 'access_groups'],
            ['update' => '#page-content-wrapper', 'escape' => false, 'class' => 'load-ajax btn btn-sm btn-default', 'title' => __('Back')]);
        echo $this->Html->link($this->Html->icon('plus') . ' ' . $this->Html->tag('span', __('Add'), ['class' => 'hidden-xs hidden-sm']),
            array_merge(['action' => 'add'], $this->request->getQuery()),
            ['update' => '#page-content-wrapper', 'escape' => false, 'class' => 'load-ajax btn btn-sm btn-default', 'title' => __('Add')]);
        echo $this->Html->link($this->Html->icon('refresh') . ' ' . $this->Html->tag('span', __('Refresh'), ['class' => 'hidden-xs hidden-sm']),
            array_merge(['action' => 'index'], $this->request->getQuery()),
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
                            <th><?= $this->Paginator->sort('ip_address') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($ipsGroups as $ipsGroup): ?>
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
                                                echo $this->Html->link($this->Html->icon('edit') . ' ' . __('Edit'),
                                                    array_merge(['action' => 'edit', $ipsGroup->id], $this->request->getQuery()),
                                                    ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]
                                                ); ?>
                                            </li>
                                            <li>
                                                <?php
                                                echo $this->Html->link($this->Html->icon('trash') . ' ' . __('Delete'),
                                                    ['action' => '#'],
                                                    ['escape' => false,
                                                        'class' => 'btn-confirm',
                                                        'data-id' => $ipsGroup->id,
                                                        'data-toggle' => 'modal',
                                                        'data-target' => '#ConfirmDelete',
                                                        'data-action' => Router::url(array_merge(['action' => 'delete', $ipsGroup->id], $this->request->getQuery()), false),]
                                                ); ?>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td><?= h($ipsGroup->name) ?></td>
                                <td><?= h($ipsGroup->ip_address) ?></td>
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