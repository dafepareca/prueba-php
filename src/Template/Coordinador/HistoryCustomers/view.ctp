<?php
/**
 * Created by PhpStorm.
 * User: walter
 * Date: 14/08/17
 * Time: 05:05 PM
 */
?>
<fieldset class="scheduler-border" style="padding: 0 1.4em 0em 0em !important">
    <legend class="scheduler-border"><?=__('Fecha de Gestión')?> : <?=$historyCustomer->created->format('Y/m/d')?></legend>
</fieldset>

<?php
    $col = '8';
    if(empty($historyCustomer->history_normalizations)){
        $col = '12';
    }
?>

<div class="col-sm-12">
    <div style="border: 1px solid #000; padding: 8px; border-radius: 8px">
        <h5 style="text-align: center; color: #ed1c27">Situación cliente</h5>
        <div class="table-responsive table-info">
            <table class="table table-hover">
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
                </tr>
                </thead>
                <tbody>
                <?php
                /** @var  $detail \App\Model\Entity\HistoryDetail*/
                foreach($historyCustomer->history_details as $detail): ?>
                    <tr>
                        <td><?=$detail->type_obligation->type?></td>
                        <td><?=$detail->obligation?></td>
                        <td><?=$this->Number->Currency($detail->total_debt, null, ['precision' => 0])?></td>
                        <td><?=$this->Number->Currency($detail->fee, null, ['precision' => 0])?></td>
                        <td><?=$this->Number->Currency($detail->minimum_payment, null, ['precision' => 0])?></td>
                        <td><?=$detail->days_past_due; ?></td>
                        <td><?=$detail->rate_ea?>%</td>
                        <td><?=$detail->rate_em?>%</td>

                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div style="margin-top: 10px" class="col-sm-12"></div>

<div class="col-sm-<?=$col?>">
    <div style="border: 1px solid #000; padding: 8px; border-radius: 8px">
        <h5 style="text-align: center; color: #ed1c27">Nueva situación cliente</h5>
        <div class="table-responsive table-info">
            <table class="table table-hover">
                <thead class="thead-default">
                <tr class="table-danger">
                    <th><?=__('Tipo')?></th>
                    <th><?=__('Obligación')?></th>
                    <th><?=__('Alternativa de Negociación')?></th>
                    <th><?=__('Plazo')?></th>
                    <th><?=__('Nueva Cuota')?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                /** @var  $detail \App\Model\Entity\HistoryDetail*/
                foreach($historyCustomer->history_details as $detail): ?>
                <tr>
                    <td>
                        <?php
                        if($detail->selected) {
                          echo $this->Html->icon('check', ['style' => 'color:#ed1c27']);
                        }
                        ?>
                        <?=$detail->type_obligation->type?>
                    </td>
                    <td><?=$detail->obligation?></td>
                    <td><?=$detail->strategy; ?></td>
                    <td><?=$detail->term?></td>
                    <td><?=$this->Number->Currency($detail->new_fee, null, ['precision' => 0])?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if(!empty($historyCustomer->history_normalizations)): ?>
<div class="col-sm-4">
    <div style="border: 1px solid #000; padding: 8px; border-radius: 8px">
        <h5 style="text-align: center; color: #ed1c27">Unificación Productos Consumos</h5>
        <div class="table-responsive table-info">
            <table class="table table-hover">
                <thead class="thead-default">
                <tr class="table-danger">
                    <th><?=__('Cuota')?></th>
                    <th><?=__('Tasa')?></th>
                    <th><?=__('Plazo')?></th>
                </tr>
                </thead>
                <tbody>
            <?php
            /** @var  $normalizations \App\Model\Entity\HistoryNormalization*/
            foreach($historyCustomer->history_normalizations as $normalizations): ?>
                <tr>
                    <td>
                        <?php
                        if($normalizations->selected) {
                            echo $this->Html->icon('check', ['style' => 'color:#ed1c27']);
                        }
                        ?>
                        <?=$this->Number->Currency($normalizations->fee, null, ['precision' => 0])?></td>
                    <td><?=$normalizations->rate?>%</td>
                    <td><?=$normalizations->term?></td>
                </tr>
             <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endIf; ?>
