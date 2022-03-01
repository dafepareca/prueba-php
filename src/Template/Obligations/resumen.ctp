<?php
/** @var  $customer \App\Model\Entity\Customer */
?>

<?= $this->Form->create('', [
    'id' => 'form-finalizar',
    'horizontal' => false,
    'url' => ['controller' => 'obligations', 'action' => 'finalizar']
]) ?>
<div class="row div-separador">
    <fieldset class="container scheduler-border" style="padding: 0 1.4em 1.4em 50em !important">
        <legend class="scheduler-border"><?=__('Resumen negociación')?></legend>
    </fieldset>

    <?php if (!empty($sinCambio)): ?>
    <div class="col-md-12">
        <div style="border: 1px solid #000; padding: 8px; border-radius: 8px">
            <h5 style="text-align: center; color: #ed1c27"><?=__('Obligaciones Sin Cambio')?></h5>
            <div class="table-responsive table-info">
                <table class="table table-responsive table-bordered table-hover">
                    <thead class="thead-default">
                    <tr class="table-danger">
                        <th><?= __('Tipo') ?></th>
                        <th><?= __('Obligación') ?></th>
                        <th><?= __('Saldo total') ?></th>
                        <th><?= __('Cuota') ?></th>
                        <th><?= __('Pago mínimo') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    /** @var  $obligacion \App\Model\Entity\Obligation */
                    foreach ($sinCambio as $obligacion): ?>
                        <tr>
                            <td><?= $obligacion->type_obligation->type ?></td>
                            <td><?= $obligacion->maskobligation ?></td>
                            <td><?= $this->Number->Currency($obligacion->total_debt, null, ['precision' => 0]) ?></td>
                            <td><?= $this->Number->Currency($obligacion->fee, null, ['precision' => 0]) ?></td>
                            <td><?= $this->Number->Currency($obligacion->minimum_payment, null, ['precision' => 0]) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>
<?php if(!empty($conCambio)): ?>
<div class="row div-separador">

    <div class="col-md-12">
        <div style="border: 1px solid #000; padding: 8px; border-radius: 8px">
            <h5 style="text-align: center; color: #ed1c27"><?=__('Alternativas')?></h5>
            <div class="table-responsive table-info">
                <table class="table table-responsive table-bordered table-hover">
                    <thead class="thead-default">
                    <tr class="table-danger">
                        <th><?= __('Tipo') ?></th>
                        <th><?= __('Obligación') ?></th>
                        <th><?= __('Saldo total') ?></th>
                        <th><?= __('Cuota anterior') ?></th>
                        <th><?= __('Nuevo plazo') ?></th>
                        <th><?= __('Nueva tasa EA') ?></th>
                        <th><?= __('Nueva cuota') ?></th>
                        <th><?= __('Estrategia') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    /** @var  $obligacion \App\Model\Entity\Obligation */
                    foreach ($conCambio as $obligacion): ?>
                        <tr>
                            <td><?= $obligacion->type_obligation->type ?></td>
                            <td><?= $obligacion->obligation ?></td>
                            <td><?= $this->Number->Currency($obligacion->total_debt, null, ['precision' => 0]) ?></td>
                            <td><?= $this->Number->Currency($obligacion->fee, null, ['precision' => 0]) ?></td>
                            <td><?= $obligacion->nuevoPlazo ?></td>
                            <td><?= round($obligacion->rate,1) ?>%</td>
                            <td><?= $this->Number->Currency($obligacion->nuevaCuota, null, ['precision' => 0]) ?></td>
                            <td><?= $obligacion->estrategias[$obligacion->estrategia] ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<?php endif; ?>

