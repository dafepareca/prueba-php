<?php
$key = isset($key) ? $key : '<%= key %>';
$this->Form->horizontal = false;
?>
<tr>
    <td>
        <?= $this->Form->hidden("ips_groups.{$key}.id") ?>
        <?= $this->Form->input("ips_groups.{$key}.name", [
                'label' => false,
                'class' => 'input-sm',
                'templates' => [
                    'inputContainer' => '{{content}}'
                ]
        ]); ?>
    </td>
    <td>
        <?= $this->Form->input("ips_groups.{$key}.ip_address", [
            'label' => false,
            'class' => 'input-sm',
            'templates' => [
                'inputContainer' => '{{content}}'
            ],
            'prepend' => $this->Html->icon('laptop'),
            'pattern' => '\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}',
            'title' => 'IPv4 0.0.0.0'
        ]);?>
    </td>
    <td width="20" align="center" valign="middle">
        <?=$this->Html->link($this->Html->icon('minus'),
            '#', ['escape' => false, 'class' => 'removeIp btn btn-xs btn-danger', 'title' => __('Remove')]); ?>
    </td>
</tr>
<?php $this->Form->horizontal = true; ?>