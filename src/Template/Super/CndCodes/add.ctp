<?php
/** @var  $cndCode \App\Model\Entity\Cnd Code */
/** @var  $Html \Cake\View\Helper\HtmlHelper */
?>
<section class="content-header">
    <h1 class="pull-left"><?= $this->Html->icon('list') . ' ' . __('Cnd Codes') ?> |
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
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= __('Cnd Code Information') ?></h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <?= $this->Form->create($cndCode, [
                    'class' => 'form-ajax',
                    'update' => '#page-content-wrapper',
                    'horizontal' => true
                ]) ?>
                <div class="box-body">
                    <?= $this->Form->input('code') ?>
                    <?= $this->Form->input('message',
                        ['options' => [
                            'Sin estrategia'=>'Sin estrategia',
                            'Solicite desmarcación unidad de cumplimiento'=>'Solicite desmarcación unidad de cumplimiento',
                            'Cliente Fondo Nacional de Garantías'=>'Cliente Fondo Nacional de Garantías',
                            'Conjuntos Problema, validar estrategia - Preguntas Frecuentes' => 'Conjuntos Problema, validar estrategia - Preguntas Frecuentes',
                            'Solicite desmarcación seguridad bancaria' => 'Solicite desmarcación seguridad bancaria',
                            'Cliente amparado con la circular 007 y 014 de la Súper financiera (Covid-19)' => 'Cliente amparado con la circular 007 y 014 de la Súper financiera (Covid-19)'
                        ],'empty'=>'selected']) ?>
                    <?= $this->Form->input('not_negotiate',['type' => 'checkbox']) ?>
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