<ul class="sidebar-menu">
    <li class="header"><?= __('Administrator') ?></li>
    <li class="treeview">
        <a href="#">
            <?=$this->Html->icon('dashboard')?> <span>Dashboard</span> <?=$this->Html->icon('chevron-left pull-right')?>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?php echo $this->Url->build('/'); ?>"><?=$this->Html->icon('dot-circle-o')?> Dashboard v1</a></li>
            <li><a href="<?php echo $this->Url->build('/pages/home2'); ?>"><?=$this->Html->icon('dot-circle-o')?> Dashboard v2</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <?=$this->Html->icon('file')?>
            <span>Layout Options</span>
            <span class="label label-primary pull-right">4</span>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?php echo $this->Url->build('/pages/layout/top-nav'); ?>"><?=$this->Html->icon('dot-circle-o')?> Top Navigation</a></li>
            <li><a href="<?php echo $this->Url->build('/pages/layout/boxed'); ?>"><?=$this->Html->icon('dot-circle-o')?> Boxed</a></li>
            <li><a href="<?php echo $this->Url->build('/pages/layout/fixed'); ?>"><?=$this->Html->icon('dot-circle-o')?> Fixed</a></li>
            <li><a href="<?php echo $this->Url->build('/pages/layout/collapsed-sidebar'); ?>"><?=$this->Html->icon('dot-circle-o')?> Collapsed Sidebar</a></li>
        </ul>
    </li>
    <li>
        <a href="<?php echo $this->Url->build('/pages/widgets'); ?>">
            <?=$this->Html->icon('th')?> <span>Widgets</span>
            <small class="label pull-right bg-green">Hot</small>
        </a>
    </li>
    <li class="treeview">
        <a href="#">
            <?=$this->Html->icon('pie-chart')?>
            <span>Charts</span>
            <?=$this->Html->icon('chevron-left pull-right')?>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?php echo $this->Url->build('/pages/charts/chartjs'); ?>"><?=$this->Html->icon('dot-circle-o')?> ChartJS</a></li>
            <li><a href="<?php echo $this->Url->build('/pages/charts/morris'); ?>"><?=$this->Html->icon('dot-circle-o')?> Morris</a></li>
            <li><a href="<?php echo $this->Url->build('/pages/charts/flot'); ?>"><?=$this->Html->icon('dot-circle-o')?> Flot</a></li>
            <li><a href="<?php echo $this->Url->build('/pages/charts/inline'); ?>"><?=$this->Html->icon('dot-circle-o')?> Inline charts</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-laptop"></i>
            <span>UI Elements</span>
            <?=$this->Html->icon('chevron-left pull-right')?>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?php echo $this->Url->build('/pages/ui/general'); ?>"><?=$this->Html->icon('dot-circle-o')?> General</a></li>
            <li><a href="<?php echo $this->Url->build('/pages/ui/icons'); ?>"><?=$this->Html->icon('dot-circle-o')?> Icons</a></li>
            <li><a href="<?php echo $this->Url->build('/pages/ui/buttons'); ?>"><?=$this->Html->icon('dot-circle-o')?> Buttons</a></li>
            <li><a href="<?php echo $this->Url->build('/pages/ui/sliders'); ?>"><?=$this->Html->icon('dot-circle-o')?> Sliders</a></li>
            <li><a href="<?php echo $this->Url->build('/pages/ui/timeline'); ?>"><?=$this->Html->icon('dot-circle-o')?> Timeline</a></li>
            <li><a href="<?php echo $this->Url->build('/pages/ui/modals'); ?>"><?=$this->Html->icon('dot-circle-o')?> Modals</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-edit"></i> <span>Forms</span>
            <?=$this->Html->icon('chevron-left pull-right')?>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?php echo $this->Url->build('/pages/forms/general'); ?>"><?=$this->Html->icon('dot-circle-o')?> General Elements</a></li>
            <li><a href="<?php echo $this->Url->build('/pages/forms/advanced'); ?>"><?=$this->Html->icon('dot-circle-o')?> Advanced Elements</a></li>
            <li><a href="<?php echo $this->Url->build('/pages/forms/editors'); ?>"><?=$this->Html->icon('dot-circle-o')?> Editors</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-table"></i> <span>Tables</span>
            <?=$this->Html->icon('chevron-left pull-right')?>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?php echo $this->Url->build('/pages/tables/simple'); ?>"><?=$this->Html->icon('dot-circle-o')?> Simple tables</a></li>
            <li><a href="<?php echo $this->Url->build('/pages/tables/data'); ?>"><?=$this->Html->icon('dot-circle-o')?> Data tables</a></li>
        </ul>
    </li>
    <li>
        <a href="<?php echo $this->Url->build('/pages/calendar'); ?>">
            <i class="fa fa-calendar"></i> <span>Calendar</span>
            <small class="label pull-right bg-red">3</small>
        </a>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-envelope"></i> <span>Mailbox</span>
            <small class="label pull-right bg-yellow">12</small>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?php echo $this->Url->build('/pages/mailbox/mailbox'); ?>">Inbox <span class="label label-primary pull-right">13</span></a></li>
            <li><a href="<?php echo $this->Url->build('/pages/mailbox/compose'); ?>">Compose</a></li>
            <li><a href="<?php echo $this->Url->build('/pages/mailbox/read-mail'); ?>">Read</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-folder"></i> <span>Examples</span>
            <?=$this->Html->icon('chevron-left pull-right')?>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?php echo $this->Url->build('/pages/starter'); ?>"><?=$this->Html->icon('dot-circle-o')?> Starter</a></li>
            <li><a href="<?php echo $this->Url->build('/pages/examples/invoice'); ?>"><?=$this->Html->icon('dot-circle-o')?> Invoice</a></li>
            <li><a href="<?php echo $this->Url->build('/pages/examples/profile'); ?>"><?=$this->Html->icon('dot-circle-o')?> Profile</a></li>
            <li><a href="<?php echo $this->Url->build('/pages/examples/login'); ?>"><?=$this->Html->icon('dot-circle-o')?> Login</a></li>
            <li><a href="<?php echo $this->Url->build('/pages/examples/register'); ?>"><?=$this->Html->icon('dot-circle-o')?> Register</a></li>
            <li><a href="<?php echo $this->Url->build('/pages/examples/lockscreen'); ?>"><?=$this->Html->icon('dot-circle-o')?> Lockscreen</a></li>
            <li><a href="<?php echo $this->Url->build('/pages/examples/404'); ?>"><?=$this->Html->icon('dot-circle-o')?> 404 Error</a></li>
            <li><a href="<?php echo $this->Url->build('/pages/examples/500'); ?>"><?=$this->Html->icon('dot-circle-o')?> 500 Error</a></li>
            <li><a href="<?php echo $this->Url->build('/pages/examples/blank'); ?>"><?=$this->Html->icon('dot-circle-o')?> Blank Page</a></li>
            <li><a href="<?php echo $this->Url->build('/pages/examples/pace'); ?>"><?=$this->Html->icon('dot-circle-o')?> Pace Page</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-share"></i> <span>Multilevel</span>
            <?=$this->Html->icon('chevron-left pull-right')?>
        </a>
        <ul class="treeview-menu">
            <li><a href="#"><?=$this->Html->icon('dot-circle-o')?> Level One</a></li>
            <li>
                <a href="#"><?=$this->Html->icon('dot-circle-o')?> Level One <?=$this->Html->icon('chevron-left pull-right')?></a>
                <ul class="treeview-menu">
                    <li><a href="#"><?=$this->Html->icon('dot-circle-o')?> Level Two</a></li>
                    <li>
                        <a href="#"><?=$this->Html->icon('dot-circle-o')?> Level Two <?=$this->Html->icon('chevron-left pull-right')?></a>
                        <ul class="treeview-menu">
                            <li><a href="#"><?=$this->Html->icon('dot-circle-o')?> Level Three</a></li>
                            <li><a href="#"><?=$this->Html->icon('dot-circle-o')?> Level Three</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li><a href="#"><?=$this->Html->icon('dot-circle-o')?> Level One</a></li>
        </ul>
    </li>
    <li><a href="<?php echo $this->Url->build('/pages/documentation'); ?>"><i class="fa fa-book"></i> <span>Documentation</span></a></li>
    <li class="header">LABELS</li>
    <li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>Important</span></a></li>
    <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> <span>Warning</span></a></li>
    <li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>Information</span></a></li>
    <li><a href="<?php echo $this->Url->build('/pages/debug'); ?>"><i class="fa fa-bug"></i><span>Debug</span></a></li>
</ul>