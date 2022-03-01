<div class="pull-left">
    <?php
    echo $this->Form->create('Search', [
        'inline' => true, 'class' => 'form-ajax',
        'update' => '#page-content-wrapper', 'type' => 'get',
        'templates' => [
            'inputGroupStart' => '<div class="input-group input-group-sm" style="width: 180px;">{{prepend}}',
        ]
    ]);
    echo $this->Form->input('code', [
        'label' => false,
        'placeholder' => __('Search by code'),
        'append' => [
            $this->Form->button($this->Html->icon('search'), [ 'class' => 'btn btn-info btn-sm', 'escape' => false])
        ]
    ]);
    echo $this->Form->end();
    ?>
</div>
<div class="clearfix" style="margin-bottom: 25px"></div>