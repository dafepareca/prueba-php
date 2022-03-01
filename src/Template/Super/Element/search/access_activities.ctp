<?php
use Cake\Routing\Router;
/** @var  $roles \App\Model\Entity\Role[] */
echo $this->Html->css([
        '/plugins/jQueryUI/jquery-ui.min',
        '/plugins/daterangepicker/daterangepicker-bs3',
]);
echo $this->Html->script([
        '/plugins/jQueryUI/jquery-ui.min',
        'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js',
        '/plugins/daterangepicker/daterangepicker'
]);
?>
<div class="searchForm">
    <?php
    $this->Form->setConfig('columns', [
        'xs' => null,
        'sm' => null,
        'md' => [
            'label' => 3,
            'input' => 8,
            'error' => 1
        ],
        'lg' => [
            'label' => 3,
            'input' => 8,
            'error' => 1
        ]
    ]);
    $this->Html->templates([
        'dropdownMenu' => '<div class="dropdown-menu{{attrs.class}} flat dropdown-menu-right" id="dropdown-search" {{attrs}}>{{content}}</div>',
        'dropdownMenuItem' => '{{content}}',
    ]);
    ?>
    <?= $this->Form->create('Search', [
        'class' => 'form-ajax inline',
        'update' => '#page-content-wrapper', 'type' => 'get',
        'horizontal' => true,
        'templates' => [
            'input' => '<input type="{{type}}" name="{{name}}" class="form-control{{attrs.class}} input-sm" {{attrs}} />',
            'select' => '<select name="{{name}}" class="form-control{{attrs.class}} input-sm" {{attrs}}>{{content}}</select>',
            'inputContainer' => '<div class="col-xs-12"><div class="form-group {{type}}{{required}}">{{content}}</div></div>',
        ]
    ]);
    ?>
    <?= $this->Form->input('date', [
        'templates' => [
            'inputContainer' => '<div class="form-group {{type}}{{required}}">{{content}}</div>',
            'input' => '<input type="{{type}}" name="{{name}}" class="form-control{{attrs.class}} input-sm" {{attrs}} />',
            'formGroupHorizontal' => '{{label}}<div class="col-lg-6 col-md-7 col-sm-8">{{prepend}}{{input}}{{append}}</div>',
        ],
        'label' => false, 'placeholder' => __('Search by date'), 'readonly' => 'readonly',
        'append' =>
            $this->Form->button($this->Html->icon('search'), ['class' => 'btn btn-info btn-sm', 'escape' => false]) .
            $this->Form->dropdownButton('',
                [
                    $this->Panel->create() .
                    $this->Panel->body(
                        //$this->Form->input('date', ['prepend' => '<i class="fa fa-calendar"></i>'])
                        $this->Form->input('type_activity_id', ['options' => $accessTypesActivities, 'empty' => __('Select Item')])
                        . $this->Form->input('model', ['options' => $models, 'empty' => __('Select Item')])
                    ) .
                    $this->Panel->footer(
                        '<div class="text-right">'
                        . '<div class=" pull-right">'
                        . $this->Form->button(__('Filter'), ['class' => 'btn-info btn-sm flat', 'escape' => false]) . ' '
                        . $this->Form->button(__('Close'), ['type' => 'button', 'id' => 'close-menu', 'class' => 'btn-default btn-sm flat', 'escape' => false])
                        . '</div>'
                        . '</div>'
                        . '<div class="clearfix"></div>'
                    ) .
                    $this->Panel->end()
                ],
                ['class' => 'btn-sm btn-default']
            )
    ]); ?>
    <?= $this->Form->end(); ?>
</div>
<script>
    $('.searchForm .dropdown-menu').click(function (e) {
        e.stopPropagation();
    });
    $(document).ready(function (e) {
        function customwidth() {
            var formwidth = $('.searchForm .input-group').width();
            $('.searchForm .dropdown-menu').width(formwidth);
        }

        customwidth();
        $(window).resize(function (e) {
            customwidth();
        });

        $('#close-menu').click(function (e) {
            e.stopPropagation();
            $("#dropdown-search").dropdown('toggle');
        });

    });
    $('#users-name').autocomplete({
        source:'<?php echo Router::url(array('controller' => 'Users', 'action' => 'autocomplete', 'prefix' => false)); ?>',
        minLength: 3,
        select: function (event, ui) {
            event.stopPropagation();
        }

    });
    $('#date').daterangepicker(
        {
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            //startDate: moment().subtract(29, 'days'),
            //endDate: moment(),
            autoUpdateInput: false,
            locale: {
                format: 'YYYY-MM-DD',
            },
        },
        function (start, end) {
            $('#reportrange span').html(start.format('YYYY/MM/DD') + ' - ' + end.format('YYYY/MM/DD'));
        }
    ).click(function() {
        $('.daterangepicker').click(function(e){
            e.stopPropagation();
        });
    });

    $('#date').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
    });
</script>