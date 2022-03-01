<?php
/** @var  $ticket \App\Model\Entity\Ticket */
/** @var  $Html \Cake\View\Helper\HtmlHelper */
?>
<section class="content-header">
    <h1 class="pull-left"><?= $this->Html->icon('list') . ' ' . __('Tickets') ?> |
        <small><?= __('Add') ?></small>
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
        <div class="col-md-8">
            <!-- general form elements -->
            <div class="box box-default" style="margin-bottom: 200px">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= __('Ticket Information') ?></h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <?= $this->Form->create($ticket, [
                    'type' => 'file',
                    'class' => 'form-ajax',
                    'update' => '#page-content-wrapper',
                    'horizontal' => true
                ]) ?>
                <div class="box-body">
                    <?= $this->Form->input('customer_id', ['required'=>'required', 'type'=>'number', 'label'=>'No. Identificación Cliente']) ?>
                    <?= $this->Form->input('priority', ['options'=>[1=>'Alta', 2=>'Media',3=>'Baja'],'empty'=>'Seleccionar']) ?>
                    <?= $this->Form->input('ticket_type_error_id', ['options'=>$typesErrors,'empty'=>'Seleccionar']) ?>
                    <?= $this->Form->input('title', ['options'=>$typesTitles,'empty'=>'Seleccionar']) ?>

                    <!--$this->Form->input('title')-->
                    <?= $this->Form->input(
                            'description',
                            [
                                'type' => 'textarea',
                                'help'=>'Favor describir detalladamente el problema e indicar el número de identificación del cliente si aplica.'
                            ]
                        ) ?>


                        <?= $this->Form->input('tickets_resources.0.img', ['label'=>__('resorce').' 1', 'type' => 'file','multiple', '_button' => ['class' => 'btn-info'], 'button-label' => __('Search')]) ?>
                        <?= $this->Form->input('tickets_resources.0.file_dir', ['type' => 'hidden']) ?>
                        <?= $this->Form->input('tickets_resources.0.file_type', ['type' => 'hidden']) ?>
                        <?= $this->Form->input('tickets_resources.0.file_size', ['type' => 'hidden']) ?>

                        <?= $this->Form->input('tickets_resources.1.img', ['label'=>__('resorce').' 1', 'type' => 'file','multiple', '_button' => ['class' => 'btn-info'], 'button-label' => __('Search')]) ?>
                        <?= $this->Form->input('tickets_resources.1.file_dir', ['type' => 'hidden']) ?>
                        <?= $this->Form->input('tickets_resources.1.file_type', ['type' => 'hidden']) ?>
                        <?= $this->Form->input('tickets_resources.1.file_size', ['type' => 'hidden']) ?>

                        <?= $this->Form->input('tickets_resources.2.img', ['label'=>__('resorce').' 1', 'type' => 'file','multiple', '_button' => ['class' => 'btn-info'], 'button-label' => __('Search')]) ?>
                        <?= $this->Form->input('tickets_resources.2.file_dir', ['type' => 'hidden']) ?>
                        <?= $this->Form->input('tickets_resources.2.file_type', ['type' => 'hidden']) ?>
                        <?= $this->Form->input('tickets_resources.2.file_size', ['type' => 'hidden']) ?>
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