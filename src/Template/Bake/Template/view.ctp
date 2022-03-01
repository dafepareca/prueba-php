<%
use Cake\Utility\Inflector;
$associations += ['BelongsTo' => [], 'HasOne' => [], 'HasMany' => [], 'BelongsToMany' => []];
$immediateAssociations = $associations['BelongsTo'];
$associationFields = collection($fields)->map(function($field) use ($immediateAssociations) {
    foreach ($immediateAssociations as $alias => $details) {
        if ($field === $details['foreignKey']) {
            return [$field => $details];
        }
    }
})
->filter()
->reduce(function($fields, $value) {
return $fields + $value;
}, []);

$groupedFields = collection($fields)->filter(function($field) use ($schema) {
    return $schema->columnType($field) !== 'binary';
})
->groupBy(function($field) use ($schema, $associationFields) {
    $type = $schema->columnType($field);
    if (isset($associationFields[$field])) {
        return 'string';
    }
    if (in_array($type, ['integer', 'float', 'decimal', 'biginteger'])) {
        return 'number';
    }
    if (in_array($type, ['date', 'time', 'datetime', 'timestamp'])) {
        return 'date';
    }
    return in_array($type, ['text', 'boolean']) ? $type : 'string';
})->toArray();
$groupedFields += ['number' => [], 'string' => [], 'boolean' => [], 'date' => [], 'text' => []];
$pk = "\$$singularVar->{$primaryKey[0]}";
%>
<?php
/** @var  $<%= $singularVar %> \App\Model\Entity\<%= $pluralHumanName %> */
/** @var  $Html \Cake\View\Helper\HtmlHelper*/
?>
<div class="modal-header modal-header-info">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><?= $this->Html->icon('info-circle').' '.__('<%= $singularHumanName %> Information') ?></h4>
</div>
<!-- /modal-header -->
<div class="modal-body">
    <dl class="dl-horizontal">
<% if ($groupedFields['string']) : %>
<% foreach ($groupedFields['string'] as $field) : %>
<% if (isset($associationFields[$field])) : %>
<% $details = $associationFields[$field]; %>
        <dt><?= __('<%= Inflector::humanize($details['property']) %>') ?></dt>
        <dd><?= $<%= $singularVar %>->has('<%= $details['property'] %>') ? $<%= $singularVar %>-><%= $details['property'] %>-><%= $details['displayField'] %> : '' ?></dd>
<% else : %>
<% if ($field != 'password') :%>
        <dt><?= __('<%= Inflector::humanize($field) %>') ?></dt>
        <dd><?= h($<%= $singularVar %>-><%= $field %>) ?></dd>
<% endif; %>
<% endif; %>
<% endforeach; %>
<% endif; %>
<% if ($associations['HasOne']) : %>
<% foreach ($associations['HasOne'] as $alias => $details) : %>
        <dt><?= __('<%= Inflector::humanize(Inflector::singularize(Inflector::underscore($alias))) %>') ?></dt>
        <dd><?= $<%= $singularVar %>->has('<%= $details['property'] %>') ? $this->Html->link($<%= $singularVar %>-><%= $details['property'] %>-><%= $details['displayField'] %>, ['controller' => '<%= $details['controller'] %>', 'action' => 'view', $<%= $singularVar %>-><%= $details['property'] %>-><%= $details['primaryKey'][0] %>]) : '' ?></dd>
<% endforeach; %>
<% endif; %>
<% if ($groupedFields['number']) : %>
<% foreach ($groupedFields['number'] as $field) : %>
<% if ($field != $primaryKey[0]) :%>
        <dt><?= __('<%= Inflector::humanize($field) %>') ?></dt>
        <dd><?= $this->Number->format($<%= $singularVar %>-><%= $field %>) ?></dd>
<% endif; %>
<% endforeach; %>
<% endif; %>
<% if ($groupedFields['boolean']) : %>
<% foreach ($groupedFields['boolean'] as $field) : %>
        <dt><?= __('<%= Inflector::humanize($field) %>') ?></dt>
        <dd><?= $<%= $singularVar %>-><%= $field %> ? __('Yes') : __('No') ?></dd>
<% endforeach; %>
<% endif; %>
<% if ($groupedFields['text']) : %>
<% foreach ($groupedFields['text'] as $field) : %>
        <dt><?= __('<%= Inflector::humanize($field) %>') ?></dt>
        <dd><?= $this->Text->autoParagraph(h($<%= $singularVar %>-><%= $field %>)) ?></dd>
<% endforeach; %>
<% endif; %>
<% if ($groupedFields['date']) : %>
<% foreach ($groupedFields['date'] as $field) : %>
<% //if (!in_array($field, ['created', 'modified', 'updated'])) : %>
        <dt><%= "<%= __('" . Inflector::humanize($field) . "') %>" %></dt>
        <dd><?= $<%= $singularVar %>-><%= $field %>->format('Y-m-d h:i:s A') ?></dd>
<% //endif; %>
<% endforeach; %>
<% endif; %>
    </dl>
</div>
<!-- /.modal-body -->
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Close') ?></button>
</div>
<!-- /modal-footer -->