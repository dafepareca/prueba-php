<?php
$this->Paginator->templates([
    'sort' => '<a href="{{url}}" class="load-ajax" update="#page-content-wrapper">{{text}}</a>',
    'sortAsc' => '<a class="asc load-ajax" href="{{url}}" update="#page-content-wrapper">{{text}}</a>',
    'sortDesc' => '<a class="desc load-ajax" href="{{url}}" update="#page-content-wrapper">{{text}}</a>',

    'first' => '<li><a href="{{url}}" class="load-ajax" update="#page-content-wrapper">{{text}}</a></li>',
    'last' => '<li><a href="{{url}}" class="load-ajax" update="#page-content-wrapper">{{text}}</a></li>',
    'number' => '<li><a href="{{url}}" class="load-ajax" update="#page-content-wrapper">{{text}}</a></li>',
    'counterPages' => 'Page {{page}} of {{pages}}, showing {{current}} records out of {{count}} total'
]);
?>