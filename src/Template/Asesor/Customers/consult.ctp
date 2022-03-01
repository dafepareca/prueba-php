<?php
/**
 * Created by PhpStorm.
 * User: walter
 * Date: 21/06/17
 * Time: 02:46 PM
 */

/** @var  $customer \App\Model\Entity\Customer*/
?>

<!-- Main jumbotron for a primary marketing message or call to action -->
<div class="fijo content-informacion collapseInfo collapse in">
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                <?= $this->Form->create('', [
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
                            <td><?= $this->Form->button($this->Html->icon('play'), ['class' => 'btn-xs btn btn-primary', 'type' => 'submit', 'div' => false]) ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <?= $this->Form->end() ?>

                <div class="row">
                    <!--                    <button class="btn-sm center-block input-danger"></button>-->
                    <div class="col-sm-12 ingresos">

                        <?php
                        if(isset($customer->history_customers) && !empty($customer->history_customers)){
                            $disabled = false;
                        } else{
                            $disabled = true;
                        }

                        ?>
                        <?= $this->Form->button($this->Html->icon('file-text-o').__('HISTORIAL CLIENTE'),
                            [
                                'id'=>'historial_cliente',
                                'class' => 'input-danger btn-sm center-block btn btn-defaul',
                                'type' => 'button',
                                'div' => false,
                                'disabled' => $disabled
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
                                    'type' => 'number',
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
                                    'type' => 'number',
                                    'class' => 'finalizar evaluar input-sm no-spin'
                                ]
                            ) ?>
                        </div>
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
                        'url' => ['controller' => 'customer', 'action' => 'update']
                    ]) ?>
                    <?=$this->Form->control('id',['type' => 'hidden'])?>
                    <div class="col-xs-5">
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">
                                <?=$this->Html->icon('user');?>
                            </span>
                            <?= $this->Form->input('name',
                                array(
                                    'type'=>'text',
                                    'required',
                                    'disabled',
                                    'label' => false,
                                    'autocomplete' => 'off'
                                )
                            ) ?>
                        </div>
                    </div>
                    <div class="col-xs-5">
                        <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1">
                                        <?=$this->Html->icon('envelope');?>
                                    </span>
                            <?= $this->Form->input('email',
                                array(
                                    'placeholder' => __(''),
                                    'type'=>'email',
                                    'required',
                                    'disabled',
                                    'label' => false,
                                    'autocomplete' => 'off'
                                )
                            ) ?>
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <button type="button" id="btn-editar" class="input-danger btn-sm center-block btn btn-defaul editar-datos"><?=$this->Html->icon('pencil');?> <?=__('editar')?></button>
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
                                                <tr class="bg-black">
                                                    <td class="n-total"><span class="span-white">$0</span></td>
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
   <div style="height: 230px">

   </div>
</div>



<div class="container container-detalle">
    <!-- Example row of columns -->
    <div class="row">
        <?php if(isset($comite)):
            $mensaje = '';
            if($comite->history_status_id == 2 ){
                $mensaje = 'Negociación pendiente comite.';
            }elseif($comite->history_status_id == 5){
                $mensaje = 'Negociación Aceptada por comite.';
            }elseif($comite->history_status_id == 6){
                $mensaje = 'Negociación rechazada por comite.';
            }
            ?>
            <div class="text-center">
                <h3><span style="color: #fff" class="label label-default"><?=$mensaje?></span></h3>
            </div>
        <?php endif; ?>
        <?php if(!empty($obligations)): ?>
            <?php if(count($obligations) > 0):
                ?>
                <div class="col-sm-12">
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border"><?=__('Detalle de Productos')?></legend>
                    </fieldset>

                    <div class="table-responsive table-info">
                        <table class="table table-hover">
                            <thead class="thead-default">
                            <tr class="table-danger">
                                <td style="background-color: #fff; border: none !important;">
                                    <div style="color: #0c0c0c" class="checkbox checkbox-table">
                                        <label>
                                            <input class="obligaciones checkbox" id="select-all"  type="checkbox" value="">
                                            <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                        </label>
                                    </div>
                                </td>
                                <th><?=__('Tipo')?></th>
                                <th><?=__('Obligación')?></th>
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
                                $tipo = $obligation->type_obligation_id;
                                $attr='';
                                $class = 'select-all';
                                if(in_array($tipo,[\App\Model\Table\TypeObligationsTable::CXR,\App\Model\Table\TypeObligationsTable::TDC])){
                                    $attr = 'checked disabled';
                                    $class = '';
                                    echo $this->Form->input('marca[]', ['class' => 'evaluar', 'value'=>$obligation->id, 'type' => 'hidden']);
                                }
                                ?>
                                <tr>
                                    <td style="border: none;">
                                        <div class="checkbox checkbox-table">
                                            <label>
                                                <input class="obligaciones checkbox evaluar <?=$class?>" name="marca[]" type="checkbox" value="<?=$obligation->id?>" <?=$attr?>>
                                                <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                            </label>
                                        </div>
                                    </td>
                                    <td><?=$obligation->type_obligation->type?></td>
                                    <td><?=$obligation->obligation?></td>
                                    <td><?=$this->Number->Currency($obligation->total_debt, null, ['precision' => 0]) ?></td>
                                    <td><?=$this->Number->Currency($obligation->fee, null, ['precision' => 0]) ?></td>
                                    <td><?=$this->Number->Currency($obligation->minimum_payment, null, ['precision' => 0]) ?></td>
                                    <td><?=$obligation->days_past_due ?></td>
                                    <td><?=$obligation->rate ?>%</td>
                                    <td><?=$obligation->tasaMensual ?>%</td>
                                    <td><?=$obligation->date_cut->format('Y-m-d') ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div style="margin-bottom: 30px;" class="text-center">
                        <?= $this->Form->button(__('Continuar'), ['id' => 'continuar', 'update'=>'#oferta', 'class' => 'btn btn-primary continuar', 'div' => false]) ?>
                    </div>
                </div>
            <?php endIf; ?>
        <?php else :?>
            <div class="container">
                <?= $this->element('header_home');?>
                <?= $this->Flash->render(); ?>
                <?= $this->Flash->render('auth'); ?>
                <div id="page-content-wrapper" class="page-content-wrapper">
                    <?php echo $this->fetch('content'); ?>
                    <?php echo $this->element('modal-transacciones'); ?>
                </div>
            </div><!-- /.Container -->
        <?php endIf; ?>

        <div id="oferta">

        </div>

        <div id="resumen">

        </div>

    </div>
</div> <!-- /container -->

<?= $this->element('history_customer'); ?>
