<?php
/** @var  $accessGroups \App\Model\Entity\AccessGroup[] */
/** @var  $accessGroups \App\Model\Entity\UserStatus[] */
?>
<div class="searchForm">
    <?php
    $this->Form->setConfig('columns', [
        'sd' => null,
        'md' => null,
        'lg' => [
            'label' => 4,
            'input' => 8,
            'error' => 0
        ]
    ]);
    $this->Html->templates([
        'dropdownMenu' => '<div class="dropdown-menu{{attrs.class}} " id="dropdown-search" {{attrs}}>{{content}}</div>',
        'dropdownMenuItem' => '{{content}}',
    ]);
    ?>
    <?= $this->Form->create('Search', [
        'class' => 'form-ajax',
        'update' => '#page-content-wrapper', 'type' => 'get',
        'horizontal' => true,
        'templates' => [
            'input' => '<input type="{{type}}" name="{{name}}" class="form-control{{attrs.class}} input-sm" {{attrs}} />',
            'select' => '<select name="{{name}}" class="form-control{{attrs.class}} input-sm" {{attrs}}>{{content}}</select>',
            'inputContainer' => '<div class="col-xs-6"><div class="form-group {{type}}{{required}}">{{content}}</div></div>',
        ]
    ]);
    ?>
    <?= $this->Form->input('name', [
        'templates' => [
            'inputContainer' => '<div class="form-group {{type}}{{required}}">{{content}}</div>',
            'input' => '<input type="{{type}}" name="{{name}}" class="form-control{{attrs.class}} input-sm" {{attrs}} />',
            'formGroupHorizontal' => '{{label}}<div class="col-lg-6 col-md-7 col-sm-8">{{prepend}}{{input}}{{append}}</div>',
        ],
        'label' => false, 'placeholder' => __('Search by name'),
        'prepend' =>
            $this->Form->dropdownButton('',
                [
                    $this->Panel->create().
                    $this->Panel->body(
                         $this->Form->input('email')
                        .$this->Form->input('identification')
                        .$this->Form->input('mobile')
                        .$this->Form->input('access_group_id', [ 'options' => $accessGroups, 'empty' => __('Select Item')])
                        .$this->Form->input('user_status_id', [ 'options' => $userStatuses, 'empty' => __('Select Item')])
                        .$this->Form->input('role_id', [ 'options' => $roles, 'empty' => __('Select Item')])
                        .$this->Form->input('busines_id', [ 'options' => $businessGroups, 'empty' => __('Select Item')])
                    ).
                    $this->Panel->footer(
                        '<div class="text-right">'
                            .'<div class=" pull-right">'
                                .$this->Form->button(__('Filter'), [ 'class' => 'btn-info btn-sm flat', 'escape' => false]).' '
                                .$this->Form->button(__('Close'), ['type' => 'button', 'id' => 'close-menu', 'class' => 'btn-default btn-sm flat', 'escape' => false])
                            .'</div>'
                        .'</div>'
                        .'<div class="clearfix"></div>'
                    ).
                    $this->Panel->end()
                ],
                [ 'class' => 'btn-sm btn-default dropdown-menu-left' ]
            ),
        'append' =>
            $this->Form->button($this->Html->icon('search'), [ 'class' => 'btn btn-info btn-sm', 'escape' => false])
    ]); ?>
    <?= $this->Form->end(); ?>
</div>
<script>
    $('.searchForm .dropdown-menu').click(function(event){
        event.stopPropagation();
    });
    $(document).ready(function(e) {
        function customwidth(){
            var formwidth = $('.searchForm .input-group').width();
            $('.searchForm .dropdown-menu').width(formwidth);
        }
        customwidth();
        $(window).resize(function(e) {
            customwidth();
        });

        $('#close-menu').click(function(e){
            e.stopPropagation();
            $("#dropdown-search").dropdown('toggle');// this doesn't
        });
    });
</script>