<div class="row div-separador">

    <?php
    $valorNormalizacion = 0;
    if (!empty($normalizadas)):
        $valorNormalizacion = $negociacion['normalizacion']['cuota'];
        ?>
    <div class="col-md-6">
        <div style="border: 1px solid #000; padding: 8px; border-radius: 8px">
            <h5 style="text-align: center; color: #ed1c27"><?=__('Unificación de obligaciones')?></h5>
            <div class="table-responsive table-info">
                <table class="table table-responsive table-bordered table-hover">
                    <thead class="thead-default">
                    <tr class="table-danger">
                        <th><?= __('Tipo') ?></th>
                        <th><?= __('Obligación') ?></th>
                        <th><?= __('Saldo total') ?></th>
                        <th><?= __('Cuota') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    /** @var  $obligacion \App\Model\Entity\Obligation */
                    foreach ($normalizadas as $obligacion): ?>
                        <tr>
                            <td><?= $obligacion->type_obligation->type ?></td>
                            <td><?= $obligacion->obligation ?></td>
                            <td><?= $this->Number->Currency($obligacion->total_debt, null, ['precision' => 0]) ?></td>
                            <td><?= $this->Number->Currency($obligacion->fee, null, ['precision' => 0]) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div style="border: 1px solid #000; padding: 8px; border-radius: 8px">
            <h5 style="text-align: center; color: #ed1c27"><?=__('Unificación de obligaciones')?></h5>
            <div class="table-responsive table-info">
                <table class="table table-responsive table-bordered table-hover">
                    <thead class="thead-default">

                    </thead>
                    <tbody>
                    <tr>
                        <th><?= __('Saldo') ?></th>
                        <td><?= $this->Number->Currency($saldo, null, ['precision' => 0]) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Plazo') ?></th>
                        <td><?= $negociacion['normalizacion']['plazo'] ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Tasa EM') ?></th>
                        <td><?= round($negociacion['normalizacion']['tasa'],1) ?>%</td>
                    </tr>
                    <tr>
                        <th><?= __('Tasa EA') ?></th>
                        <td><?= round($negociacion['normalizacion']['tasa_anual'],1) ?>%</td>
                    </tr>
                    <tr>
                        <th><?= __('Cuota') ?></th>
                        <td><?= $this->Number->Currency($negociacion['normalizacion']['cuota'], null, ['precision' => 0]) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Observación') ?></th>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <th><?= __('Pago inicial acordado cliente') ?></th>
                        <td><?= $this->Number->Currency($negociacion['pago_acordado'], null, ['precision' => 0]) ?></td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>

<?php if(!empty($castigadaHp)): ?>
<div class="row div-separador">

    <div class="col-md-12">
        <div style="border: 1px solid #000; padding: 8px; border-radius: 8px">
            <h5 style="text-align: center; color: #ed1c27"><?=__('Castigada con garantía')?></h5>
            <div class="table-responsive table-info">
                <table class="table table-responsive table-bordered table-hover">
                    <thead class="thead-default">
                    <tr class="table-danger">
                        <th><?= __('Tipo') ?></th>
                        <th><?= __('Obligación') ?></th>
                        <th><?= __('Saldo total') ?></th>
                        <th><?= __('Cuota anterior') ?></th>
                        <th><?= __('Nuevo plazo') ?></th>
                        <th><?= __('Nueva tasa EA') ?></th>
                        <th><?= __('Nueva cuota') ?></th>
                        <th><?= __('Estrategia') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    /** @var  $obligacion \App\Model\Entity\Obligation */
                    foreach ($castigadaHp as $obligacion): ?>
                        <tr>
                            <td><?= $obligacion->type_obligation->type ?></td>
                            <td><?= $obligacion->obligation ?></td>
                            <td><?= $this->Number->Currency($obligacion->total_debt, null, ['precision' => 0]) ?></td>
                            <td><?= $this->Number->Currency($obligacion->fee, null, ['precision' => 0]) ?></td>
                            <td><?= $obligacion->nuevoPlazo ?></td>
                            <td><?= round($obligacion->rate,1) ?>%</td>
                            <td><?= $this->Number->Currency($obligacion->nuevaCuota, null, ['precision' => 0]) ?></td>
                            <td><?= $obligacion->estrategias[$obligacion->estrategia] ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<?php endif; ?>

