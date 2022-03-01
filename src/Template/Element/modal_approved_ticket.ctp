<?php
/**
 * Created by PhpStorm.
 * User: walter
 * Date: 17/08/17
 * Time: 02:39 PM
 */

$content = '<h4>'.__('Are you sure you want to approved this ticket?').'</h4>';
echo $this->Modal->create(['id' => 'ConfirmApprovedTicket', 'class' => 'modal-default confirm']);
echo $this->Modal->header($this->Html->icon('question-circle-o') . ' ' . __('Confirm Approved Ticket'), ['close' => true, 'class' => 'modal-header-default']);
echo $this->Modal->body($content, ['class' => 'my-body-class']);
echo $this->Modal->footer(
    [
        $this->Form->button(__('Cancel'), ['data-dismiss' => 'modal']),
        $this->Form->button(__('Approved'), ['data-dismiss' => 'modal', 'action' => '#', 'class' => 'btn btn-danger btn-delete'])
    ]
);
echo $this->Modal->end();

?>