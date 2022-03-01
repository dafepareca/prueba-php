<h1>Oferta</h1>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h3 class="panel-title">Obligaciones Negociadas</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
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
                        <th>Tasa EM</th>
                        <th>Moneda</th>
                        <th>Nuevo Plazo</th>
                        <th>Nueva Tasa EA</th>
                        <th>Nueva Cuota</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    /** @var  $obligation \App\Model\Entity\Obligation*/
                    foreach($negociadas as $obligation):
                            if($obligation->normalizar == 1){
                                $nuevoPlazo = "Normalizar";
                                $nuevaTasa = "Normalizar";
                                $nuevaCuota = "Normalizar";
                            }else{
                                $nuevoPlazo = $obligation->nuevoPlazo;
                                $nuevaTasa = $obligation->rate.'%';
                                $nuevaCuota = '$'.number_format($obligation->nuevaCuota,0,'.','.');
                            }
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
                        <td><?=$nuevoPlazo?></td>
                        <td><?=$nuevaTasa?></td>
                        <td><?=$nuevaCuota?></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
            </div>

        </div>
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h3 class="panel-title">Obligaciones Negociables</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
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
                    foreach($negociables as $obligation):
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
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h3 class="panel-title">Obligaciones No Negociables</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
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
                    foreach($noNegociables as $obligation):
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
<div class="row">
    <div class="col-lg-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Resumen Final</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                <table class="table table-responsive">
                    <thead class="thead-default">
                    <tr>
                        <th>Resumen final</th>
                        <th>Saldo Capital</th>
                        <th>Valor cuotas</th>
                        <th>Valor nuevas Cuotas</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th>Créditos negociados</th>
                        <td>$<?=number_format($valorNegociados,0,'.','.')?></td>
                        <td>$<?=number_format($cuotaNegociados,0,'.','.')?></td>
                        <td>$<?=number_format($nuevaCuotaNegociados,0,'.','.')?></td>

                    </tr>
                    <tr>
                        <th>Créditos no negociado</th>
                        <td>$<?=number_format($valorNoNegociados,0,'.','.')?></td>
                        <td>$<?=number_format($cuotaNoNegociados,0,'.','.')?></td>
                        <td>$<?=number_format($cuotaNoNegociados,0,'.','.')?></td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <hr style="border: 0.25px solid">
                        </td>
                    </tr>
                    <tr>
                        <th>Total</th>
                        <td>$<?=number_format($total,0,'.','.')?></td>
                        <td>$<?=number_format($totalCuota,0,'.','.')?></td>
                        <td>$<?=number_format($totalNuevaCuota,0,'.','.')?></td>
                    </tr>
                    </tbody>
                </table>

                    <table class="table table-responsive">
                        <thead class="thead-default">
                            <tr>
                                <th></th>
                                <th>Total</th>
                                <th>Cuapos</th>
                                <th>Fijos</th>
                                <th>Hip y veh</th>
                            </tr>
                        </thead>
                    <tbody>
                        <tr>
                            <th>Pago minimo hoy</th>
                            <td>$<?=number_format($pagoMinimo,0,'.','.')?></td>
                            <td>--</td>
                            <td>--</td>
                            <td>--</td>
                        </tr>
                        <tr>
                            <th>Total pago minimo sugerido hoy</th>
                            <td>--</td>
                            <td>--</td>
                            <td>--</td>
                            <td>--</td>
                        </tr>
                        <tr>
                            <th>Pago acordado con cliente hoy</th>
                            <td>--</td>
                            <td>--</td>
                            <td>--</td>
                            <td>--</td>
                        </tr>
                    </tbody>

                </table>
                </div>
            </div>


            <div class="panel-footer text-center">
                <?= $this->Form->button(__('Si'), ['class' => 'btn btn-primary', 'div' => false]) ?>
                <?= $this->Form->button(__('No'), ['onclick'=>'javascrip:location.reload()','class' => 'btn btn-primary', 'div' => false]) ?>
            </div>
        </div>
    </div>
</div>