<div class="row div-separador">

    <?php
    if (!empty($castigada)):?>
        <div class="col-md-6">
            <div style="border: 1px solid #000; padding: 8px; border-radius: 8px">
                <h5 style="text-align: center; color: #ed1c27"><?=__('Unificación de obligaciones Castigada')?></h5>
                <div class="table-responsive table-info">
                    <table class="table table-responsive table-bordered table-hover">
                        <thead class="thead-default">
                        <tr class="table-danger">
                            <th><?= __('Tipo') ?></th>
                            <th><?= __('Obligación') ?></th>
                            <th><?= __('Saldo total') ?></th>
                            <th><?= __('Cuota') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        /** @var  $obligacion \App\Model\Entity\Obligation */
                        foreach ($castigada as $obligacion): ?>
                            <tr>
                                <td><?= $obligacion->type_obligation->type ?></td>
                                <td><?= $obligacion->obligation ?></td>
                                <td><?= $this->Number->Currency($obligacion->total_debt, null, ['precision' => 0]) ?></td>
                                <td><?= $this->Number->Currency($obligacion->fee, null, ['precision' => 0]) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <?php
        $normalizacionCastigada = 0;
        if(isset($propuestaCastigada)):
            $normalizacionCastigada = $propuestaCastigada['cuota'];
            ?>
        <div class="col-md-6">
            <div style="border: 1px solid #000; padding: 8px; border-radius: 8px">
                <h5 style="text-align: center; color: #ed1c27"><?=__('Unificación de obligaciones castigada')?></h5>
                <div class="table-responsive table-info">
                    <table class="table table-responsive table-bordered table-hover">
                        <thead class="thead-default">

                        </thead>
                        <tbody>
                        <tr>
                            <th><?= __('Saldo') ?></th>
                            <td><?= $this->Number->Currency($saldoCastigada, null, ['precision' => 0]) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Plazo') ?></th>
                            <td><?= $propuestaCastigada['plazo'] ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Cuota') ?></th>
                            <td><?= $this->Number->Currency($propuestaCastigada['cuota'], null, ['precision' => 0]) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Tasa interes mensual') ?></th>
                            <td><?= round($propuestaCastigada['tasa']*100,1) ?>%</td>
                        </tr>
                        <tr>
                            <th><?= __('Tasa interes anual') ?></th>
                            <td><?= $propuestaCastigada['tasa_anual'] ?>%</td>
                        </tr>
                        <tr>
                            <th><?= __('% Condonacion inicial') ?></th>
                            <td><?= round($propuestaCastigada['condonacion_inicial']*100,0,PHP_ROUND_HALF_DOWN) ?>%</td>
                        </tr>
                        <tr>
                            <th><?= __('Condonacion inicial') ?></th>
                            <td><?= $this->Number->Currency($propuestaCastigada['valor_condonacion_inicial'], null, ['precision' => 0]) ?></td>
                        </tr>

                        <tr>
                            <th><?= __('% Condonacion final') ?></th>
                            <td><?=round($propuestaCastigada['condonacion']*100,0,PHP_ROUND_HALF_DOWN)?>%</td>
                        </tr>
                        <tr>
                            <th><?= __('Condonacion final') ?></th>
                            <td><?= $this->Number->Currency($propuestaCastigada['valor_condonacion'], null, ['precision' => 0]) ?></td>
                        </tr>

                        <tr>
                            <td colspan="2"></td>
                        </tr>
                        <tr>
                            <th><?= __('Pago inicial acordado cliente') ?></th>
                            <td><?= $this->Number->Currency($propuestaCastigada['pago_inicial'], null, ['precision' => 0]) ?></td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php endif; ?>

</div>

<?php
if (!empty($otrasAlternativas)):
?>
<div class="row div-separador">

    <div class="col-md-12">
        <div style="border: 1px solid #000; padding: 8px; border-radius: 8px">
            <h5 style="text-align: center; color: #ed1c27"><?=__('Otras alternativas')?></h5>
            <div class="table-responsive table-info">
                <table class="table table-responsive table-bordered table-hover">
                    <thead class="thead-default">
                    <tr class="table-danger">
                        <th><?= __('Tipo') ?></th>
                        <th><?= __('Obligación') ?></th>
                        <th><?= __('Saldo total') ?></th>
                        <th><?= __('Valor del pago') ?></th>
                        <th><?= __('Estrategia') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    /** @var  $obligacion \App\Model\Entity\Obligation */
                    foreach ($otrasAlternativas as $obligacion): ?>
                        <tr>
                            <td><?= $obligacion->type_obligation->type ?></td>
                            <td><?= $obligacion->maskobligation ?></td>
                            <td><?= $this->Number->Currency($obligacion->total_debt, null, ['precision' => 0]) ?></td>
                            <td><?= $this->Number->Currency($obligacion->nuevaCuota, null, ['precision' => 0]) ?></td>
                            <td><?= $obligacion->estrategias[$obligacion->estrategia] ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<?php endif; ?>
