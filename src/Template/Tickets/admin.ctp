<?php
use Cake\Routing\Router;
/** @var  $tickets \App\Model\Entity\Ticket[] */
/** @var  $Html \Cake\View\Helper\HtmlHelper*/
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="pull-left"><?= $this->Html->icon('list') . ' ' . __('Tickets') ?></h1>
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
                    <?= $this->element('search/tickets') ?>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover table-condensed table-striped table-bordered">
                        <thead>
                        <tr>
                            <th width="30"></th>
                            <th><?= $this->Paginator->sort('customer_id') ?></th>
                            <th><?= $this->Paginator->sort('title') ?></th>
                            <th><?= $this->Paginator->sort('priority') ?></th>
                            <th><?= $this->Paginator->sort('ticket_type_error',__('ticket type error')) ?></th>
                            <th><?= $this->Paginator->sort('state') ?></th>
                            <th><?= $this->Paginator->sort('user_id') ?></th>
                            <th><?= $this->Paginator->sort('created') ?></th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($tickets as $ticket): ?>
                            <tr>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-xs btn-info dropdown-toggle" data-toggle="dropdown">
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu flat">

                                            <li>
                                                <?= $this->Html->link($this->Html->icon('eye').' '.__('View'), ['action' => 'view', $ticket->id],
                                                    ['data-toggle' => 'modal', 'data-target' => '#viewModal', 'escape' => false]) ?>
                                            </li>
                                            <?php if($ticket->ticket_state_id == \App\Model\Table\TicketsStatusTable::PENDIENTE): ?>
                                                <li>
                                                    <?= $this->Html->link($this->Html->icon('edit').' '.__('Developing'), ['action' => 'developing', $ticket->id],
                                                        ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
                                                </li>
                                            <?php endif; ?>
                                            <?php if ($ticket->ticket_state_id == \App\Model\Table\TicketsStatusTable::DESARROLLO): ?>
                                                <li>
                                                    <?= $this->Html->link($this->Html->icon('edit').' '.__('Solve'), ['action' => 'solve', $ticket->id],
                                                        ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
                                                </li>
                                            <?php endif; ?>
                                            <?php if ($ticket->ticket_state_id == \App\Model\Table\TicketsStatusTable::REVISION): ?>

                                                <li>
                                                    <?= $this->Html->link($this->Html->icon('check') . ' ' . __('Approved'),
                                                        ['action' => '#'],
                                                        ['escape' => false,
                                                            'class' => 'btn-confirm',
                                                            'data-id' => $ticket->id,
                                                            'data-toggle' => 'modal',
                                                            'data-target' => '#ConfirmApprovedTicket',
                                                            'data-action' => Router::url(['action' => 'approved', $ticket->id], false),]
                                                    );
                                                    ?>
                                                </li>
                                            <?php endif; ?>

                                        </ul>
                                    </div>
                                </td>
                                <td><?= h($ticket->customer_id) ?></td>
                                <td><?= h($ticket->title) ?></td>
                                <td><?= $ticket->priorities[$ticket->priority] ?></td>
                                <td><?= $ticket->tickets_types_error->type ?></td>
                                <td><?= h($ticket->tickets_status->state) ?></td>
                                <td><?= $ticket->has('user') ? $ticket->user->name : '' ?></td>
                                <td><?= h($ticket->created->format('Y-m-d H:i')) ?></td>
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