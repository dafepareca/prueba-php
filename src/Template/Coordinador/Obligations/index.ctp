<?php
/**
 * Created by PhpStorm.
 * User: walter
 * Date: 23/06/17
 * Time: 11:06 AM
 */
?>
<div class="row">
    <div class="col-lg-6">
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h3 class="panel-title">Obligaciones Negociables</h3>
            </div>
            <div class="panel-body">
                <table class="table table-responsive table-bordered table-hover">
                    <thead class="thead-default">
                    <tr class="table-danger">
                        <th>Tipo</th>
                        <th>Obligación</th>
                        <th>Saldo total</th>
                        <th>Cuota</th>
                        <th>Pago mínimo</th>
                        <th>Días mora</th>
                        <th>Tasa EA</th>
                        <th>Moneda</th>
                        <th>Marca</th>
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
                        <td><?=$obligation->rate?></td>
                        <td><?=$obligation->currency?></td>
                        <td>
                            <?php if(in_array($obligation->type_obligation_id,[1,2])){ ?>
                                <input class="checkbox" data-fee="<?=$obligation->fee ?>" data-type="<?=$obligation->type_obligation_id ?>" data-value="<?=$obligation->total_debt ?>"  value="<?= $obligation->id ?>" checked disabled type="checkbox" name="marca">
                            <?php }else{ ?>
                                <input class="checkbox" data-fee="<?=$obligation->fee ?>" data-type="<?=$obligation->type_obligation_id ?>" data-value="<?=$obligation->total_debt ?>" value="<?= $obligation->id ?>" type="checkbox" name="marca">
                            <?php } ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h3 class="panel-title">Obligaciones No Negociables</h3>
            </div>
            <div class="panel-body">
                <table class="table table-responsive table-bordered table-hover">
                    <thead class="thead-default">
                    <tr>
                        <th>Tipo</th>
                        <th>Obligación</th>
                        <th>Saldo total</th>
                        <th>Cuota</th>
                        <th>Pago mínimo</th>
                        <th>Días mora</th>
                        <th>Tasa EA</th>
                        <th>Moneda</th>
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
                            <td><?=$obligation->rate?></td>
                            <td><?=$obligation->currency?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Resumen situación actual cliente</h3>
            </div>
            <div class="panel-body">
                <table class="table table-responsive">
                    <thead class="thead-default">
                    <tr>
                        <th></th>
                        <th>Total</th>
                        <th>Hipotecario y Vehiculo</th>
                        <th>Fijo</th>
                        <th>Cupos</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th>Saldo total</th>
                        <td>$<?=number_format($total,0,'.','.')?></td>
                        <td>$<?=number_format($totalHipVehi,0,'.','.')?></td>
                        <td>$<?=number_format($totalFijos,0,'.','.')?></td>
                        <td>$<?=number_format($totalCupos,0,'.','.')?></td>

                    </tr>
                    <tr>
                        <th>Saldo negociable</th>
                        <td>$<?=number_format($totalNegociable,0,'.','.')?></td>
                        <td>$<?=number_format($totalNegociableHipVehi,0,'.','.')?></td>
                        <td>$<?=number_format($totalNegociableFijos,0,'.','.')?></td>
                        <td>$<?=number_format($totalNegociableCupos,0,'.','.')?></td>
                    </tr>
                    <tr>
                        <td colspan="5">
                            <hr style="border: 0.25px solid">
                        </td>
                    </tr>
                    <tr>
                        <th>Total cuotas</th>
                        <td>$<?=number_format($totalCuotas,0,'.','.')?></td>
                        <td>$<?=number_format($totalHipVehiC,0,'.','.')?></td>
                        <td>$<?=number_format($totalFijosC,0,'.','.')?></td>
                        <td>$<?=number_format($totalCuposC,0,'.','.')?></td>
                    </tr>
                    <tr>
                        <th>Cuotas negociables</th>
                        <td>$<?=number_format($totalNegociableCuotas,0,'.','.')?></td>
                        <td>$<?=number_format($totalNegociableHipVehi,0,'.','.')?></td>
                        <td>$<?=number_format($totalNegociableFijosC,0,'.','.')?></td>
                        <td>$<?=number_format($totalNegociableCuposC,0,'.','.')?></td>

                    </tr>
                    </tbody>
                </table>
            </div>

        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Resumen nueva situación cliente</h3>
            </div>
            <div class="panel-body">
                <table class="table table-responsive">
                    <thead class="thead-default">
                    <tr>
                        <th></th>
                        <th>Total</th>
                        <th>Hipotecario y Vehiculo</th>
                        <th>Fijo</th>
                        <th>Cupos</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th>Saldo Negociar</th>
                        <td class="saldo_negociar">--</td>
                        <td class="saldo_negociar_hip_vehi">--</td>
                        <td class="saldo_negociar_fijos">--</td>
                        <td class="saldo_negociar_cupos">--</td>
                    </tr>
                    <tr>
                        <th>Saldo no negociar</th>
                        <td class="saldo_no_negociar">--</td>
                        <td class="saldo_no_negociar_hip_vehi">--</td>
                        <td class="saldo_no_negociar_fijos">--</td>
                        <td class="saldo_no_negociar_cupos">--</td>
                    </tr>
                    <tr>
                        <td colspan="5">
                            <hr style="border: 0.25px solid">
                        </td>
                    </tr>
                    <tr>
                        <th>Cuotas negociar</th>
                        <td class="cuotas_negociar">--</td>
                        <td class="cuotas_negociar_hip_vehi">--</td>
                        <td class="cuotas_negociar_fijos">--</td>
                        <td class="cuotas_negociar_cupos">--</td>

                    </tr>
                    <tr>
                        <th>Cuotas no negociar</th>
                        <td class="cuotas_no_negociar">--</td>
                        <td class="cuotas_no_negociar_hip_vehi">--</td>
                        <td class="cuotas_no_negociar_fijos">--</td>
                        <td class="cuotas_no_negociar_cupos">--</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <input type="hidden" value="0" id="saldo_negociar">
            <input type="hidden" value="<?=$total?>" id="saldo_total">
            <input type="hidden" value="<?=$totalHipVehi?>" id="saldo_total_hip_vehi">
            <input type="hidden" value="<?=$totalFijos?>" id="saldo_total_fijos">
            <input type="hidden" value="<?=$totalCupos?>" id="saldo_total_cupos">

            <input type="hidden" value="0" id="cuotas_negociar">
            <input type="hidden" value="<?=$totalCuotas?>" id="total_cuotas">
            <input type="hidden" value="<?=$totalHipVehiC?>" id="cuotas_total_hip_vehi">
            <input type="hidden" value="<?=$totalFijosC?>" id="cuotas_total_fijos">
            <input type="hidden" value="<?=$totalCuposC?>" id="cuotas_total_cupos">

        </div>
    </div>
</div>

<script>

    $(document).on('change', '.checkbox', function() {
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



        $("input:checkbox:checked").each(function() {
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

    }

</script>
