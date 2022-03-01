<?php
use Cake\Routing\Router;
/** @var  $<%=$pluralVar%> \App\Model\Entity\<%= $singularHumanName %>[] */
/** @var  $Html \Cake\View\Helper\HtmlHelper*/
?>
<%
use Cake\Utility\Inflector;
$fields = collection($fields)
->filter(function($field) use ($schema) {
return !in_array($schema->columnType($field), ['binary', 'text']);
})
->take(7);
%>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="pull-left"><?= $this->Html->icon('list') . ' ' . __('<%= $pluralHumanName %>') ?></h1>
    <div class="btn-group pull-right">
        <?= $this->Html->link($this->Html->icon('plus') . ' ' . $this->Html->tag('span', __('Add'), ['class' => 'hidden-xs hidden-sm']),
            ['action' => 'add'],
            ['update' => '#page-content-wrapper', 'escape' => false, 'class' => 'load-ajax btn btn-sm btn-default', 'title' => __('Add')]) ?>
        <?= $this->Html->link($this->Html->icon('refresh') . ' ' . $this->Html->tag('span', __('Refresh'), ['class' => 'hidden-xs hidden-sm']),
            ['action' => 'index'],
            ['update' => '#page-content-wrapper', 'escape' => false, 'class' => 'load-ajax btn btn-sm btn-default', 'title' => __('Refresh')]) ?>
    </div>
    <div class="clearfix"></div>
</section>

<!-- Main content -->
<?= $this->element('paging_options') ?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <?= $this->element('search/generic') ?>
                    <?= $this->element('paging_links') ?>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover table-condensed table-striped table-bordered">
                        <thead>
                            <tr>
                                <th width="30"></th>
<% foreach ($fields as $field):
if (!in_array($field, ['id', 'created', 'modified', 'updated'])) :%>
                                <th><?= $this->Paginator->sort('<%= $field %>') ?></th>
<% endif; %>
<% endforeach; %>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($<%=$pluralVar%> as $<%=$singularVar%>): ?>
<% $pk = '$' . $singularVar . '->' . $primaryKey[0]; %>
                            <tr>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-xs btn-info dropdown-toggle" data-toggle="dropdown">
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu flat">
                                            <li>
                                                <?= $this->Html->link($this->Html->icon('edit').' '.__('Edit'), ['action' => 'edit', <%= $pk %>],
                                                ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
                                            </li>
                                            <li>
                                                <?= $this->Html->link($this->Html->icon('eye').' '.__('View'), ['action' => 'view', <%= $pk %>],
                                                ['data-toggle' => 'modal', 'data-target' => '#viewModal', 'escape' => false]) ?>
                                            </li>
                                            <li>
                                                <?= $this->Html->link($this->Html->icon('trash').' '.__('Delete'),
                                                ['action' => '#'],
                                                ['escape' => false,
                                                    'class' => 'btn-confirm',
                                                    'data-id' => <%= $pk %>,
                                                    'data-toggle' => 'modal',
                                                    'data-target' => '#ConfirmDelete',
                                                    'data-action' => Router::url(['action' => 'delete', <%= $pk %>], false),]
                                                ) ?>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
<% foreach ($fields as $field) {
if (!in_array($field, ['id','created', 'modified', 'updated'])) {
$isKey = false;
if (!empty($associations['BelongsTo'])) {
foreach ($associations['BelongsTo'] as $alias => $details) {
if ($field === $details['foreignKey']) {
$isKey = true;
%>
                                <td><?= $<%= $singularVar %>->has('<%= $details['property'] %>') ? $this->Html->link($<%= $singularVar %>-><%= $details['property'] %>-><%= $details['displayField'] %>, ['controller' => '<%= $details['controller'] %>', 'action' => 'view', $<%= $singularVar %>-><%= $details['property'] %>-><%= $details['primaryKey'][0] %>]) : '' ?></td>
<%
break;
}
}
}
if ($isKey !== true) {
if (!in_array($schema->columnType($field), ['integer', 'biginteger', 'decimal', 'float'])) {
%>
                                <td><?= h($<%= $singularVar %>-><%= $field %>) ?></td>
<%
} else {
%>
                                <td><?= $this->Number->format($<%= $singularVar %>-><%= $field %>) ?></td>
<%
}
}
}
}
%>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix">
                    <?= $this->element('paging_counter') ?>
                </div>
            </div>
            <!-- /.box -->
        </div>
    </div>
</section>
<!-- /.content -->