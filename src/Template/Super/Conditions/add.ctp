<?php
/** @var  $condition \App\Model\Entity\Condition */
/** @var  $Html \Cake\View\Helper\HtmlHelper */
/** @var  $typeCondition \App\Model\Entity\TypesCondition*/
?>
<section class="content-header">
    <h1 class="pull-left"><?= $this->Html->icon('list') . ' ' . __('Conditions') ?> |
        <small><?= __('Add') ?> | <?=$typeCondition->type?></small>
    </h1>
    <div class="btn-group pull-right">
        <?= $this->Html->link($this->Html->icon('chevron-left') . ' ' . $this->Html->tag('span', __('Back'), ['class' => 'hidden-xs hidden-sm']),
            array_merge(['action' => 'index'], $this->request->getQuery()),
            ['escape' => false, 'class' => 'load-ajax btn btn-sm btn-default', 'update' => '#page-content-wrapper', 'title' => __('Back')]); ?>
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
                    <h3 class="box-title"><?= __('Condition Information') ?></h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <?= $this->Form->create($condition, [
                    'class' => 'form-ajax',
                    'update' => '#page-content-wrapper',
                    'horizontal' => true
                ]) ?>
                <div class="box-body">
                    <?= $this->Form->input('operator',['empty'=>__('Select Item'),'options'=> \App\Model\Table\ConditionsTable::getOperators()]) ?>
                    <?= (!empty($opciones))?$this->Form->input('compare',['options'=> $opciones,'label'=>$label1]):$this->Form->input('compare',['label'=>$label1]) ?>
                    <?= (!empty($opcionesValor))?$this->Form->input('value',['empty'=>__('Select option'),'options'=> $opcionesValor,'label'=>$label2]):$this->Form->input('value', ['label' => $label2]) ?>

                    <?php if($type_condition_id == \App\Model\Table\TypesConditionsTable::PORCENTAJECONDONACION): ?>
                    <?= $this->Form->input('value_2', ['label' => $label2.' con Hp','required'=>'required']) ?>
                    <?php endif; ?>

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

<script>

    $( "#operator" ).change(function() {
        if($(this).val() == 6){
            bootbox.confirm("<form id='infos' action=''>\
            Valor minimo:<input id='valor_minimo' type='text' class='form-control' name='valor_minimo' /><br/>\
            Valor maximo:<input id='valor_maximo' type='text' class='form-control' name='valor_maximo' />\
            </form>", function(result) {
                    if(result){
                        var valor = $('#valor_minimo').val() +' , '+ $('#valor_maximo').val()
                        $('#compare'). val(valor);
                    }else {
                        $('#compare'). val();
                    }

            });
        }

    });


</script>