<div class="row div-separador">

    <div class="col-md-6">
        <div style="border: 1px solid #000; padding: 8px; border-radius: 8px">
            <h5 style="text-align: center; color: #ed1c27"><?=__('Situación cliente')?></h5>
            <div class="table-responsive table-info">
                <table class="table table-responsive table-bordered table-hover">
                    <thead class="thead-default">
                    <tr class="table-danger">
                        <th><?= __('Situación cliente') ?></th>
                        <th><?= __('Inicial') ?></th>
                        <th><?= __('Final') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th><?= __('Saldo') ?></th>
                        <td><?= $this->Number->Currency($total, null, ['precision' => 0]) ?></td>
                        <td><?= $this->Number->Currency($total, null, ['precision' => 0]) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Cuotas') ?></th>
                        <td><?= $this->Number->Currency($totalCuota, null, ['precision' => 0]) ?></td>
                        <td><?= $this->Number->Currency($totalNuevaCuota+$valorNormalizacion, null, ['precision' => 0]) ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <?php
            if (!empty($castigada)):?>
            <h5 style="text-align: center; color: #ed1c27"><?=__('Situación cliente cartera castigada')?></h5>
            <div class="table-responsive table-info">
                <table class="table table-responsive table-bordered table-hover">
                    <thead class="thead-default">
                    <tr class="table-danger">
                        <th><?= __('Situación cliente') ?></th>
                        <th><?= __('Inicial') ?></th>
                        <th><?= __('Final') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th><?= __('Saldo') ?></th>
                        <td><?= $this->Number->Currency($totalCastigada, null, ['precision' => 0]) ?></td>
                        <td><?= $this->Number->Currency($totalCastigada, null, ['precision' => 0]) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Cuotas') ?></th>
                        <td><?= $this->Number->Currency($totalCuotaCastigada, null, ['precision' => 0]) ?></td>
                        <td><?= $this->Number->Currency($totalNuevaCuotaCastigada+$normalizacionCastigada, null, ['precision' => 0]) ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php
        if($citaCentroCartera == true){
    ?>
    <div class="col-md-6">
        <div style="border: 1px solid #000; padding: 8px; border-radius: 8px">
            <h5 style="text-align: center; color: #ed1c27"><?=__('Cita centro de cartera')?></h5>

            <?= $this->Form->input(
                '',
                array(
                    'class' => 'entregaDocumentos',
                    'label'=>__('Entrega Documentos'),
                    'required',
                    'options' => [0 => 'Oficina', 1 => 'Domicilio'],
                    'style'=> 'width: 100%;'
                )
            ) ?>
            <div class="oficinas">

            <?= $this->Form->input(
                'ciudad',
                array(
                    'label'=>__('ciudad'),
                    'required',
                    'options' => $ciudades,
                    'empty' => __('seleccionar'),
                    'style'=> 'width: 100%;'
                )
            ) ?>

            <?= $this->Form->input(
                'oficina',
                array(
                    'label'=>__('Oficina'),
                    'required',
                    'placeholder' => __('seleccionar')
                )
            ) ?>

            <?= $this->Form->input(
                'oficina_id',
                array(
                    'type' => 'hidden',
                    'id' => 'oficina_id',
                    'value' => 0
                )
            ) ?>
            </div>

            <div class="domicilio" style="display: none">
                <?= $this->Form->input(
                    'ciudad',
                    array(
                        'label'=>__('ciudad'),
                        'required',
                        'type' => 'text',
                        'style'=> 'width: 100%;',
                        'disabled' => 'disabled',
                        'id' => 'ciudad_2'
                    )
                ) ?>

                <?= $this->Form->input(
                    'oficina',
                    array(
                        'label'=>__('Dirección'),
                        'required',
                        'type' => 'text',
                        'id' => 'oficina_2',
                        'disabled' => 'disabled'
                    )
                ) ?>
            </div>

            <?= $this->Form->input('fecha', array('label'=>__('Fecha'),'id' => 'datetimepicker', 'required','type' => 'text')) ?>
            
            <?php if (isset($diaPagoCredito)) { ?> 
                <?= $this->Form->input('dia_pago_credito', [
                    'label'=>__('Día Pago Crédito'), 
                    'class' => 'diaPagoCredito', 
                    'required', 
                    'options' => $diaPagoCredito,
                ]) ?>

                
            <?php } if (isset($documentosPagare)){?>
                <?= $this->Form->input('documentos_pagare', [
                    'type' => 'hidden',
                    'value' => "1",
                ]) ?>
                
                <h3 class="text-center"><span style="color: #fff" class="label label-warning"><?= $documentosPagare ?></span></h3>
            <?php }?>
        </div>
    </div>
    <?php
    }else{
    ?>
            <div class="col-md-6">
        <div style="border: 1px solid #000; padding: 8px; border-radius: 8px">
            <h5 style="text-align: center; color: #ed1c27"><?=__('Fecha de Pago')?></h5>

            <?= $this->Form->input('fecha', array('label'=>__('Fecha'),'id' => 'datetimepicker', 'required','type' => 'text')) ?>

            <?php if (isset($diaPagoCredito) && isset($documentosPagare)) { ?>
                <?= $this->Form->input('dia_pago_credito', [
                    'label'=>__('Día Pago Crédito'), 
                    'class' => 'diaPagoCredito', 
                    'required', 
                    'options' => $diaPagoCredito,
                ]) ?>

                <?= $this->Form->input('documentos_pagare', [
                    'type' => 'hidden',
                    'value' => "0",
                ]) ?>

                
                <h3 class="text-center"><span style="color: #fff" class="label label-warning"><?= $documentosPagare ?></span></h3>
            <?php } ?>

        </div>
    </div>
    <?php
        }
    ?>
