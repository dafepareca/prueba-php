<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
    <title><?= $this->fetch('title') ?></title>
</head>
<body>
<?php
$colorTemplate = '#fc0204';
$colorFontTemplate = '#222d32';
$cliente = 'Davivienda';
?>
<center>
    <table cellpadding="8" cellspacing="0" style="background:#ffffff;width:100%!important;margin:0;padding:0"
           border="0">
        <tbody>
        <tr>
            <td valign="top">
                <table cellpadding="0" cellspacing="0" style="border-radius:4px;border:0px <?= $colorTemplate; ?> solid"
                       border="0" align="center">
                    <tbody>
                    <tr>
                        <td colspan="3" height="6"></td>
                    </tr>
                    <tr style="line-height:0px">
                        <td colspan="3" style="font-size:0px" align="center" height="1">
                            <?= $this->Html->image('email/head.png', ['fullBase' => true]); ?>
                        </td>
                    </tr>
                    <tr>

                    <tr>
                        <td colspan="3" height="30"></td>
                    </tr>
                    <tr>

                        <td colspan="3" width="454" align="left"
                            style="color: <?= $colorFontTemplate ?>;border-collapse:collapse;font-size:11pt;font-family:'Open Sans',Arial,Verdana,Tahoma,'Sans Serif';max-width:454px"
                            valign="top">
                            <?= $this->fetch('content'); ?>
                            <br>
                            <br>
                            <br>
                            <br>
                            <?php if (isset($link)): ?>
                                <center><a
                                            style="border-radius:3px;color:white;font-size:15px;padding:14px 7px 14px 7px;max-width:240px;font-family:'Open Sans','lucida grande','Segoe UI',arial,verdana,'lucida sans unicode',tahoma,sans-serif;border:1px <?= $colorTemplate; ?> solid;text-align:center;text-decoration:none;width:240px;margin:6px auto;display:block;background-color:<?= $colorTemplate; ?>"
                                            href="<?= $link ?>" target="_blank">IR A <?php echo $cliente; ?></a></center>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <!--<tr>
                                    <td style="padding: 25px;" colspan="3">
                                        Cordialmente <br>
                                        - Equipo <? /*=$cliente*/ ?>
                                    </td>
                                </tr>-->
                    <tr>
                        <td colspan="3" height="30"></td>
                    </tr>
                    <tr style="line-height:0px">
                        <td colspan="3" style="font-size:0px" align="center" height="1">
                            <?= $this->Html->image('email/footer.png', ['fullBase' => true]); ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" height="36"></td>
                    </tr>
                    </tr>
                    </tbody>
                </table>

            </td>
        </tr>
        </tbody>
    </table>
</center>
</body>
</html>