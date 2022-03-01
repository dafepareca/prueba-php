<div id="modal_update_password" class="modal-danger modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?=__('Alerta del sistema')?></h4>
            </div>
            <div  class="modal-body">
            En este momento no se puede completar la operación, por favor intente nuevamente.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Close') ?></button>
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
