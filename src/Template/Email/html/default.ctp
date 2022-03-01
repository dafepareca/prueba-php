<?php
$settings = \Cake\Cache\Cache::read('settings', 'long');
$this->port = $settings['FileRepository']['port'];
$colorTemplate = '#222d32';
$colorFontTemplate = '#222d32';
?>
<center><table cellpadding="8" cellspacing="0" style="background:#ffffff;width:100%!important;margin:0;padding:0" border="0"><tbody><tr>
            <td valign="top">
                <table cellpadding="0" cellspacing="0" style="border-radius:4px;border:1px <?= $colorTemplate;?> solid" border="0" align="center"><tbody><tr><td colspan="3" height="6"></td></tr><tr style="line-height:0px"><td width="100%" style="font-size:0px" align="center" height="1">
                            <?= $this->Html->image('logo.jpg', []); ?>
                        </td></tr><tr><td><table cellpadding="0" cellspacing="0" style="line-height:25px" border="0" align="center">
                                <tbody><tr><td colspan="3" height="30"></td></tr><tr><td width="36"></td>
                                    <td width="454" align="left" style="color: <?= $colorFontTemplate?>;border-collapse:collapse;font-size:11pt;font-family:'Open Sans',Arial,Verdana,Tahoma,'Sans Serif';max-width:454px" valign="top">
                                        <?php  echo $message?>
                                        <br>

                                        <?php if(isset($url)):?>
                                            <center><a style="border-radius:3px;color:white;font-size:15px;padding:14px 7px 14px 7px;max-width:200px;font-family:'Open Sans','lucida grande','Segoe UI',arial,verdana,'lucida sans unicode',tahoma,sans-serif;border:1px <?= $colorTemplate;?> solid;text-align:center;text-decoration:none;width:200px;margin:6px auto;display:block;background-color:<?= $colorTemplate;?>" href="<?= $url?>" target="_blank"><?= $label?></a></center>
                                        <?php endif;?>
                                        <br>
                                        Cordialmente <br>
                                        - Equipo <?= $settings['Site']['name'] ?>

                                    </td>
                                    <td width="36"></td>
                                </tr><tr><td colspan="3" height="36"></td></tr></tbody></table></td></tr></tbody></table><table cellpadding="0" cellspacing="0" align="center" border="0"><tbody><tr><td height="10"></td></tr>

                    <tr>
                        <td style="padding:0;border-collapse:collapse">
                            <table cellpadding="0" cellspacing="0" align="center" border="0">
                                <tbody>
                                <tr style="color:#a8b9c6;font-size:11px;font-family:'Open Sans','Lucida Grande','Segoe UI',Arial,Verdana,'Lucida Sans Unicode',Tahoma,'Sans Serif'">
                                    <td width="300" align="left"></td>
                                    <td width="228" align="right" style="color:<?= $colorTemplate;?>;text-align:right"><?= date('Y')?> <a style="text-decoration: none;color:<?= $colorTemplate;?>;" href="<?=  $this->Url->build('/', true)?>" target="_blank" style="color:<?= $colorTemplate;?>;"><?=  $this->Url->build('/', true)?></a></td>
                                </tr>
                                </tbody>
                            </table>

                        </td>
                    </tr>
                    </tbody></table></td></tr></tbody></table></center>