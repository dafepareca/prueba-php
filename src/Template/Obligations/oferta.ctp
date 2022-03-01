<?= $this->Form->create('', [
    'class' => 'form-acetar-oferta',
    'id' => 'form-acetar-oferta',
    'update' => '#resumen',
    'horizontal' => false,
]) ?>

<?php if(!empty($ofertasVigente)): ?>
    <div class="row div-separador">
        <fieldset class="container scheduler-border" style="padding: 0 1.4em 1.4em 50em !important">
            <legend class="scheduler-border">Alternativas de Solución</legend>
        </fieldset>
        <div class="text-center">
            <?php if(isset($normalizacion) && !empty($normalizacion)): ?>
            <h5><span style="color: #ed1c27">Disponible para negociación:</span> <?=$this->Number->Currency($capacidad_disponible, null, ['precision' => 0])?></h5>
            <?php endif; ?>
        </div>
        <div class="col-md-8">
            <div style="border: 1px solid #000; padding: 8px; border-radius: 8px">
                <div class="table-responsive table-info">
                    <table class="table table-hover">
                        <thead class="thead-default">
                        <tr class="table-danger">
                            <!--<th style="background-color: #fff; border: none !important; color: #ed1c27"><?#=__('Acepta');?></th>-->
                            <th><?=__('Tipo')?></th>
                            <th><?=__('Obligación')?></th>
                            <th><?=__('Alternativa de Negociación')?></th>
                            <th><?=__('Pago sugerido')?></th>
                            <th><?=__('Pagos Acumulados')?></th>
                            <th><?=__('Pago real')?></th>
                            <th><?=__('Plazo')?></th>
                            <th><?=__('Nueva Cuota')?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $totalPagoSugerido = 0;
                        $totalPagoAcomulado = 0;
                        $totalPagoReal = 0;

                        $totalPagoSugeridoN = 0;
                        $totalPagoRealN = 0;
                        $totalPagoAcomuladoN = 0;

                        $pagoAcumulado = 0;
                        $pagoSugerido = 0;
                        $abonoReal = 0;

                        /** @var  $obligacion \App\Model\Entity\Obligation*/
                        foreach($ofertasVigente as $obligacion):
                            $tipo = $obligacion->type_obligation_id;
                            $attr='';
                            if(in_array($tipo,[\App\Model\Table\TypeObligationsTable::CXR,\App\Model\Table\TypeObligationsTable::TDC])){
                                $attr = 'checked disabled';
                            }

                            $totalPagoSugerido+=$obligacion->pagoSugerido;
                            $totalPagoAcomulado+=$obligacion->acomulated_payment;
                            $totalPagoReal+=$obligacion->pagoReal;

                            ?>
                            <tr>
                                <!--<td style="border: none;">-->
                                <?php #if($obligacion->estrategia != 1 && $obligacion->negociable == 0): ?>
                                <!--<div class="checkbox checkbox-table">
                                <label>
                                    <input name="obligacion[<?=$obligacion->id?>][acepta]" class="finalizar" type="checkbox"  value="1" <?#=$attr?>>
                                    <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                </label>
                            </div>-->
                                <?php #endIf; ?>
                                <!--</td>-->
                                <td><?=$obligacion->type_obligation->type?></td>
                                <td><?=$obligacion->maskobligation?></td>
                                <td><?=$obligacion->estrategias[$obligacion->estrategia]?></td>
                                <td><?=$this->Number->Currency($obligacion->pagoSugerido, null, ['precision' => 0])?></td>
                                <td><?=$this->Number->Currency($obligacion->acomulated_payment, null, ['precision' => 0])?></td>
                                <td>
                                    <?php
                                    if(!in_array($obligacion->estrategia,[1,4,5,16])) {
                                       echo $this->Form->input('pago.' . $obligacion->id,
                                            [
                                                'value' => round($obligacion->pagoReal),
                                                'label' => false,
                                                'class' => 'div-false finalizar input-sm money pago_real',
                                                'templates' => [
                                                    'inputContainer' => '{{content}}'
                                                ],
                                                'required' => 'required',
                                                'type' => 'text'
                                            ]
                                        );
                                    }?>
                                </td>
                                <td><?=$obligacion->nuevoPlazo?></td>
                                <td><?=$this->Number->Currency($obligacion->nuevaCuota, null, ['precision' => 0])?></td>
                            </tr>
                        <?php endforeach; 
                            if(isset($normalizacion['data'])){
                                foreach($normalizacion['data'] as $key => $data):
                                    if ($key == 0) {
                                        $abonoReal = $data['abono_real'];
                                    }
                                endforeach;
                            }
                            $totalPagoReal += $abonoReal;
                        ?>
                        <tr>
                            <td></td>
                            <td></td>
                            <td><b>Total: </b></td>
                            <td><b><?=$this->Number->Currency($totalPagoSugerido, null, ['precision' => 0])?></b></td>
                            <td><b><?=$this->Number->Currency($totalPagoAcomulado, null, ['precision' => 0])?></b></td>
                            <td><b class="total_pago_real"><div id='total_pago_real'><?=$this->Number->Currency($totalPagoReal, null, ['precision' => 0])?></div></b></td>
                            <td></td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="div-separador"></div>

            <div class="row">
                <div class="col-md-10 abonos <?= $totalPagoReal; ?>">


                    <?=$this->Form->input('payment_agreed',
                        [
                            'label' => false,
                            'class' => 'div-false finalizar',
                            'templates' => [
                                'inputContainer' => '{{content}}'
                            ],
                            'value'=> $totalPagoReal,
                            'required' => 'required',
                            'type' => 'hidden'
                        ]
                    );?>

                </div>
            </div>
        </div>
        <div class="col-md-4">
            <?php
            $valor = null;
            if(isset($normalizacion) && !empty($normalizacion)):?>
                <div style="border: 1px solid #000; padding: 8px; border-radius: 8px">
                    <h5 style="text-align: center; color: #ed1c27">Unificación Productos Consumos</h5>
                    <div class="table-responsive table-info">
                        <table class="table table-hover">
                            <thead class="thead-default">

                            <tr class="table-danger">
                                <th style="background-color: #fff; border: none !important; color: #ed1c27">
                                    <?=__('Acepta');?>
                                </th>
                                <th><?=__('Cuota')?></th>
                                <th><?=__('Tasa')?></th>
                                <th><?=__('Plazo')?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if(isset($normalizacion['data'])):
                                $i = 0;
                                foreach($normalizacion['data'] as $key => $data):
                                    $class = '';
                                    $checked = '';
                                    $disabled = '';

                                    if($data['rango']){
                                        $class = 'bg-danger';
                                        if(!isset($marcado)) {
                                            $checked = 'checked';
                                            $disabled = 'disabled';
                                            $marcado = 'ok';
                                            $valor = $key;
                                        }
                                    }
                                    
                                    $attr = '';
                                    if ($key == 0) {
                                        $attr = 'checked disabled';
                                        $pagoAcumulado = $data['pago_acumulado']; 
                                        $pagoSugerido = $data['pago_sugerido'];
                                        $abonoReal = $data['abono_real'];
                                    }

                                    ?>
                                    <tr>
                                        <td style="border: none;">
                                            <div class="checkbox checkbox-table">
                                                <label>
                                                    <input cuota="<?=$data['cuota']?>" pagoAcumulado="<?=$data['pago_acumulado']?>"  pagoSugerido="<?=$data['pago_sugerido']?>"  
                                                        abonoReal="<?=$data['abono_real']?>"class="aceptar_oferta check_aceptar" type="checkbox"  value="<?= $key ?>" <?= $attr ?>>
                                                    <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td class="<?= $class ?>"><?= $this->Number->Currency($data['cuota'], null, ['precision' => 0]) ?></td>
                                        <td><?= number_format(round($data['tasa'], 1),1) ?>%</td>
                                        <td><?= $data['plazo'] ?></td>
                                    </tr>
                                <?php
                                endforeach;
                            endif;
                            ?>
                            </tbody>
                        </table>

                        <div class="abonos" style="margin-left: 40px; font-size: 12px;">
                        <b>Pago sugerido:</b>
                            <input type="hidden" name="totalPagoAcomuladoN" value="<?= $pagoAcumulado ?>" id="total_pago_acomulado_n">
                         <span id="pago_sugerido_unificacion"><?= $this->Number->Currency($pagoSugerido, null, ['precision' => 0]) ?></span>
                            <br>
                            <br>
                            <?=$this->Form->input('payment_agreed_unificacion',
                                [
                                    'label' =>  __('Abono Real').':',
                                    'class' => 'div-false finalizar input-sm money pago_real',
                                    'style'=>'width: 55%;',
                                    'templates' => [
                                        'inputContainer' => '{{content}}'
                                    ],
                                    'value'=> $abonoReal,
                                    'required' => 'required',
                                    'id' => 'abono_real_unificacion',
                                    'type' => 'text'
                                ]
                            );?>
                        </div>

                    </div>
                </div>
                <div class="div-separador"></div>

            <?php if(isset($normalizacion['ind_escalonada']) && $normalizacion['ind_escalonada'] == 1): ?>
                <script>
                    bootbox.alert('Oferta de normalización escalonada.');
                </script>
            <?php endIf; ?>
            <?php endIf; ?>


        </div>
    </div>
