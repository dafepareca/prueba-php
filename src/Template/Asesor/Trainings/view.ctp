<?php
/** @var  $training \App\Model\Entity\Trainings */
/** @var  $Html \Cake\View\Helper\HtmlHelper*/
?>
<div class="modal-header modal-header-info">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><?= $this->Html->icon('info-circle').' '.__('Training Information') ?></h4>
</div>
<!-- /modal-header -->
<div class="modal-body">
    <dl class="dl-horizontal">
        <dt><?= __('Name') ?></dt>
        <dd><?= h($training->name) ?></dd>
        <dt><?= __('Description') ?></dt>
        <dd><?= $this->Text->autoParagraph(h($training->description)) ?></dd>
    </dl>

    <?php if(!empty($training->training_resources)): ?>
    <h4>Recursos</h4>
    <?php foreach ($training->training_resources as $resource): ?>
        <?php if($resource->file_type == 'video/mp4'): ?>

                <video width="100%" controls controlsList="nodownload" style="margin-bottom: 40px">
                    <source src="img/<?php echo $resource->file_dir.$resource->resource?>" type="video/mp4">
                    Your browser does not support HTML5 video.
                </video>

        <?php endif; ?>

            <?php if($resource->file_type == 'application/pdf'): ?>
                <embed style="margin-bottom: 40px" src="img/<?php echo $resource->file_dir.$resource->resource?>#toolbar=0" width="100%" height="600"
                       type="application/pdf">
            <?php endif; ?>

    <?php endforeach; ?>
    <?php endif; ?>
</div>

</div>
<!-- /.modal-body -->
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Close') ?></button>
</div>
<!-- /modal-footer -->