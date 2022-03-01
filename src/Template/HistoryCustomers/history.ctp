<?php
/**
 * Created by PhpStorm.
 * User: walter
 * Date: 4/09/17
 * Time: 04:37 PM
 */
?>

<div class="table-responsive table-modal">
    <table class="table table-hover">
        <thead class="thead-default">
        <tr class="table-danger">
            <th></th>
            <th><?=__('Fecha')?></th>
            <th><?=__('Hora')?></th>
            <th><?=__('Ingresos')?></th>
            <th><?=__('Capacidad de Pago')?></th>
            <th><?=__('Alternativa')?></th>
            <th><?=__('Pago Acordado')?></th>
            <th><?=__('Estado')?></th>
            <th><?=__('Usuario')?></th>
        </tr>
        </thead>
        <tbody>
        <?php if(isset($historyCustomer)):
            /** @var  $history \App\Model\Entity\HistoryCustomer */
            foreach($historyCustomer as $history):
                ?>
                <tr>
                    <td style="color: #ed1c27 !important;">
                        <?=$this->Html->link('Ver',
                            [
                                'controller' => 'historyCustomers',
                                'action' => 'view',
                                $history->id,
                                '_full' => true,
                            ],
                            [
                                'class' => 'history_detail',
                                'style' => 'color: #ed1c27 !important;'
                            ]
                        );?>
                    </td>
                    <td><?=$history->created->format('Y-m-d')?></td>
                    <td><?=$history->created->format('H:i:s')?></td>
                    <td><?=$this->Number->Currency($history->income, null, ['precision' => 0])?></td>
                    <td><?=$this->Number->Currency($history->payment_capacity, null, ['precision' => 0])?></td>
                    <td><?=$history->alternative?></td>
                    <td><?=$this->Number->Currency($history->payment_agreed, null, ['precision' => 0])?></td>
                    <td><?=$history->history_status->name?></td>
                    <td><?=$history->user->name?></td>
                </tr>
            <?php endforeach; ?>
        <?php endIf; ?>
        </tbody>
    </table>
</div>