</div>

<?php if($comite): ?>
    <div class="row">
        <div class="col-md-4">
            <?=$this->Form->input('coordinador',
                [
                    'label' => __('Coordinador').': ',
                    'options'=>  $coordinadores,
                    'class' => 'finalizar',
                    'required' => 'required',
                    'empty' => __('seleccionar')
                ]
            );?>
        </div>
    </div>
<?php endIf; ?>

<div class="text-center div-separador">
    <?php
    if($comite){
        echo $this->Form->input('comite',
            [
                'value'=>1,
                'type' => 'hidden',
                'id' => 'enviar-comite'
            ]
        );
        echo  $this->Form->button($this->Html->icon('paper-plane-o'). __('Enviar a Comité'), ['type'=>'submit','class' => 'btn btn-primary', 'div' => false]);
    }else{
        echo $this->Form->button(__('Finalizar'), ['type'=>'submit','class' => 'btn btn-primary', 'div' => false]);
    }
    ?>

</div>

<div class="div-separador-2x"></div>

<?= $this->Form->end(); ?>

<div class="example-modal">
    <div id="modaloficinas" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><?=__('Oficinas')?></h4>
                </div>
                <div id="bodymodaloficinas" class="modal-body" style="max-height: 450px; overflow-y: auto;">


                </div>
                <!--<div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>-->
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</div>
<!-- /.example-modal -->


<script>


    $(function () {
        $("#ciudad").select2();
    });

    $(function () {
        $('#datetimepicker').datetimepicker({
            format: 'YYYY-MM-DD',
            locale: 'es',
            daysOfWeekDisabled: [0],
            minDate : 'now'
        });
    });


    $(document).on('change', '.entregaDocumentos', function () {

       var val = $(this).val();
       if(val == 1){
           $('.oficinas').css('display','none');
           $('.domicilio').css('display','block');
           $('#ciudad').attr('disabled','disabled');
           $('#oficina').attr('disabled','disabled');
           $('#oficina_id').attr('disabled','disabled');

           $('#ciudad_2').removeAttr('disabled','disabled');
           $('#oficina_2').removeAttr('disabled','disabled');

       } else {
           $('.oficinas').css('display','block');
           $('.domicilio').css('display','none');
           $('#ciudad').removeAttr('disabled','disabled');
           $('#oficina').removeAttr('disabled','disabled');
           $('#oficina_id').removeAttr('disabled');
           $('#ciudad_2').attr('disabled','disabled');
           $('#oficina_2').attr('disabled','disabled');
       }
    });

</script>