<?php
/** @var  $biUser \App\Model\Entity\BiUser */
/** @var  $accessGroups \App\Model\Entity\AccessGroup[] */
/** @var  $businessUnits \App\Model\Entity\BusinessUnit[] */
/** @var  $Html \Cake\View\Helper\HtmlHelper */
?>
<section class="content-header">
    <h1 class="pull-left"><?= $this->Html->icon('users') . ' ' . __('Bi Users') ?> |
        <small><?= __('Edit') ?></small>
    </h1>
    <div class="btn-group pull-right">
        <?= $this->Html->link($this->Html->icon('chevron-left') . ' ' . $this->Html->tag('span', __('Back'), ['class' => 'hidden-xs hidden-sm']),
            ['controller' => 'Users', 'action' => 'index'], ['escape' => false, 'class' => 'load-ajax btn btn-sm btn-default', 'update' => '#page-content-wrapper', 'title' => __('Back')]) ?>
    </div>
    <div class="clearfix"></div>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= __('Bi User Information') ?></h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <?php $this->Form->setConfig('columns', [
                    'sm' => [
                        'label' => 3,
                        'input' => 9,
                        'error' => 0
                    ],
                    'md' => [
                        'label' => 4,
                        'input' => 8,
                        'error' => 0
                    ]
                ]);
                ?>
                <?= $this->Form->create($biUser, [
                    'class' => 'form-ajax',
                    'update' => '#page-content-wrapper',
                    'horizontal' => true,
                    'type' => 'file'
                ]) ?>
                <div class="box-body">
                    <div class="col-sm-6">
                        <?= $this->Form->input('name') ?>
                        <?= $this->Form->input('email', ['prepend' => $this->Html->icon('at')]) ?>
                        <?= $this->Form->input('mobile', ['prepend' => $this->Html->icon('phone')]) ?>
                        <?= $this->Form->input('access_group_id', ['prepend' => $this->Html->icon('ban'), 'options' => $accessGroups, 'empty' => __('Select Item')]) ?>
                        <?= $this->Form->input('user_status_id', ['options' => \App\Model\Table\UserStatusesTable::getStatusList()]) ?>
                    </div>
                    <div class="col-sm-6">
                        <div class="col-md-offset-4 col-md-8">
                            <?= $this->Nested->_thumb('photo', $biUser->attachment, 'large', ['id' => 'avatar', 'class' => 'img-thumbnail', 'style' => 'margin-bottom:6px']) ?>
                        </div>
                        <?= $this->Form->input('attachment.photo', ['type' => 'file',
                            '_button' => ['class' => 'btn-info'], 'button-label' => __('Search')]) ?>
                        <?= $this->Form->input('attachment.file_dir', ['type' => 'hidden']) ?>
                        <?= $this->Form->input('attachment.file_type', ['type' => 'hidden']) ?>
                        <?= $this->Form->input('attachment.file_size', ['type' => 'hidden']) ?>
                        <?= $this->Form->input('attachment.model', ['type' => 'hidden', 'value' => 'User']) ?>
                    </div>
                    <div class="clearfix"></div>
                    <h4 style="border-bottom: 1px solid #ddd; padding-bottom: 10px;"><?= __('Campaign access') ?></h4>
                    <div class="col-sm-6">
                        <div class="form-group select required <?= !empty($biUser->getError('campaigns_bi_users'))? 'has-error':'' ?>">
                            <label class="control-label col-md-4 col-sm-3"><?= __('Campaigns') ?></label>
                            <div class="col-md-8 col-sm-9">
                                <div id="menu-select">
                                    <div class="panel list-group" style="margin-bottom: 0">
                                        <?php
                                        $temporal = [];
                                        foreach($biUser->campaigns as $item){
                                            $temporal[] =  $item->id;
                                        }
                                        ?>
                                        <?php foreach ($businessUnits as $business_unit): ?>
                                            <a class="list-group-item flat" data-toggle="collapse"
                                               data-target="#BU_<?= h($business_unit->id) ?>" data-parent="#menu-select">
                                                <?= $this->Html->icon('briefcase') ?>
                                                <?= h($business_unit->name) ?>
                                                <span class="label label-info pull-right"><?= h($business_unit->campaign_count) ?></span></a>
                                            <div id="BU_<?= h($business_unit->id) ?>" class="collapse">
                                                <?php foreach ($business_unit->campaigns as $campaign): ?>
                                                    <?php
                                                    $class = $value = null;
                                                    if (!empty($this->request->data['campaigns_bi_users'])) :
                                                        $value = array_search($campaign->id, array_column($this->request->data['campaigns_bi_users'], 'campaign_id'));
                                                        if(!empty($value) || $value === 0):
                                                            $class = 'disabled';
                                                        endif;
                                                    elseif (!empty($biUser->campaigns)):
                                                        $value = array_search($campaign->id, $temporal);
                                                        if(!empty($value) || $value === 0):
                                                            $class = 'disabled';
                                                        endif;
                                                    endif;
                                                    ?>
                                                    <a class="list-group-item flat addCampaign <?= $class ?>"
                                                       data-value="<?= $campaign->id ?>"
                                                       data-id="campaign-<?= $campaign->id ?>"
                                                       data-businessunit="<?= h($business_unit->name) ?>"
                                                       data-campaign="<?= h($campaign->name) ?>"
                                                    ><?= $campaign->name ?></a>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <?php if(!empty($biUser->getError('campaigns_bi_users'))): ?>
                                <span class="help-block error-message col-md-offset-4 col-md-8 col-sm-offset-3 col-sm-9"><?= $biUser->getError('campaigns_bi_users')['_required']?></span>
                            <?php endif ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div style="max-height: 250px; overflow: auto; border: 1px solid #CCC;">
                            <table id="row-table" class="table table-hover table-condensed table-striped">
                                <thead>
                                <tr>
                                    <th width="80"><?= __('Business Unit') ?></th>
                                    <th><?= __('Campaign') ?></th>
                                    <th width="20"><?= $this->Html->icon('eye', ['title' => __('View')]) ?></th>
                                    <th width="20"><?= $this->Html->icon('upload', ['title' => __('Upload')]) ?></th>
                                    <th width="30" align="center"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $key = 0;
                                if (!empty($this->request->data['campaigns_bi_users'])) :
                                    foreach( $this->request->data['campaigns_bi_users'] as $item):
                                        echo $this->element('rows_bi', [
                                            'key'           => $key++,
                                            'campaign_id'   => $item['campaign_id'],
                                            'businessunit'  => $item['unit_name'],
                                            'campaign'      => $item['campaign_name']
                                        ]);
                                    endforeach;
                                elseif(!empty($biUser->campaigns)):
                                    foreach( $biUser->campaigns as $item):
                                        echo $this->element('rows_bi', [
                                            'key'           => $key++,
                                            'campaign_id'   => $item->id,
                                            'businessunit'  => $item->business_unit->name,
                                            'campaign'      => $item->name
                                        ]);
                                    endforeach;
                                else:
                                    $data = null;
                                endif;
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <script id="row-template" type="text/x-underscore-template">
                            <?= $this->element('rows_bi') ?>
                        </script>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="pull-left">
                        <?= $this->Form->button($this->Html->icon('save') . ' ' . __('Save'), ['class' => 'btn-success', 'escape' => false]) ?>
                    </div>
                    <div class="pull-right">
                        <i>* <?= __('Required fields') ?></i>
                    </div>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function () {
        var ipTable = $('#row-table'),
            ipBody = ipTable.find('tbody'),
            ipTemplate = _.template($('#row-template').remove().text()),
            numberRows = ipTable.find('tbody > tr').length;
        ipTable.on('click', 'a.removeCampaign', function (e) {
            e.preventDefault();
            $(this)
                .closest('tr')
                .fadeOut('fast', function () {
                    $(this).remove();
                });
            $('[data-id=campaign-' + $(this).attr("data-value") + ']').removeClass('disabled');
        });

        $('#menu-select').on('click', 'a.addCampaign', function (e) {
            e.preventDefault();
            if (!$(this).hasClass('disabled')) {
                var campaign_id = $(this).attr("data-value");
                var businessunit = $(this).attr("data-businessunit");
                var campaign = $(this).attr("data-campaign");
                $(this).addClass('disabled');
                $(ipTemplate({
                    key: numberRows++,
                    campaign_id: campaign_id,
                    businessunit: businessunit,
                    campaign: campaign
                }))
                    .hide()
                    .appendTo(ipBody)
                    .fadeIn('fast');
            }
        });
    });
    $("#attachment-photo").change(function(){
        readURL(this, 'avatar');
    });
</script>