<?php endif; ?>


<?php if(!empty($ofertasVehiculo)): ?>
    <div class="row div-separador">
        <fieldset class="container scheduler-border" style="padding: 0 1.4em 1.4em 50em !important">
            <legend class="scheduler-border">Alternativas de Solución vehículo</legend>
        </fieldset>

        <div class="col-md-7">
            <div style="border: 1px solid #000; padding: 8px; border-radius: 8px">
                <div class="table-responsive table-info">
                    <table class="table table-hover">
                        <thead class="thead-default">
                        <tr class="table-danger">
                            <!--<th style="background-color: #fff; border: none !important; color: #ed1c27"><?#=__('Acepta');?></th>-->
                            <th><?=__('Tipo')?></th>
                            <th><?=__('Obligación')?></th>
                            <th><?=__('Alternativa de Negociación')?></th>
                            <th><?=__('Plazo')?></th>
                            <th><?=__('Nueva Cuota')?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        /** @var  $obligacion \App\Model\Entity\Obligation*/
                        foreach($ofertasVehiculo as $obligacion):
                            $tipo = $obligacion->type_obligation_id;
                            $attr='';
                            if(in_array($tipo,[\App\Model\Table\TypeObligationsTable::CXR,\App\Model\Table\TypeObligationsTable::TDC])){
                                $attr = 'checked disabled';
                            }
                            ?>
                            <tr>
                                <!--<td style="border: none;">-->
                                <?php #if($obligacion->estrategia != 1 && $obligacion->negociable == 0): ?>
                                <!--<div class="checkbox checkbox-table">
                                <label>
                                    <input name="obligacion[<?=$obligacion->id?>][acepta]" class="finalizar" type="checkbox"  value="1" <?#=$attr?>>
                                    <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                </label>
                            </div>-->
                                <?php #endIf; ?>
                                <!--</td>-->
                                <td><?=$obligacion->type_obligation->type?></td>
                                <td><?=$obligacion->maskobligation?></td>
                                <td><?=$obligacion->estrategias[$obligacion->estrategia]?></td>
                                <td><?=$obligacion->nuevoPlazo?></td>
                                <td><?=$this->Number->Currency($obligacion->nuevaCuota, null, ['precision' => 0])?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="div-separador"></div>

            <div class="row">
                <div class="col-md-10 abonos">


                </div>
            </div>
        </div>
        <div class="col-md-5">
        <?php if(isset($vehiculo) && !empty($vehiculo)): ?>
            <div style="border: 1px solid #000; padding: 8px; border-radius: 8px">
                <h5 style="text-align: center; color: #ed1c27"><?=__('Pagos vehículo')?></h5>
                <div class="table-responsive table-info">
                    <table class="table table-responsive table-bordered table-hover">
                        <thead class="thead-default">

                        </thead>
                        <tbody>
                        <tr>
                            <th><?= __('Valor a pagar') ?></th>
                            <td><?= $this->Number->Currency($vehiculo['total_payment'], null, ['precision' => 0]) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Condonación') ?></th>
                            <td><?= round($vehiculo['condonation']*100,1) ?>%</td>
                        </tr>
                        <tr>
                            <th><?= __('Valor condonación') ?></th>
                            <td><?= $this->Number->Currency($vehiculo['value_condonation'], null, ['precision' => 0]) ?></td>
                        </tr>

                        <tr>
                            <td colspan="2"></td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php if(!empty($ofertasCastigada)): ?>
    <div class="row div-separador">
        <fieldset class="container scheduler-border" style="padding: 0 1.4em 1.4em 50em !important">
            <legend class="scheduler-border">Alternativas de Solución Castigada</legend>
        </fieldset>
        <div class="text-center">
            <h5><span style="color: #ed1c27">Disponible para negociación:</span> <?=$this->Number->Currency($disponibleCastigada, null, ['precision' => 0])?></h5>
        </div>

        <div class="col-md-6">
            <div style="border: 1px solid #000; padding: 8px; border-radius: 8px">
                <div class="table-responsive table-info">
                    <table class="table table-hover">
                        <thead class="thead-default">
                        <tr class="table-danger">
                            <!--<th style="background-color: #fff; border: none !important; color: #ed1c27"><?#=__('Acepta');?></th>-->
                            <th><?=__('Tipo')?></th>
                            <th><?=__('Obligación')?></th>
                            <th><?=__('Alternativa de Negociación')?></th>
                            <th><?=__('Plazo')?></th>
                            <th><?=__('Nueva Cuota')?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        /** @var  $obligacion \App\Model\Entity\Obligation*/
                        foreach($ofertasCastigada as $obligacion):?>
                            <tr>
                                <td><?=$obligacion->type_obligation->type?></td>
                                <td><?=$obligacion->maskobligation?></td>
                                <td><?=$obligacion->estrategias[$obligacion->estrategia]?></td>
                                <td><?=$obligacion->nuevoPlazo?></td>
                                <td><?=$this->Number->Currency($obligacion->nuevaCuota, null, ['precision' => 0])?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="div-separador"></div>

        </div>
        <?php if(!empty($cuotasCastigada)):
            if(count($cuotasCastigada) == 1){
                $text = 'Pago total';
            }else{
                $text = 'Unificación Productos';
            }
            ?>
            <div class="col-md-6">

                <div style="border: 1px solid #000; padding: 8px; border-radius: 8px">
                    <h5 style="text-align: center; color: #ed1c27"><?php echo $text; ?></h5>
                    <div>
                        <div class="table-responsive table-info">
                            <table class="table table-hover">
                                <thead class="thead-default">

                                <tr class="table-danger">
                                    <th style="background-color: #fff; border: none !important; color: #ed1c27">
                                        <?=__('Acepta');?>
                                    </th>
                                    <th><?=__('Cuota')?></th>
                                    <th><?=__('Plazo')?></th>
                                    <th><?=__('% Cond I.')?></th>
                                    <th><?=__('Valor condonación I.')?></th>
                                    <th><?=__('% Cond.')?></th>
                                    <th><?=__('Valor condonación.')?></th>
                                    <th><?=__('Pago inicial.')?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php 
                                foreach ($cuotasCastigada as   $key => $cuota):
                                    $attr = '';
                                    if ($key == 0) {
                                        $attr = 'checked disabled';
                                    }
                                ?>
                                    <tr>
                                        <td style="border: none;">
                                            <div class="checkbox checkbox-table">
                                                <label>
                                                    <input cuota="" class="aceptar_oferta_castigada check_aceptar" type="checkbox"
                                                           value="<?php echo $key; ?>" <?= $attr ?>>
                                                    <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td class=""><?=$this->Number->Currency($cuota['cuota'], null, ['precision' => 0])?></td>
                                        <td><?=$cuota['plazo']?></td>
                                        <td><?=round($cuota['condonacion_inicial']*100,0,PHP_ROUND_HALF_DOWN)?>%</td>
                                        <td class=""><?=$this->Number->Currency($cuota['valor_condonacion_inicial'], null, ['precision' => 0])?></td>
                                        <td><?=round($cuota['condonacion']*100,0,PHP_ROUND_HALF_DOWN)?>%</td>
                                        <td class=""><?=$this->Number->Currency($cuota['valor_condonacion'], null, ['precision' => 0])?></td>
                                        <td class=""><?=$this->Number->Currency($cuota['pago_inicial'], null, ['precision' => 0])?></td>
                                    </tr>

                                <?php
                                endforeach;
                                ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

                <?php if(isset($normalizacion['ind_escalonada']) && $normalizacion['ind_escalonada'] == 1): ?>
                    <script>
                        bootbox.alert('Oferta de normalización escalonada.');
                    </script>
                <?php endIf; ?>
            </div>
        <?php endif; ?>


    </div>
