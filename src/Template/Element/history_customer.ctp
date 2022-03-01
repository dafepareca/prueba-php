<?php
/**
 * Created by PhpStorm.
 * User: walter
 * Date: 4/09/17
 * Time: 04:29 PM
 */
?>
<div class="example-modal">
    <div class="modal modal-danger" id="modal-cliente">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">
                        <?=__('Historial Cliente')?>
                    </h4>
                </div>
                <div class="modal-body">

                    <div class="row informacion">
                        <div class="col-sm-12">
                            <div style="color: #ed1c27; display: inline">
                                <?=$this->Html->icon('user fa-4x');?>
                            </div>
                            <div style="display: inline-block; font-size: 12px">
                                <p><strong><?=__('Nombre Cliente')?>:</strong> <?=$customer->name;?> </p>
                                <p><strong><?php echo @$customer->customer_type_identification->type;?>:</strong> <?=$customer->id; ?></p>
                            </div>

                            <div class="div-separador"></div>

                            <div id="conten-history-customer">

                            </div>

                        </div>

                    </div>

                    <div id="detail_history" class="row informacion">

                    </div>

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</div>
