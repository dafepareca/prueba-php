<?php
/**
 * Created by PhpStorm.
 * User: walter
 * Date: 23/06/17
 * Time: 11:28 AM
 */
?>


<div class="col-lg-10 col-lg-offset-1">

    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#tab-productos"><?=__('Productos')?></a></li>
        <li><a data-toggle="tab" href="#tab-oferta"><?=__('Oferta')?></a></li>
        <li class="tab-normalizar" style="display: none;"><a data-toggle="tab" href="#tab-normalizar"><?=__('Normalización')?></a></li>
        <li><a data-toggle="tab" href="#tab-resumen"><?=__('Resumen')?></a></li>
    </ul>

    <div class="tab-content" style="padding-top: 30px; margin-bottom: 50px;">

        <div id="tab-productos" class="tab-pane fade in active">

            <?= $this->Form->create('', [
                'id' => 'evaluar',
                'update' => '#tab-oferta',
                'horizontal' => false,
                'url' => ['controller' => 'obligations', 'action' => 'evaluar']
            ]) ?>

            <div class="row" style="padding: 5px;">
                <div class="col-lg-10 col-lg-offset-1">

                    <div class="row">
                        <div class="col-lg-2" style="padding-right: 3px">
                            <?= $this->Html->image('teclado.png', ['fullBase' => true,'style'=>'max-width: 185px']); ?>
                        </div>
                        <div class="col-lg-10 col-md-12">
                             <div class="panel panel-danger">
                        <div class="panel-heading">
                            <h3 class="panel-title"><?=__('Ingresar la siguiente información')?></h3>
                        </div>
                        <div class="panel-body">
                            <div class="col-md-6">
                                <?= $this->Form->input('customer.email', array('label'=>__('Correo'),'required','type' => 'email')) ?>
                                <?= $this->Form->input(
                                    'customer.work_activity_id',
                                    array(
                                        'label'=>__('Actividad Laboral'),
                                        'required',
                                        'options' => $workActivitys,
                                        'empty' => __('seleccionar')
                                    )
                                ) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $this->Form->input('customer.income', ['label'=>__('Ingresos'),'required','type'=>'number']) ?>
                                <?= $this->Form->input('customer.payment_capacity', ['label'=>__('Capacidad de pago'),'required','type'=>'number']) ?>
                            </div>
                        </div>
                    </div>
                         </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-2" style="padding-right: 3px">
                            <?= $this->Html->image('teclado.png', ['fullBase' => true,'style'=>'max-width: 185px']); ?>
                        </div>
                        <div class="col-lg-10 col-md-12">
                            <div class="panel panel-danger">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><?=__('Obligaciones Negociables')?></h3>
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive table-info">
                                        <table class="table table-bordered table-hover">
                                            <thead class="thead-default">
                                            <tr class="table-danger">
                                                <th><?=__('Tipo')?></th>
                                                <th><?=__('Obligación')?></th>
                                                <th><?=__('Saldo total')?></th>
                                                <th><?=__('Cuota')?></th>
                                                <th><?=__('Pago mínimo')?></th>
                                                <th><?=__('Días mora')?></th>
                                                <th><?=__('Tasa EA')?></th>
                                                <th><?=__('Tasa EM')?></th>
                                                <th><?=__('Moneda')?></th>
                                                <th><?=__('Marca')?></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $total = 0;
                                            $totalNegociable = 0;

                                            $totalHipVehi = 0;
                                            $totalNegociableHipVehi = 0;
                                            $totalHipVehiC = 0;
                                            $totalNegociableHipVehiC = 0;

                                            $totalNegociableFijos = 0;
                                            $totalFijos = 0;
                                            $totalFijosC = 0;
                                            $totalNegociableFijosC = 0;

                                            $totalNegociableCupos = 0;
                                            $totalCupos = 0;
                                            $totalCuposC = 0;
                                            $totalNegociableCuposC = 0;

                                            $totalCuotas = 0;
                                            $totalNegociableCuotas = 0;

                                            /** @var  $obligation \App\Model\Entity\Obligation*/
                                            foreach($obligations as $obligation):

                                                $total += $obligation->total_debt;
                                                $totalNegociable = $total;
                                                $totalCuotas += $obligation->fee;
                                                $totalNegociableCuotas = $totalCuotas;

                                                if(in_array($obligation->type_obligation_id,[4,5])){
                                                    $totalHipVehi += $obligation->total_debt;
                                                    $totalHipVehiC += $obligation->fee;
                                                }elseif($obligation->type_obligation_id == 3){
                                                    $totalFijos +=  $obligation->total_debt;
                                                    $totalFijosC +=  $obligation->fee;

                                                }elseif(in_array($obligation->type_obligation_id,[1,2])){
                                                    $totalCupos += $obligation->total_debt;
                                                    $totalCuposC += $obligation->fee;
                                                }

                                                $totalNegociableHipVehi = $totalHipVehi;
                                                $totalNegociableHipVehiC = $totalHipVehiC;

                                                $totalNegociableFijos = $totalFijos;
                                                $totalNegociableFijosC = $totalFijosC;

                                                $totalNegociableCupos = $totalCupos;
                                                $totalNegociableCuposC = $totalCuposC;

                                                ?>
                                                <tr>
                                                    <td><?=$obligation->type_obligation->type?></td>
                                                    <td><?=$obligation->obligation ?></td>
                                                    <td>$<?=number_format($obligation->total_debt,0,'.','.')?></td>
                                                    <td>$<?=number_format($obligation->fee,0,'.','.')?></td>
                                                    <td>$<?=number_format($obligation->minimum_payment,0,'.','.')?></td>
                                                    <td><?=$obligation->days_past_due?></td>
                                                    <td><?=$obligation->rate?>%</td>
                                                    <td><?=$obligation->tasaMensual?>%</td>
                                                    <td><?=$obligation->currency?></td>
                                                    <td>
                                                        <?php if(in_array($obligation->type_obligation_id,[1,2])){ ?>
                                                            <?= $this->Form->input(
                                                                'marca[]',
                                                                [
                                                                    'value'=>$obligation->id,
                                                                    'type' => 'checkbox',
                                                                    'class' => 'obligaciones checkbox',
                                                                    'data-fee' => $obligation->fee,
                                                                    'data-type' => $obligation->type_obligation_id,
                                                                    'data-value' => $obligation->total_debt,
                                                                    'label' => false,
                                                                    'checked' => true,
                                                                    'disabled' => true
                                                                ]
                                                            ) ?>
                                                            <?= $this->Form->input('marca[]', ['value'=>$obligation->id, 'type' => 'hidden']) ?>
                                                        <?php }else{ ?>
                                                            <?= $this->Form->input(
                                                                'marca[]',
                                                                [
                                                                    'value'=>$obligation->id,
                                                                    'type' => 'checkbox',
                                                                    'class' => 'obligaciones checkbox',
                                                                    'data-fee' => $obligation->fee,
                                                                    'data-type' => $obligation->type_obligation_id,
                                                                    'data-value' => $obligation->total_debt,
                                                                    'label' => false
                                                                ]
                                                            ) ?>

                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <?php if(count($noObligations) > 0){ ?>
                    <div class="row">
                        <div class="col-lg-2" style="padding-right: 3px">
                            <?= $this->Html->image('teclado.png', ['fullBase' => true,'style'=>'max-width: 185px']); ?>
                        </div>
                        <div class="col-lg-10 col-md-12">
                            <div class="panel panel-danger">
                        <div class="panel-heading">
                            <h3 class="panel-title"><?=__('Obligaciones No Negociables')?></h3>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive table-info">
                                <table class="table table-responsive table-bordered table-hover">
                                    <thead class="thead-default">
                                    <tr>
                                        <th><?=__('Tipo')?></th>
                                        <th><?=__('Obligación')?></th>
                                        <th><?=__('Saldo total')?></th>
                                        <th><?=__('Cuota')?></th>
                                        <th><?=__('Pago mínimo')?></th>
                                        <th><?=__('Días mora')?></th>
                                        <th><?=__('Tasa EA')?></th>
                                        <th><?=__('Moneda')?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    /** @var  $obligation \App\Model\Entity\Obligation*/
                                    foreach($noObligations as $obligation):
                                        $total+= $obligation->total_debt;
                                        if(in_array($obligation->type_obligation_id,[4,5])){
                                            $totalHipVehi += $obligation->total_debt;
                                            $totalHipVehiC += $obligation->fee;
                                        }elseif($obligation->type_obligation_id == 3){
                                            $totalFijos +=  $obligation->total_debt;
                                            $totalFijosC +=  $obligation->fee;
                                        }elseif(in_array($obligation->type_obligation_id,[1,2])){
                                            $totalCupos += $obligation->total_debt;
                                            $totalCuposC += $obligation->fee;
                                        }

                                        $totalCuotas += $obligation->fee;

                                        ?>
                                        <tr>
                                            <td><?=$obligation->type_obligation->type?></td>
                                            <td><?=$obligation->obligation ?></td>
                                            <td>$<?=number_format($obligation->total_debt,0,'.','.')?></td>
                                            <td>$<?=number_format($obligation->fee,0,'.','.')?></td>
                                            <td>$<?=number_format($obligation->minimum_payment,0,'.','.')?></td>
                                            <td><?=$obligation->days_past_due?></td>
                                            <td><?=$obligation->rate?>%</td>
                                            <td><?=$obligation->currency?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>

            <div class="text-center">

                <?= $this->Form->input('saldo_negociar', ['id'=>'saldo_negociar', 'value'=>0, 'type' => 'hidden']) ?>
                <?= $this->Form->input('saldo_total', ['id'=>'saldo_total', 'value'=>$total, 'type' => 'hidden']) ?>
                <?= $this->Form->input('saldo_total_hip_vehi', ['id'=>'saldo_total_hip_vehi', 'value'=>$totalHipVehi, 'type' => 'hidden']) ?>
                <?= $this->Form->input('saldo_total_fijos', ['id'=>'saldo_total_fijos', 'value'=>$totalFijos, 'type' => 'hidden']) ?>
                <?= $this->Form->input('saldo_total_cupos', ['id'=>'saldo_total_cupos', 'value'=>$totalCupos, 'type' => 'hidden']) ?>
                <?= $this->Form->input('cuotas_negociar', ['id'=>'cuotas_negociar', 'value'=>0, 'type' => 'hidden']) ?>
                <?= $this->Form->input('total_cuotas', ['id'=>'total_cuotas', 'value'=>$totalCuotas, 'type' => 'hidden']) ?>
                <?= $this->Form->input('cuotas_total_hip_vehi', ['id'=>'cuotas_total_hip_vehi', 'value'=>$totalHipVehiC, 'type' => 'hidden']) ?>
                <?= $this->Form->input('cuotas_total_fijos', ['id'=>'cuotas_total_fijos', 'value'=>$totalFijosC, 'type' => 'hidden']) ?>
                <?= $this->Form->input('cuotas_total_cupos', ['id'=>'cuotas_total_cupos', 'value'=>$totalCuposC, 'type' => 'hidden']) ?>

                <?= $this->Form->button(__('Continuar'), ['class' => 'btn btn-primary', 'type' => 'submit', 'div' => false]) ?>
            </div>

            <?= $this->Form->end() ?>

        </div>

        <div id="tab-oferta" class="tab-pane fade">
            <h3><?=__('Oferta')?></h3>
        </div>

        <div id="tab-normalizar" class="tab-pane fade">
            <h3><?=__('Normalización')?></h3>
        </div>

        <div id="tab-resumen" class="tab-pane fade">
            <h3><?=__('Resumen')?></h3>

        </div>

    </div>

</div>

<script>

   /* $(document).on('change', '.checkbox', function() {
        refrescarCliente();
    });

    $( document ).ready(function() {
        refrescarCliente();
    });

    function refrescarCliente(){

        var saldo_negociar = 0;
        var cuotas_negociar = 0;
        var saldo_total = parseInt($("#saldo_total").val());
        var saldo_total_hip_vehi = parseInt($("#saldo_total_hip_vehi").val());
        var saldo_total_fijos = parseInt($("#saldo_total_fijos").val());
        var saldo_total_cupos = parseInt($("#saldo_total_cupos").val());

        var cuotas_total_hip_vehi = parseInt($("#cuotas_total_hip_vehi").val());
        var cuotas_total_fijos = parseInt($("#cuotas_total_fijos").val());
        var cuotas_total_cupos = parseInt($("#cuotas_total_cupos").val());

        var total_cuotas = parseInt($("#total_cuotas").val());

        var $totalHipVehi = 0;
        var $totalHipVehiC = 0;
        var $totalFijos = 0;
        var $totalFijosC = 0;
        var $totalCupos = 0;
        var $totalCuposC = 0;



        $(".obligaciones:input:checkbox:checked").each(function() {
            var valor = parseInt($(this).attr('data-value'));
            var valor_fee = parseInt($(this).attr('data-fee'));

            saldo_negociar+=valor;
            cuotas_negociar+=valor_fee;

            var type = $(this).attr('data-type');

            if(type == 4 || type == 5){
                $totalHipVehi += valor;
                $totalHipVehiC += valor_fee;
            }
            if(type == 3){
                $totalFijos +=  valor;
                $totalFijosC +=  valor_fee;
            }
            if(type == 1 || type == 2){
                $totalCupos += valor;
                $totalCuposC += valor_fee;
            }
        });

        $("#saldo_negociar").val(saldo_negociar);
        $(".saldo_negociar").html("$"+numeral(saldo_negociar).format('0,0'));
        $(".saldo_no_negociar").html("$"+numeral(saldo_total-saldo_negociar).format('0,0'));

        $("#saldo_negociar_hip_vehi").val($totalHipVehi);
        $(".saldo_negociar_hip_vehi").html("$"+numeral($totalHipVehi).format('0,0'));
        $(".saldo_no_negociar_hip_vehi").html("$"+numeral(saldo_total_hip_vehi-$totalHipVehi).format('0,0.'));

        $("#saldo_negociar_fijos").val($totalFijos);
        $(".saldo_negociar_fijos").html("$"+numeral($totalFijos).format('0,0'));
        $(".saldo_no_negociar_fijos").html("$"+numeral(saldo_total_fijos-$totalFijos).format('0,0.'));

        $("#saldo_negociar_cupos").val($totalCupos);
        $(".saldo_negociar_cupos").html("$"+numeral($totalCupos).format('0,0'));
        $(".saldo_no_negociar_cupos").html("$"+numeral(saldo_total_cupos-$totalCupos).format('0,0.'));

        // cuotas
        $("#cuotas_negociar").val(cuotas_negociar);
        $(".cuotas_negociar").html("$"+numeral(cuotas_negociar).format('0,0'));
        $(".cuotas_no_negociar").html("$"+numeral(total_cuotas-cuotas_negociar).format('0,0'));

        $("#cuotas_negociar_hip_vehi").val($totalHipVehiC);
        $(".cuotas_negociar_hip_vehi").html("$"+numeral($totalHipVehiC).format('0,0'));
        $(".cuotas_no_negociar_hip_vehi").html("$"+numeral(cuotas_total_hip_vehi-$totalHipVehiC).format('0,0.'));

        $("#cuotas_negociar_fijos").val($totalFijosC);
        $(".cuotas_negociar_fijos").html("$"+numeral($totalFijosC).format('0,0'));
        $(".cuotas_no_negociar_fijos").html("$"+numeral(cuotas_total_fijos-$totalFijosC).format('0,0.'));

        $("#cuotas_negociar_cupos").val($totalCuposC);
        $(".cuotas_negociar_cupos").html("$"+numeral($totalCuposC).format('0,0'));
        $(".cuotas_no_negociar_cupos").html("$"+numeral(cuotas_total_cupos-$totalCuposC).format('0,0.'));

    }*/

</script>





