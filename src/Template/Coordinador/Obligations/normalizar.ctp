
<div class="row">
    <?= $this->Form->create('', [
        'id' => 'form-normalizar',
        'update' => '#tab-resumen',
        'horizontal' => false,
        'url' => ['controller' => 'obligations', 'action' => 'resumen']
    ]) ?>
    <div class="col-lg-10 col-lg-offset-1">
        <div class="row">
            <div class="col-lg-6">
                <?= $this->element('_datos_cliente', ['customer' => $customer]) ?>
            </div>
            <div class="col-lg-6">
                <?= $this->element('_resumen_cliente', ['customer' => $customer]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?=__('Capacidad de pago:')?></h3>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-responsive table-bordered table-hover">
                                <thead class="thead-default">
                                </thead>
                                <tbody>
                                <tr>
                                    <th><?=__('Informado cliente:')?></th>
                                    <td><?=$this->Number->Currency($capacidadPago,null,['precision' => 0])?></td>
                                </tr>
                                <tr>
                                    <th><?=__('Sugerido')?></th>
                                    <td><?=$this->Number->Currency($capacidadPago+$capacidadPago*0.3,null,['precision' => 0])?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="box box-info">
                    <div class="box-header with-border">
                        <h4 class="box-title"><?=__('Comportamiento Ingresos')?></h4>
                    </div>
                    <div class="box-body">
                        <div class="chart">
                            <canvas id="lineChart" style="height:250px"></canvas>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>

            <div class="col-lg-6">
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?=__('Negociación:')?></h3>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-responsive table-bordered table-hover">
                                <thead class="thead-default">
                                </thead>
                                <tbody>
                                <tr>
                                    <th><?=__('Cuota')?></th>
                                    <th><?=__('Tasa EA')?></th>
                                    <th><?=__('Tasa EM')?></th>
                                    <th><?=__('Plazo')?></th>
                                    <th></th>
                                </tr>

                                <?php
                                $valor=0;
                                if(isset($normalizacion['data'])):
                                    foreach($normalizacion['data'] as $key => $data):
                                        $class = '';
                                        $checked = false;
                                        $disabled = false;

                                        if($data['rango']){
                                            $class = 'bg-danger';
                                            if(!isset($marcado)) {
                                                $checked = true;
                                                $disabled = true;
                                                $marcado = 'ok';
                                                $valor = $key;
                                            }
                                        }
                                        ?>
                                        <tr>
                                            <td class="<?=$class?>"><?=$this->Number->Currency($data['cuota'],null,['precision' => 0])?></td>
                                            <td><?=$data['tasa_anual']?>%</td>
                                            <td><?=$data['tasa']?>%</td>
                                            <td><?=$data['plazo']?></td>
                                            <td>
                                                <?= $this->Form->input('negociacion',
                                                    [
                                                        'type' => 'checkbox',
                                                        'value' => $key,
                                                        'class' => 'aceptar_oferta',
                                                        'label' => false,
                                                        'checked' => $checked,
                                                        'disabled' => $disabled,
                                                        'templates' => [
                                                            'inputContainer' => '{{content}}'
                                                        ],
                                                        'style' => 'margin-left: 0px;'
                                                    ]
                                                )
                                                ?>
                                        </tr>
                                        <?php
                                    endforeach;
                                endif;
                                ?>

                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="text-center" style="padding-bottom: 50px;">
            <?= $this->Form->input('propuesta_aceptada',
                [
                    'value'=>$valor,
                    'id'=>'propuesta_aceptada',
                    'type' => 'hidden'
                ])
            ?>
            <h5><?=__('Acepta')?></h5>
            <?= $this->Form->button(__('Si'), ['type' => 'submit', 'class' => 'btn btn-primary', 'div' => false]) ?>
            <?= $this->Form->button(__('No'), ['onclick'=>'javascrip:location.reload()','class' => 'btn btn-primary', 'div' => false]) ?>
        </div>
    </div>
    <?= $this->Form->end(); ?>
</div>

<script>

    $('html,body').scrollTop(0);

    $('.aceptar_oferta').click( function() {
        if ($(this).is(':checked')) {
            $(this).removeClass('aceptar_oferta');
            $('.aceptar_oferta').attr('disabled', false);
            $('.aceptar_oferta').attr('checked', false);
            $(this).attr('checked', true);
            $(this).attr('disabled', true);
            $(this).addClass('aceptar_oferta');
            $('#propuesta_aceptada').val($(this).val());
        }
    });

    $(function () {

        //-------------
        //- LINE CHART -
        //--------------
        var lineChartCanvas = $("#lineChart").get(0).getContext("2d");

        var areaChartData = {
            labels: ["originación", "Base Banco", "Captura"],
            datasets: [
                {
                    fillColor: "rgba(210, 214, 222, 1)",
                    strokeColor: "rgba(210, 214, 222, 1)",
                    pointColor: "rgba(210, 214, 222, 1)",
                    pointStrokeColor: "#c1c7d1",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    data: [0, 1000000, 3000000]
                }
            ]
        };

        var areaChartOptions = {
            //Boolean - If we should show the scale at all
            showScale: true,
            //Boolean - Whether grid lines are shown across the chart
            scaleShowGridLines: false,
            //String - Colour of the grid lines
            scaleGridLineColor: "rgba(0,0,0,.05)",
            //Number - Width of the grid lines
            scaleGridLineWidth: 1,
            //Boolean - Whether to show horizontal lines (except X axis)
            scaleShowHorizontalLines: true,
            //Boolean - Whether to show vertical lines (except Y axis)
            scaleShowVerticalLines: true,
            //Boolean - Whether the line is curved between points
            bezierCurve: true,
            //Number - Tension of the bezier curve between points
            bezierCurveTension: 0.3,
            //Boolean - Whether to show a dot for each point
            pointDot: false,
            //Number - Radius of each point dot in pixels
            pointDotRadius: 4,
            //Number - Pixel width of point dot stroke
            pointDotStrokeWidth: 1,
            //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
            pointHitDetectionRadius: 20,
            //Boolean - Whether to show a stroke for datasets
            datasetStroke: true,
            //Number - Pixel width of dataset stroke
            datasetStrokeWidth: 2,
            //Boolean - Whether to fill the dataset with a color
            datasetFill: true,
            //String - A legend template
            legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
            //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
            maintainAspectRatio: true,
            //Boolean - whether to make the chart responsive to window resizing
            responsive: true
        };

        var lineChart = new Chart(lineChartCanvas);
        var lineChartOptions = areaChartOptions;
        lineChartOptions.datasetFill = false;
        lineChart.Line(areaChartData, lineChartOptions);

    });
</script>