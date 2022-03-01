<?php
/**
 * Created by PhpStorm.
 * User: walter
 * Date: 21/06/17
 * Time: 02:46 PM
 */

/** @var  $customer \App\Model\Entity\Customer*/
/** @var \App\View\AppView $this */
?>

<!-- Main jumbotron for a primary marketing message or call to action -->
<div class="fijo content-informacion collapseInfo collapse in">
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                <?= $this->Form->create('', [
                    'id' => 'form-consulta',
                    'class' => 'form-ajax',
                    'update' => '#page-content-wrapper',
                    'horizontal' => false,
                    'templates' => [
                        'inputContainer' => '{{content}}'
                    ],
                ]) ?>

                <div>
                    <table width="100%">
                        <thead>
                        <tr>
                            <th></th>
                            <th style="text-align: left"><?=__('Tipo Documento')?></th>
                            <th style="text-align: left"><?=__('Número Documento')?></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><?= $this->Form->button($this->Html->icon('undo'), ['onclick' => 'javascrip:location.reload()','class' => 'btn-xs btn btn-primary', 'type' => 'button', 'div' => false]) ?></td>
                            <td><?= $this->Form->input('customer_type_identification_id',['label'=>false,'class' => 'input-danger input-sm', 'options' => $typeIdentifications, 'required']) ?></td>
                            <td><?= $this->Form->input('identification', array('label'=>false,'required', 'class' => 'input-danger input-sm')) ?></td>
                            <td><?= $this->Form->button($this->Html->icon('play'), ['id'=> 'boton-consulta','class' => 'btn-xs btn btn-primary', 'type' => 'submit', 'div' => false]) ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <?= $this->Form->end() ?>

                <div class="row">
                    <!--                    <button class="btn-sm center-block input-danger"></button>-->
                    <div class="col-sm-12 ingresos">
                        <?= $this->Form->button($this->Html->icon('file-text-o').__('HISTORIAL CLIENTE'),
                            [
                                'id'=>'historial_cliente',
                                'class' => 'input-danger btn-sm center-block btn btn-defaul',
                                'type' => 'button',
                                'div' => false,
                                'disabled' => false
                            ]
                        ) ?>

                        <div class="input-group">
                                    <span class="span-input-red input-group-addon" id="basic-addon1">
                                        <?=__('Deuda Actual')?>
                                    </span>
                            <?= $this->Form->input('deuda_total',
                                [
                                    'placeholder' => __(''),
                                    'required','disabled',
                                    'label' => false,
                                    'class' => 'input-sm',
                                    'value'=> $this->Number->Currency($totales['total']['total'], null, ['precision' => 0]),
                                ]
                            ) ?>
                        </div>

                        <div class="input-group">
                                    <span class="span-input-red input-group-addon" id="basic-addon1">
                                        <?=__('Ingreso Actual')?>
                                    </span>
                            <?= $this->Form->input('customer.income',
                                [
                                    'placeholder' => __(''),
                                    'required',
                                    'label' => false,
                                    'type' => 'text',
                                    'class' => 'finalizar evaluar input-sm no-spin'
                                ]
                            ) ?>
                        </div>

                        <div class="input-group">
                                    <span class="span-input-red input-group-addon" id="basic-addon1">
                                        <?=__('Capacidad de Pago')?>
                                    </span>
                            <?= $this->Form->input(
                                'customer.payment_capacity',
                                [
                                    'placeholder' => __(''),
                                    'required',
                                    'label' => false,
                                    'type' => 'text',
                                    'class' => 'finalizar evaluar input-sm no-spin'
                                ]
                            ) ?>

                            <?= $this->Form->input(
                                'customer.income_source',
                                [
                                    'id' => 'check_ingresos',
                                    'required',
                                    'label' => false,
                                    'type' => 'hidden',
                                    'class' => 'finalizar evaluar',
                                    'value' => '1'
                                ]
                            ) ?>

                            <?= $this->Form->input(
                                'customer.valor_total_castigada',
                                [
                                    'id' => 'valor_total_castigada',
                                    'required',
                                    'label' => false,
                                    'type' => 'hidden',
                                    'class' => 'finalizar evaluar',
                                    'value' => $totales['total_castigada']['total']
                                ]
                            ) ?>

                            <?= $this->Form->input(
                                'customer.valor_total_vigente',
                                [
                                    'id' => 'valor_total_vigente',
                                    'required',
                                    'label' => false,
                                    'type' => 'hidden',
                                    'class' => 'finalizar evaluar',
                                    'value' => $totales['total_vigente']['total']
                                ]
                            ) ?>
                        </div>

                        <?php if($castigadaConsumo): ?>
                        <div class="checkbox" style="display: inline-block">
                            <label style="font-size: 1.2em">
                                <input name="pago_total_castigada" id="pago_total_consumo_castigada" class="finalizar evaluar check_pago_total" type="checkbox" value="1">
                                <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                <?=__('Pago Total Consumo Castigada')?>
                            </label>
                        </div>
                        <?php endif; ?>

                        <?php if($totales['vehiculo']['total'] > 0): ?>
                            <div class="checkbox" style="display: inline-block">
                                <label style="font-size: 1.2em">
                                    <input name="pago_total_vehiculo" class="check_pago_total_vehiculo finalizar evaluar" type="checkbox" value="1">
                                    <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                    <?=__('Pago total vehiculo')?>
                                </label>
                            </div>
                        <?php endif; ?>

                        <?php if($castigadaConsumo): ?>
                        <div class="input-group">
                                    <span class="text_pago_consumo_castigada span-input-red input-group-addon" id="basic-addon1">
                                        <?=__('Pago Inicial Consumo Castigada')?>
                                    </span>
                            <?= $this->Form->input(
                                'customer.initial_payment_punished',
                                [
                                    'placeholder' => __(''),
                                    'required',
                                    'label' => false,
                                    'type' => 'text',
                                    'class' => 'finalizar evaluar input-sm no-spin'
                                ]
                            ) ?>
                        </div>
                        <?php endif; ?>

                        <?php if($castigadaHp): ?>
                            <div class="input-group">
                                    <span class="text_pago_hipotecario_castigada span-input-red input-group-addon" id="basic-addon1">
                                        <?=__('Pago Inicial Hipotecario Castigado')?>
                                    </span>
                                <?= $this->Form->input(
                                    'customer.payment_punished_hp',
                                    [
                                        'placeholder' => __(''),
                                        'required',
                                        'label' => false,
                                        'type' => 'text',
                                        'class' => 'finalizar evaluar input-sm no-spin'
                                    ]
                                ) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="checkbox" style="display: inline-block">
                            <label style="font-size: 1.2em">
                                <input class="finalizar check_ingresos evaluar" disabled checked type="checkbox" value="1">
                                <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                <?=__('Ingreso Propio')?>
                            </label>

                            <label style="font-size: 1.2em; display: inline-block">
                                <input class="finalizar check_ingresos evaluar" type="checkbox" value="2">
                                <span  style="display: inline-block" class="cr"><i class="cr-icon fa fa-check"></i></span>
                                <p  style="display: inline-block"><?=__('Ingreso Terceros')?></p>
                            </label>

                            <label style="font-size: 1.2em">
                                <input class="finalizar check_ingresos evaluar" type="checkbox" value="3">
                                <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                <?=__('Mixto')?>
                            </label>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="row datos">
                    <?= $this->Form->create($customer, [
                        'class' => 'actualizar-datos',
                        'horizontal' => false,
                        'url' => ['controller' => 'customers', 'action' => 'update',(!empty($customer))?$customer->id:'']
                    ]) ?>
                    <?=$this->Form->control('id',['type' => 'hidden'])?>
                    <div class="col-xs-5">
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">
                                <?=$this->Html->icon('user');?>
                            </span>
                            <?= $this->Form->control('name',
                                array(
                                    'type'=>'text',
                                    'required',
                                    'disabled' => 'disabled',
                                    'label' => false,
                                    'autocomplete' => 'off',
                                    'id' => 'name-disabled'
                                )
                            ) ?>
                        </div>
                    </div>
                    <div class="col-xs-5">
                        <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1">
                                        <?=$this->Html->icon('envelope');?>
                                    </span>
                            <?= $this->Form->control('email',
                                array(
                                    'placeholder' => __('Email'),
                                    'type'=>'email',
                                    'required',

                                    'label' => false,
                                    'autocomplete' => 'off'
                                )
                            ) ?>
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <?=$this->Form->button(
                            $this->Html->icon('pencil').' '.__('editar'),
                            [
                                'class' => 'input-danger btn-sm center-block btn btn-defaul editar-datos',
                                'id' => 'btn-editar',
                                'type' => 'button'
                            ])?>
                    </div>
                    <?= $this->Form->end() ?>
                </div>

                <div style="margin-top: 12px" class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table width="100%">
                                <tbody>
                                <tr>
                                    <td>
                                        <div class="table-responsive">
                                            <table class="table table-responsive table-bordered">
                                                <thead class="thead-default">
                                                <tr class="danger">
                                                    <th ><?= __('Productos') ?></th>
                                                    <th class=""><span class="span-white"><?= __('Saldo Total') ?></span></th>
                                                    <th class=""><span class="span-white"><?= __('Pagos minimos') ?></span></th>
                                                    <th class=""><span class="span-white"><?= __('Cuotas pactadas') ?></span></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td class="danger">
                                                        <p><?=$this->Html->icon('home');?> <?=__('Hipotecario')?></p>
                                                    </td>
                                                    <td><?= $this->Number->Currency($totales['hipotecario']['total'], null, ['precision' => 0]) ?></td>
                                                    <td><?= $this->Number->Currency($totales['hipotecario']['minimos'], null, ['precision' => 0]) ?></td>
                                                    <td><?= $this->Number->Currency($totales['hipotecario']['cuotas'], null, ['precision' => 0]) ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="danger">
                                                        <p><?=$this->Html->icon('car');?> <?=__('Vehículo')?></p>
                                                    </td>
                                                    <td><?= $this->Number->Currency($totales['vehiculo']['total'], null, ['precision' => 0]) ?></td>
                                                    <td><?= $this->Number->Currency($totales['vehiculo']['minimos'], null, ['precision' => 0]) ?></td>
                                                    <td><?= $this->Number->Currency($totales['vehiculo']['cuotas'], null, ['precision' => 0]) ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="danger">
                                                        <p><?=$this->Html->icon('credit-card');?> <?=__('Rotativos')?></p>
                                                    </td>
                                                    <td><?= $this->Number->Currency($totales['rotativos']['total'], null, ['precision' => 0]) ?></td>
                                                    <td><?= $this->Number->Currency($totales['rotativos']['minimos'], null, ['precision' => 0]) ?></td>
                                                    <td><?= $this->Number->Currency($totales['rotativos']['cuotas'], null, ['precision' => 0]) ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="danger">
                                                        <p><?=$this->Html->icon('usd');?> <?=__('Fijos')?></p>
                                                    </td>
                                                    <td><?= $this->Number->Currency($totales['fijos']['total'], null, ['precision' => 0]) ?></td>
                                                    <td><?= $this->Number->Currency($totales['fijos']['minimos'], null, ['precision' => 0]) ?></td>
                                                    <td><?= $this->Number->Currency($totales['fijos']['cuotas'], null, ['precision' => 0]) ?></td>
                                                </tr>
                                                <tr class="bg-gray">
                                                    <td>
                                                        <p><?=__('Total Vigente')?></p>
                                                    </td>
                                                    <td><?= $this->Number->Currency($totales['total_vigente']['total'], null, ['precision' => 0]) ?></td>
                                                    <td><?= $this->Number->Currency($totales['total_vigente']['minimos'], null, ['precision' => 0]) ?></td>
                                                    <td><?= $this->Number->Currency($totales['total_vigente']['cuotas'], null, ['precision' => 0]) ?></td>
                                                </tr>

                                                <tr class="bg-gray">
                                                    <td>
                                                        <p><?=__('Total Castigada')?></p>
                                                    </td>
                                                    <td><?= $this->Number->Currency($totales['total_castigada']['total'], null, ['precision' => 0]) ?></td>
                                                    <td><?= $this->Number->Currency($totales['total_castigada']['minimos'], null, ['precision' => 0]) ?></td>
                                                    <td><?= $this->Number->Currency($totales['total_castigada']['cuotas'], null, ['precision' => 0]) ?></td>
                                                </tr>

                                                <tr class="bg-black">
                                                    <td>
                                                        <p><?=__('Total')?></p>
                                                    </td>
                                                    <td><?= $this->Number->Currency($totales['total']['total'], null, ['precision' => 0]) ?></td>
                                                    <td><?= $this->Number->Currency($totales['total']['minimos'], null, ['precision' => 0]) ?></td>
                                                    <td><?= $this->Number->Currency($totales['total']['cuotas'], null, ['precision' => 0]) ?></td>
                                                </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                    <td style="width: 40px"></td>
                                    <td>
                                        <div class="table-responsive">
                                            <table class="table table-responsive table-bordered">
                                                <thead class="thead-default">
                                                <tr class="danger">
                                                    <th><span class="span-black"><?= __('Nuevas Cuotas') ?></span></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td class="n-hipotecario">$0</td>
                                                </tr>
                                                <tr>
                                                    <td class="n-vehiculo">$0</td>
                                                </tr>
                                                <tr>
                                                    <td class="n-rotativos">$0</td>
                                                </tr>
                                                <tr>
                                                    <td class="n-fijos">$0</td>
                                                </tr>
                                                <tr class="bg-gray">
                                                    <td class="n-total"><span class="span-white">$0</span></td>
                                                </tr>
                                                <tr class="bg-gray">
                                                    <td class="n-castigada"><span class="span-white">$0</span></td>
                                                </tr>
                                                <tr class="bg-black">
                                                    <td class="n-total-f"><span class="span-white">$0</span></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>


