<?php
/**
 * Created by PhpStorm.
 * User: walter
 * Date: 31/07/17
 * Time: 11:50 AM
 */

/** @var  $oficina \App\Model\Entity\Office*/

?>

<div>
    <table class="table table-responsive table-bordered table-hover">
        <thead>
            <th></th>
            <th><?=__('Oficina')?></th>
            <th><?=__('Dirección')?></th>
            <th><?=__('Horario')?></th>
        </thead>
        <tbody>
        <?php foreach($oficinas as $oficina):?>
            <tr>
                <td style="width: 60px; text-align: center">
                    <input oficina="<?=$oficina->name; ?>" class="select_oficina" type="radio" name="oficina" value="<?=$oficina->id?>">
                </td>
                <td><?=$oficina->name; ?></td>
                <td><?=$oficina->address; ?></td>
                <td>
                    <?php foreach($oficina->schedules as $horario): ?>
                        <p><?=(!empty($horario->commentary))?$horario->commentary.' De ':''?><?=$horario->formatStartTime().' A '.$horario->formatENdTime(); ?></p>
                    <?php endforeach; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>




