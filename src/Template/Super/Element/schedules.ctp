<?php
$key = isset($key) ? $key : '<%= key %>';
$this->Form->horizontal = false;
?>
<tr>
    <td width="100">
        <div class="input-group">
            <input name="<?='schedules['.$key.'][start_time]';?>" type="text" class="form-control input-sm timepicker">
            <div class="input-group-addon">
                <i class="fa fa-clock-o"></i>
            </div>
        </div>
    </td>
    <td width="100">
        <div class="input-group">
            <input name="<?='schedules['.$key.'][end_time]';?>" type="text" class="form-control input-sm timepicker">
            <div class="input-group-addon">
                <i class="fa fa-clock-o"></i>
            </div>
        </div>
    </td>
    <td>
        <?= $this->Form->input("schedules.{$key}.commentary", [
            'label' => false,
            'class' => 'input-sm',
            'type'=>'text',
            'templates' => [
                'inputContainer' => '{{content}}'
            ]
        ]); ?>
    </td>
    <td width="20" align="center" valign="middle">
        <?=$this->Html->link($this->Html->icon('minus'),
            '#', ['escape' => false, 'class' => 'removeIp btn btn-xs btn-danger', 'title' => __('Remove')]); ?>
    </td>
</tr>
<?php $this->Form->horizontal = true; ?>