<div class="collapse in collapseInfo">
    <div style="height: 150px">

    </div>
</div>



<div class="container container-detalle">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="table-responsive">
                <table class="table table-responsive" style="border-radius: 5px !important;border-spacing: 2px;border-collapse: separate;">
                    <tbody>
                        <?php if (isset($customer->recuperator_name) && !empty($customer->recuperator_name)){ ?>
                        <tr>
                            <th class="danger" style="font-size:11px; width: 190px;border-radius: 5px 0 0 5px;border: none;padding: 6px 6px 6px 22px !important;text-align: left;"><i class="fa fa-info-circle" style="margin-right: 8px;" aria-hidden="true"></i> Nombre Recuperador</th>
                            <td style="border-radius: 0 5px 5px 0;border: 1px solid #ddd;border-left: 0;"><?= $customer->recuperator_name ?></td>
                        </tr>
                        <?php } ?>
                        <?php if (isset($customer->alternatives) && !empty($customer->alternatives)){ ?>
                        <tr>
                            <th class="danger" style="font-size:11px; width: 190px;border-radius: 5px 0 0 5px;border: none;padding: 6px 6px 6px 22px !important;text-align: left;"><i class="fa fa-home" style="margin-right: 8px;" aria-hidden="true"></i> Alternativa</th>
                            <td style="border-radius: 0 5px 5px 0;border: 1px solid #ddd;border-left: 0;"><?= $customer->alternatives ?></td>
                        </tr>
                        <?php } ?>
                        <?php if (isset($customer->observations) && !empty($customer->observations)){ ?>
                        <tr>
                            <th class="danger" style="font-size:11px; width: 190px;border-radius: 5px 0 0 5px;border: none;padding: 6px 6px 6px 22px !important;text-align: left;"><i class="fa fa-list-ul" style="margin-right: 8px;" aria-hidden="true"></i> Observaciones</th>
                            <td style="border-radius: 0 5px 5px 0;border: 1px solid #ddd;border-left: 0;"><?= $customer->observations ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Example row of columns -->
    <div class="row">

        <div class="text-center" style="margin-bottom: 36px;">
        <?php if(isset($state)): ?>
            <?php
                $continuar = true;
                if($state == \App\Model\Table\HistoryStatusesTable::PENDIENTE) {?>
                    <h3><span style="color: #fff" class="label label-danger"><?=__('Pediente Negociación')?></span></h3>
                <?php }elseif ($state == \App\Model\Table\HistoryStatusesTable::RECHAZADA){?>
                    <h3><span style="color: #fff" class="label label-success"><?=__('Pediente Negociación')?></span></h3>
                <?php }elseif ($state == \App\Model\Table\HistoryStatusesTable::CONSULTA){?>
                    <h3><span style="color: #fff" class="label label-success"><?=__('Pediente Negociación')?></span></h3>
                <?php }elseif ($state == \App\Model\Table\HistoryStatusesTable::COMITE){
                    $continuar = false;
                    ?>
                    <h3><span style="color: #fff" class="label label-warning"><?=__('Pediente Comite')?></span></h3>
                <?php }elseif ($state == \App\Model\Table\HistoryStatusesTable::ACEPTADA){?>
                    <h3><span style="color: #fff" class="label label-success"><?=__('Con Negociación')?></span></h3>
                <?php } ?>
        <?php endif; ?>

        </div>

        <?php if(count($obligations) > 0):
            $unificarConsumo = false;
            ?>
            
            <div class="col-sm-12">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border"><?=__('Detalle de Productos')?></legend>
                </fieldset>

                <div class="table-responsive table-info">
                    <table class="table table-hover">
                        <thead class="thead-default">
                        <tr class="table-danger">
                            <?php if($continuar): ?>
                                <td style="background-color: #fff; border: none !important;">
                                    <div style="color: #0c0c0c" class="checkbox checkbox-table">
                                        <label>
                                            <input class="checkbox" id="select-all"  type="checkbox" value="">
                                            <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                        </label>
                                    </div>
                                </td>
                            <?php endif; ?>
                            <th><?=__('Tipo')?></th>
                            <th><?=__('Obligación')?></th>
                            <th><?=__('Circular')?></th>
                            <th><?=__('Campaña')?></th>
                            <th><?=__('Compañía')?></th>
                            <th><?=__('Saldo total')?></th>
                            <th><?=__('Cuota')?></th>
                            <th><?=__('Pago mínimo')?></th>
                            <th><?=__('Días mora')?></th>
                            <th><?=__('Tasa EA')?></th>
                            <th><?=__('Tasa EM')?></th>
                            <th><?=__('Fecha de corte')?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        /** @var  $obligation \App\Model\Entity\Obligation*/
                        foreach($obligations as $obligation):
                            if($obligation->esConsumo()){
                                $unificarConsumo = true;
                            }
                            $tipo = $obligation->type_obligation_id;
                            $attr='';
                            $class = 'select-all';
                            if(in_array($tipo,[\App\Model\Table\TypeObligationsTable::CXR,\App\Model\Table\TypeObligationsTable::TDC])){
                                $attr = 'checked';
                                $class = '';
                                #echo $this->Form->input('marca[]', ['class' => 'evaluar', 'value'=>$obligation->id, 'type' => 'hidden']);
                            }
                            ?>
                            <tr>
                                <?php if($continuar): ?>
                                    <td style="border: none;">
                                        <?php if(!$obligation->restriccion): ?>
                                            <div class="checkbox checkbox-table">
                                                <label>
                                                    <input data-type-obligation="<?= $obligation->type_obligation_id ?>" data-company-obligation="<?= $obligation->company ?>" class="obligaciones checkbox evaluar <?=$class?>" name="marca[]" type="checkbox" value="<?=$obligation->id?>" <?=$attr?>>
                                                    <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                                </label>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                <?php endif; ?>
                                <td><?=$obligation->type_obligation->type?></td>
                                <td><?=$obligation->maskobligation?></td>
                                <td><?= $obligation->circular_026; ?></td>
                                <td><?=$obligation->campana?></td>
                                <td><?=$obligation->company?></td>
                                <td><?=$this->Number->Currency($obligation->total_debt, null, ['precision' => 0]) ?></td>
                                <td><?=$this->Number->Currency($obligation->fee, null, ['precision' => 0]) ?></td>
                                <td><?=$this->Number->Currency($obligation->minimum_payment, null, ['precision' => 0]) ?></td>
                                <td><?=$obligation->days_past_due ?></td>
                                <td><?=$obligation->rate ?>%</td>
                                <td><?=$obligation->tasaMensual ?>%</td>
                                <td><?=(!empty($obligation->minimum_payment_date))?$obligation->minimum_payment_date->format('Y-m-d'):"" ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php if($unificarConsumo): ?>
                    <div class="checkbox" style="margin-left: 20px">
                        <label style="font-size: 1.2em">
                            <input name="normalizar_consumo" class="finalizar evaluar check_normalizar_consumo" type="checkbox" value="1">
                            <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                            <?=__('Unificar consumo')?>
                        </label>
                    </div>
                    <?php endif; ?>

                </div>
                <?php

                if($customer->cndNegociar == false){
                    $continuar = false;
                }

                if($continuar): ?>



                    <div style="margin-bottom: 30px;" class="text-center">
                        <?= $this->Form->button(__('Continuar'), ['id' => 'continuar', 'update'=>'#oferta', 'class' => 'btn btn-primary continuar', 'div' => false]) ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endIf; ?>

        <div id="oferta">

        </div>

        <div id="resumen">

        </div>

    </div>
</div> <!-- /container -->

<?= $this->element('history_customer'); ?>
<?php if(!empty($customer->cndMensaje)){
    echo $this->element('modal_mensaje',['mensaje'=>$customer->cndMensaje]);
} ?>
<?= $this->element('history_customer'); ?>


<script>
    $('#customer-income').mask('000.000.000.000.000', {reverse: true});
    $('#customer-payment-capacity').mask('000.000.000.000.000', {reverse: true});
    $('#customer-initial-payment-punished').mask('000.000.000.000.000', {reverse: true});
    $('#customer-payment-punished-hp').mask('000.000.000.000.000', {reverse: true});

    
    $('#form-consulta').on('submit', event => {
        $('#boton-consulta').attr('disabled', true)

    });

   
</script>