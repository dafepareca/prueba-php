<?php
/**
 * Created by PhpStorm.
 * User: walter
 * Date: 17/08/17
 * Time: 02:39 PM
 */

?>
<?= $this->Form->create($user, [
    'class' => 'form-ajax-3',
    'update' => '#page-content-wrapper',
    'horizontal' => false,
    'type' => 'file',
    'url' => ['controller' => 'users','action' => 'update_password',$user->id]
]) ?>

<div id="modal_update_password" class="modal-defaul modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?=__('Update password')?></h4>
            </div>
            <div  class="modal-body">
                    <?= $this->Form->input('password_update', ['required','class'=>'input-sm','type' => 'password', 'label' => __('New Password'), 'prepend' => $this->Html->icon('key')]) ?>
                    <?= $this->Form->input('password_confirm_update', ['required','class'=>'input-sm', 'type' => 'password', 'label' => __('Confirm Password'), 'prepend' => $this->Html->icon('key')]) ?>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary"><?=__('Save')?></button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?= $this->Form->end() ?>

<script>
    $('#modal_update_password').modal({
        backdrop: false,
        keyboard: false,
    });
</script>

