<?php
use Cake\Core\Configure;
?>
<ul class="sidebar-menu">
    <li class="bg-sidebar-opts">
        <a href="<?= $this->Url->build('/' . strtolower($current_user['role']['prefix']), true);?>">
            <?= $this->Html->icon('home');?> <span><?= __('Home');?></span></a>
    </li>
    <li class="header"><?= __('Administrator') ?></li>

    <li class="bg-sidebar-opts">
        <?= $this->Html->link($this->Html->icon('home').' '.$this->Html->tag('span', __('Business')),
            ['controller' => 'business'],
            ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
    </li>
    <li class="bg-sidebar-opts">
        <?= $this->Html->link($this->Html->icon('list').' '.$this->Html->tag('span', __('Type Obligations')),
            ['controller' => 'typeObligations'],
            ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
    </li>
    <!-- <li class="bg-sidebar-opts">
        <?php echo $this->Html->link($this->Html->icon('list').' '.$this->Html->tag('span', __('Tasa EA TDC')),
            ['controller' => 'annualEffectiveRateTdc'],
            ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
    </li> -->
    <li class="bg-sidebar-opts">
        <?= $this->Html->link($this->Html->icon('list').' '.$this->Html->tag('span', __('Tasa UVR')),
            ['controller' => 'annualEffectiveRateUvr'],
            ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
    </li>
    <li class="bg-sidebar-opts">
        <?= $this->Html->link($this->Html->icon('map-marker').' '.$this->Html->tag('span', __('cities')),
            ['controller' => 'cityOffices'],
            ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
    </li>

    <li class="bg-sidebar-opts">
        <?= $this->Html->link($this->Html->icon('list').' '.$this->Html->tag('span', __('Tabla Jurídica')),
            ['controller' => 'legalCodes'],
            ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
    </li>
    <li class="bg-sidebar-opts">
        <?= $this->Html->link($this->Html->icon('list').' '.$this->Html->tag('span', __('Códigos Productos')),
            ['controller' => 'productCodes'],
            ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
    </li>

    <li class="bg-sidebar-opts">
        <?= $this->Html->link($this->Html->icon('list').' '.$this->Html->tag('span', __('Códigos CND')),
            ['controller' => 'cndCodes'],
            ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
    </li>
    
    <li class="bg-sidebar-opts">
        <?= $this->Html->link($this->Html->icon('list').' '.$this->Html->tag('span', __('Razón de la Negociación')),
            ['controller' => 'negotiationReason'],
            ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
    </li>

    <li class="treeview">
        <a href="#">
            <?=$this->Html->icon('file')?>
            <span><?=__('Conditions')?></span>
        </a>
        <ul class="treeview-menu">
            <!--<li>
                <?= $this->Html->link($this->Html->icon('dot-circle-o').' '.$this->Html->tag('span', __('Tasa piso')),
                    ['controller' => 'conditions','action'=>'index','type_condition_id'=>\App\Model\Table\TypesConditionsTable::TASAPISO],
                ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
            </li>
            <li>
                <?= $this->Html->link($this->Html->icon('dot-circle-o').' '.$this->Html->tag('span', __('Tiempo Normalización')),
                ['controller' => 'conditions','action'=>'index','type_condition_id'=>\App\Model\Table\TypesConditionsTable::NORMALIZACION],
                ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
            </li>-->
            <li>
                <?= $this->Html->link($this->Html->icon('dot-circle-o').' '.$this->Html->tag('span', __('Tiempo Rediferido')),
                ['controller' => 'conditions','action'=>'index','type_condition_id'=>\App\Model\Table\TypesConditionsTable::REDIFERIDO],
                ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
            </li>
            <li>
                <?= $this->Html->link($this->Html->icon('dot-circle-o').' '.$this->Html->tag('span', __('Tasa Anual Castigada')),
                    ['controller' => 'conditions','action'=>'index','type_condition_id'=>\App\Model\Table\TypesConditionsTable::TASAANUALCASTIGADA],
                    ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
            </li>
            <li>
                <?= $this->Html->link($this->Html->icon('dot-circle-o').' '.$this->Html->tag('span', __('Porcentaje de Condonación')),
                    ['controller' => 'conditions','action'=>'index','type_condition_id'=>\App\Model\Table\TypesConditionsTable::PORCENTAJECONDONACION],
                    ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
            </li>
            <li>
                <?= $this->Html->link($this->Html->icon('dot-circle-o').' '.$this->Html->tag('span', __('Porcentaje de disminución')),
                    ['controller' => 'conditions','action'=>'index','type_condition_id'=>\App\Model\Table\TypesConditionsTable::PORCENTAJEDISMINUCION],
                    ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
            </li>
            <li>
                <?= $this->Html->link($this->Html->icon('dot-circle-o').' '.$this->Html->tag('span', __('Politica acierta')),
                    ['controller' => 'conditions','action'=>'index','type_condition_id'=>\App\Model\Table\TypesConditionsTable::POLITICAACIERTA],
                    ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
            </li>

            <li>
                <?= $this->Html->link($this->Html->icon('dot-circle-o').' '.$this->Html->tag('span', __('Tasa desvalorización')),
                    ['controller' => 'conditions','action'=>'index','type_condition_id'=>\App\Model\Table\TypesConditionsTable::TASADESVALORIZACION],
                    ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
            </li>
            <li class="divider">

            </li>
            <li>
                <?= $this->Html->link($this->Html->icon('dot-circle-o').' '.$this->Html->tag('span', __('Condonación vigente')),
                    ['controller' => 'conditions','action'=>'index','type_condition_id'=>\App\Model\Table\TypesConditionsTable::CONDONACIONVIGENTE],
                    ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
            </li>
            <li>
                <?= $this->Html->link($this->Html->icon('dot-circle-o').' '.$this->Html->tag('span', __('Condonación castigada')),
                    ['controller' => 'conditions','action'=>'index','type_condition_id'=>\App\Model\Table\TypesConditionsTable::CONDONACIONCASTIGADA],
                    ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
            </li>

            <li>
                <?= $this->Html->link($this->Html->icon('dot-circle-o').' '.$this->Html->tag('span', __('Zona gris ISF')),
                    ['controller' => 'conditions','action'=>'index','type_condition_id'=>\App\Model\Table\TypesConditionsTable::ZONAGRISISF],
                    ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
            </li>

            <li>
                <?= $this->Html->link($this->Html->icon('dot-circle-o').' '.$this->Html->tag('span', __('% Pago sugerido')),
                    ['controller' => 'conditions','action'=>'index','type_condition_id'=>\App\Model\Table\TypesConditionsTable::PORCENTAJEPAGOSUGERIDO],
                    ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
            </li>
            <li>
                <?= $this->Html->link($this->Html->icon('dot-circle-o').' '.$this->Html->tag('span', __('Normalización Expres')),
                    ['controller' => 'conditions','action'=>'index','type_condition_id'=>\App\Model\Table\TypesConditionsTable::NORMALIZACIONEXPRES],
                    ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
            </li>
            <li>
                <?= $this->Html->link($this->Html->icon('dot-circle-o').' '.$this->Html->tag('span', __('ACPK Expres')),
                    ['controller' => 'conditions','action'=>'index','type_condition_id'=>\App\Model\Table\TypesConditionsTable::ACPKEXPRES],
                    ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
            </li>
        </ul>
    </li>

    <li class="bg-sidebar-opts">
        <?= $this->Html->link($this->Html->icon('list').' '.$this->Html->tag('span', __('Mensajes Modal')),
            ['controller' => 'modalMessage'],
            ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
    </li>
    <li class="header"><?=__('Users and group access');?></li>
    <li class="bg-sidebar-opts">
        <?= $this->Html->link($this->Html->icon('users').' '.$this->Html->tag('span', __('Users')),
            ['controller' => 'users'],
            ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
    </li>
    <li class="bg-sidebar-opts">
        <?= $this->Html->link($this->Html->icon('ban').' '.$this->Html->tag('span', __('Access Groups')),
            ['controller' => 'access_groups'],
            ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
    </li>
    <?php if (Configure::read('debug')): ?>
    <li class="bg-sidebar-opts">
        <?= $this->Html->link($this->Html->icon('user-secret').' '.$this->Html->tag('span', __('Roles')),
            ['controller' => 'roles'],
            ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
    </li>
    <li class="bg-sidebar-opts">
        <?= $this->Html->link($this->Html->icon('list').' '.$this->Html->tag('span', __('Type Identifications')),
            ['controller' => 'customerTypeIdentifications'],
            ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
    </li>
    <?php endif; ?>
    <li class="bg-sidebar-opts">
        <?= $this->Html->link($this->Html->icon('list').' '.$this->Html->tag('span', __('State reasons')),
            ['controller' => 'stateReasons'],
            ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
    </li>
    <li class="header"><?=__('Configuration');?></li>
    <?php if (Configure::read('debug')): ?>
        <li class="bg-sidebar-opts">
            <?= $this->Html->link($this->Html->icon('cogs').' '.$this->Html->tag('span', __('Setting Categories')),
                ['controller' => 'setting_categories'],
                ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
        </li>
        <li class="bg-sidebar-opts">
            <?= $this->Html->link($this->Html->icon('cog').' '.$this->Html->tag('span', __('Settings')),
                ['controller' => 'settings'],
                ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
        </li>
        <li class="bg-sidebar-opts">
            <?= $this->Html->link($this->Html->icon('cog').' '.$this->Html->tag('span', __('Adjust Settings')),
                ['controller' => 'settings', 'action' => 'update'],
                ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
        </li>
    <?php else: ?>
        <li class="bg-sidebar-opts">
            <?= $this->Html->link($this->Html->icon('cog').' '.$this->Html->tag('span', __('Settings')),
                ['controller' => 'settings'],
                ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
        </li>
        <li class="bg-sidebar-opts">
            <?= $this->Html->link($this->Html->icon('cog').' '.$this->Html->tag('span', __('Adjust Settings')),
                ['controller' => 'settings', 'action' => 'update'],
                ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
        </li>
    <?php endif; ?>
    <li class="header"><?=__('Logs');?></li>
    <li class="bg-sidebar-opts">
        <?= $this->Html->link($this->Html->icon('dot-circle-o').' '.$this->Html->tag('span', __('Sessions')),
            ['controller' => 'access_logs'],
            ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
    </li>
    <li class="bg-sidebar-opts">
        <?= $this->Html->link($this->Html->icon('dot-circle-o').' '.$this->Html->tag('span', __('Audit')),
            ['controller' => 'audits'],
            ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
    </li>
    <li class="bg-sidebar-opts">
        <?= $this->Html->link($this->Html->icon('dot-circle-o').' '.$this->Html->tag('span', __('Ticket')),
            ['controller' => 'tickets'],
            ['class' => 'load-ajax', 'update' => '#page-content-wrapper', 'escape' => false]) ?>
    </li>
</ul>