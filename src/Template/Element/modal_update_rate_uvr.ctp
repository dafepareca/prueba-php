<?php
/**
 * Created by PhpStorm.
 * User: walter
 * Date: 17/08/17
 * Time: 02:39 PM
 */

?>

<div id="modal_update_password" class="modal-danger modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?=__('Alerta del sistema')?></h4>
            </div>
            <div  class="modal-body">
                No se encontró configuración de la tasa UVR para el mes actual.
                Por favor informe esta situación al administrador del sistema.
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
    $('#modal_update_password').modal({
        backdrop: false,
        keyboard: false,
    });
</script>

