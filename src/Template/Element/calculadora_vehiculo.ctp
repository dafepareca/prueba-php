<?php
/**
 * Created by PhpStorm.
 * User: walter
 * Date: 4/09/17
 * Time: 04:29 PM
 */
/** @var  $obligacion \App\Model\Entity\Obligation*/
?>
<style>
    .dl-horizontal dt {
        text-align: left;
    }
    .form-control{
        margin-bottom: 4px;
        margin-top: 4px;
    }
</style>
    <div class="example-modal">
    <div class="modal modal-danger" id="modal-vehiculo-<?php echo $obligacion->id; ?>">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">
                        <?=__('Calculadora Vehículo' )?>
                    </h4>
                </div>
                <?= $this->Form->create('', [
                    'url' => ['controller' => 'obligations', 'action' => 'resultado'],
                    'class' => 'formInformacionVehiculo form-ajax',
                    'id' => 'formInformacionVehiculo-'.$obligacion->id,
                    'update' => '#resultado_consulta',
                    'horizontal' => false,
                    'templates' => [
                        'inputContainer' => '{{content}}'
                    ],
                ]) ?>

                    <div class="oooooooo"></div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div id="conten-calculadora">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">Condiciones actuales del crédito</div>
                                                <div class="panel-body">
                                                    <dl class="dl-horizontal">
                                                        <dt><?= __('Saldo total') ?></dt>
                                                        <dd><?= $this->Number->Currency($obligacion->total_debt, null, ['precision' => 0]) ?></dd>
                                                        <dt><?= __('Saldo capital') ?></dt>
                                                        <dd><?= $this->Number->Currency($obligacion->total_debt, null, ['precision' => 0]) ?></dd>
                                                        <dt><?= __('Acierta seguimiento') ?></dt>
                                                        <dd><?= $obligacion->acierta ?></dd>
                                                        <dt><?= __('Días mora') ?></dt>
                                                        <dd><?= $obligacion->days_past_due ?></dd>
                                                        <dt><?= __('Tipo cartera') ?></dt>
                                                        <dd><?= ($obligacion->company == 9)?'Castigada':'Vigente' ?></dd>
                                                    </dl>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">Condiciones actuales de la garantia</div>
                                                <div class="panel-body">

                                                    <dl class="dl-horizontal">
                                                        <dt><?= __('Avalúo garantia') ?></dt>
                                                        <dd><?= $this->Number->Currency($obligacion->valor_avaluo, null, ['precision' => 0]) ?></dd>
                                                        <dt><?= __('Fecha avalúo') ?></dt>
                                                        <dd>
                                                            <input name="fecha_avaluo" value="<?= (!empty($obligacion->fecha_avaluo)?$obligacion->fecha_avaluo->format('Y-m-d'):'--') ?>" type="text" class="datetimepicker form-control input-sm">
                                                        </dd>
                                                        <dt><?= __('Tipo avalúo') ?></dt>
                                                        <dd>
                                                            <select name="tipo_avaluo" class="form-control input-sm">
                                                                <option value="1">Perito</option>
                                                                <option value="2">Facecolda</option>
                                                                <option value="3">Revista motor</option>
                                                            </select>
                                                        </dd>
                                                        <dt><?= __('Deuda parqueadero') ?></dt>
                                                        <dd><input name="deuda_parqueadero" type="number" class="form-control input-sm"></dd>
                                                        <dt><?= __('Deuda comparendos') ?></dt>
                                                        <dd><input name="deuda_comparendo" type="number" class="form-control input-sm"></dd>
                                                        <dt><?= __('Deuda impuestos') ?></dt>
                                                        <dd><input name="deuda_impuesto" type="number" class="form-control input-sm"></dd>
                                                        <dt><?= __('Gastos mantenimiento') ?></dt>
                                                        <dd><input name="deuda_gastos" type="number" class="form-control input-sm"></dd>
                                                    </dl>
                                                    <button class="btn btn-primary" type="submit">Consultar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="resultado_consulta" class="row informacion">

                        </div>
                    </div>
                <input type="hidden" name="id" value="<?php echo $obligacion->id; ?>">
                <?= $this->Form->end() ?>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</div>