<?php endif; ?>

<div class="text-center div-separador">
    <?php
    if(isset($normalizacion['data']) && !empty($normalizacion['data'])) {
        if (is_null($valor)) {
            $valor = 0;
        }
        echo $this->Form->input('propuesta_aceptada',
            [
                'value' => $valor,
                'id' => 'propuesta_aceptada',
                'type' => 'hidden',
                'class' => 'finalizar'
            ]
        );
    }

    if(isset($cuotasCastigada) && !empty($cuotasCastigada)) {
        echo $this->Form->input('propuesta_aceptada_castigada',
            [
                'value' => 0,
                'id' => 'propuesta_aceptada_castigada',
                'type' => 'hidden',
                'class' => 'finalizar'
            ]
        );
    }

    ?>
    <?php if($comite == true){

        echo $this->Form->input('comite',
            [
                'value'=>1,
                'type' => 'hidden',
                'class' => 'finalizar'
            ]
        );

        echo  $this->Form->button($this->Html->icon('check').__('Acepta comite'), ['update'=>'#resumen','id' => 'aceptar-oferta', 'type'=>'submit','class' => 'btn btn-primary aceptar_oferta', 'div' => false]);
    }else{
        echo  $this->Form->button($this->Html->icon('check').__('Acepta'), ['update'=>'#resumen','id' => 'aceptar-oferta', 'type'=>'submit','class' => 'btn btn-primary aceptar_oferta', 'div' => false]);
    }
    echo  $this->Form->button($this->Html->icon('times').__('Rechaza'), ['type'=>'button', 'id' => 'rechazar-oferta', 'class' => 'btn btn-primary', 'div' => false]);
    ?>

</div>

<div class="div-separador-2x"></div>

<?= $this->Form->end() ?>

<script>
    $('#plazo').mask('000.000.000.000.000', {reverse: true});
    //$('#payment-agreed').mask('000.000.000.000.000', {reverse: true});
    $('.money').mask('000.000.000.000.000', {reverse: true});
</script>
