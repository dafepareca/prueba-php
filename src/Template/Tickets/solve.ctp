<?php
/**
  * @var \App\View\AppView $this
  */
?>
<?php
/** @var  $ticket \App\Model\Entity\Ticket */
/** @var  $Html \Cake\View\Helper\HtmlHelper */
?>
<section class="content-header">
    <h1 class="pull-left"><?= $this->Html->icon('list') . ' ' . __('Tickets') ?> |
        <small><?= __('Edit') ?></small>
    </h1>
    <div class="btn-group pull-right">
        <?= $this->Html->link($this->Html->icon('chevron-left') . ' ' . $this->Html->tag('span', __('Back'), ['class' => 'hidden-xs hidden-sm']),
            ['action' => 'index'], ['escape' => false, 'class' => 'load-ajax btn btn-sm btn-default', 'update' => '#page-content-wrapper', 'title' => __('Back')]); ?>
    </div>
    <div class="clearfix"></div>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= __('Ticket Information') ?></h3>
                </div>
                <dl class="dl-horizontal">
                    <dt><?= __('Title') ?></dt>
                    <dd><?= h($ticket->title) ?></dd>
                    <dt><?= __('User') ?></dt>
                    <dd><?= $ticket->has('user') ? $ticket->user->name : '' ?></dd>
                    <dt><?= __('Resolved') ?></dt>
                    <dd><?= $ticket->resolved ? __('Yes') : __('No') ?></dd>
                    <dt><?= __('Approved') ?></dt>
                    <dd><?= $ticket->approved ? __('Yes') : __('No') ?></dd>
                    <dt><?= __('Description') ?></dt>
                    <dd><?= $this->Text->autoParagraph(h($ticket->description)) ?></dd>
                    <dt><?= __('Resolved Detail') ?></dt>
                    <dd><?= $this->Text->autoParagraph(h($ticket->resolved_detail)) ?></dd>
                    <dt><?= __('Modified') ?></dt>
                    <dd><?= $ticket->modified->format('Y-m-d h:i:s A') ?></dd>
                    <dt><?= __('Created') ?></dt>
                    <dd><?= $ticket->created->format('Y-m-d h:i:s A') ?></dd>
                </dl>
                <!-- /.box-header -->
                <!-- form start -->
                <?= $this->Form->create($ticket, [
                    'class' => 'form-ajax',
                    'update' => '#page-content-wrapper',
                    'horizontal' => true
                ]) ?>
                <div class="box-body">
                    <?= $this->Form->input('resolved_detail', ['type' => 'textarea']) ?>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="pull-left">
                        <?= $this->Form->button($this->Html->icon('save').' '.__('Save'), ['class' => 'btn-success', 'escape' => false]) ?>
                    </div>
                    <div class="pull-right">
                        <i>* <?= __('Required fields') ?></i>
                    </div>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</section>