<%
use Cake\Utility\Inflector;
%>
<?php
/** @var  $<%= $singularVar %> \App\Model\Entity\<%= $singularHumanName %> */
/** @var  $Html \Cake\View\Helper\HtmlHelper */
?>
<section class="content-header">
    <h1 class="pull-left"><?= $this->Html->icon('list') . ' ' . __('<%= $pluralHumanName %>') ?> |
        <small><?= __('<%= Inflector::humanize($action) %>') ?></small>
    </h1>
    <div class="btn-group pull-right">
        <?= $this->Html->link($this->Html->icon('chevron-left') . ' ' . $this->Html->tag('span', __('Back'), ['class' => 'hidden-xs hidden-sm']),
            ['action' => 'index'], ['escape' => false, 'class' => 'load-ajax btn btn-sm btn-default', 'update' => '#page-content-wrapper', 'title' => __('Back')]); ?>
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
                    <h3 class="box-title"><?= __('<%= $singularHumanName %> Information') ?></h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <?= $this->Form->create($<%= $singularVar %>, [
                    'class' => 'form-ajax',
                    'update' => '#page-content-wrapper',
                    'horizontal' => true
                ]) ?>
                <div class="box-body">
<% foreach ($fields as $field):
if (in_array($field, $primaryKey)) {
continue;
}
if (isset($keyFields[$field])) {
$fieldData = $schema->column($field);
if (!empty($fieldData['null'])) {
%>
                    <?= $this->Form->input('<%= $field %>', ['options' => $<%= $keyFields[$field] %>, 'empty' => true]) ?>
<%
} else {
%>
                    <?= $this->Form->input('<%= $field %>', ['options' => $<%= $keyFields[$field] %>]) ?>
<%
}
continue;
}
if (!in_array($field, ['created', 'modified', 'updated'])) {
$fieldData = $schema->column($field);
$type = $fieldData['type'];
if (($fieldData['type'] === 'date') && (!empty($fieldData['null']))) {
%>
                    <?= $this->Form->input('<%= $field %>', ['empty' => true, 'default' => '']) ?>
<%
} elseif ($fieldData['type'] === 'text') {
%>
                    <?= $this->Form->input('<%= $field %>', ['type' => 'textarea']) ?>
<%
} else {
%>
                    <?= $this->Form->input('<%= $field %>') ?>
<%
}
}
endforeach;
if (!empty($associations['BelongsToMany'])) {
foreach ($associations['BelongsToMany'] as $assocName => $assocData) {
%>
                    <?= $this->Form->input('<%= $assocData['property'] %>._ids', ['options' => $<%= $assocData['variable'] %>]) ?>
<%
}
}
%>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="pull-left">
                        <?= $this->Form->button($this->Html->icon('save').' '.__('Save'), ['class' => 'btn-success', 'escape' => false]) ?>
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