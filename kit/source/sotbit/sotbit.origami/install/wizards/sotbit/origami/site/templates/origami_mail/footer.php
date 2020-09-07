<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?

global $serverName;
global $phone;
global $email;

CModule::IncludeModule('sotbit.origami');
use \Sotbit\Origami\Config\Option;

$vk = Option::get('VK', SITE_ID);
$fb = Option::get('FB', SITE_ID);
$inst = Option::get('INST', SITE_ID);
$youtube = Option::get('YOUTUBE', SITE_ID);
$telega = Option::get('TELEGA', SITE_ID);
$tw = Option::get('TW', SITE_ID);
$ok = Option::get('OK', SITE_ID);
$google = Option::get('GOOGLE', SITE_ID);
?>

</td>
</tr>
</table>
<table width="100%" height="255px" align="center" cellpadding="0" cellspacing="0" border="0"
       bgcolor="#000000" style="padding-top: 40px; padding-right: 40px; padding-left: 40px;">
    <tr>
        <td valign="top">
            <div style="color: #ffffff; font-family: 'Open Sans', sans-serif; font-size: 15px; font-weight: bold; padding-bottom: 25px;">
                <?=GetMessage('SOCIAL');?>
            </div>
            <table align="left" cellpadding="0" cellspacing="0" border="0" bgcolor="#000000">
                <tr>
                    <? if($vk){?>
                    <td width="40" style="text-align: center;">
                        <a href="<?=$vk?>"><img src="/local/templates/origami_mail/img/vk.png" alt="" border="0" width="30" height="30"
                                        style="display:block;text-decoration:none;outline:none;"></a>
                    </td>
                    <?}?>
                    <? if($tw){?>
                    <td width="40">
                        <a href="<?=$tw?>"><img src="/local/templates/origami_mail/img/tw.png" alt="" border="0" width="30" height="30"
                                        style="display:block;text-decoration:none;outline:none;"></a>
                    </td>
                    <?}?>
                    <? if($ok){?>
                    <td width="40">
                        <a href="<?=$ok?>"><img src="/local/templates/origami_mail/img/ok.png" alt="" border="0" width="30" height="30"
                                        style="display:block;text-decoration:none;outline:none;"></a>
                    </td>
                    <?}?>
                    <? if($inst){?>
                    <td width="40">
                        <a href="<?=$inst?>"><img src="/local/templates/origami_mail/img/inst.png" alt="" border="0" width="30" height="30"
                                        style="display:block;text-decoration:none;outline:none;"></a>
                    </td>
                    <?}?>
                    <? if($fb){?>
                    <td width="40">
                        <a href="<?=$fb?>"><img src="/local/templates/origami_mail/img/fb.png" alt="" border="0" width="30" height="30"
                                        style="display:block;text-decoration:none;outline:none;"></a>
                    </td>
                    <?}?>
                    <? if($google){?>
                    <td>
                        <a href="<?=$google?>"><img src="/local/templates/origami_mail/img/google.png" alt="" border="0" width="30" height="30"
                                        style="display:block;text-decoration:none;outline:none;"></a>
                    </td>
                    <?}?>
                </tr>
            </table>
        </td>
        <td valign="top">
            <div style="color: #ffffff; font-family: 'Open Sans', sans-serif; font-size: 15px; font-weight: bold; padding-bottom: 25px;">
                <?=GetMessage('INFO_COMPANY');?>
            </div>
            <table align="left" cellpadding="0" cellspacing="0" border="0" bgcolor="#000000">
                <? EventMessageThemeCompiler::IncludeComponent(
                    "bitrix:menu",
                    "bottom",
                    Array(
                        "ALLOW_MULTI_SELECT" => "N",
                        "CHILD_MENU_TYPE" => "left",
                        "DELAY" => "N",
                        "MAX_LEVEL" => "1",
                        "MENU_CACHE_GET_VARS" => array(0=>"",),
                        "MENU_CACHE_TIME" => "3600",
                        "MENU_CACHE_TYPE" => "N",
                        "MENU_CACHE_USE_GROUPS" => "Y",
                        "ROOT_MENU_TYPE" => "sotbit_bottom2",
                        "USE_EXT" => "N"
                    )
                );?>
            </table>
        </td>
        <td valign="top" align="right">
            <div style="color: #ffffff; font-family: 'Open Sans', sans-serif; font-size: 15px; font-weight: bold; padding-bottom: 25px;">
                <?=GetMessage('SOCIAL');?>
            </div>
            <table align="right" cellpadding="0" cellspacing="0" border="0" bgcolor="#000000">
                <tr align="right">
                    <td style="padding-bottom: 20px;">
                        <p style="color: #ffffff; font-family: 'Open Sans', sans-serif; font-size: 13px; text-decoration: none;">
                            <?=GetMessage('INFO_ADRESS');?>
                    </td>
                </tr>
                <tr align="right">
                    <td style="padding-bottom: 20px;">
                        <a href="tel:+375297402777"
                           style="color: #ffffff; font-family: 'Open Sans', sans-serif; font-size: 13px; text-decoration: none;">+375
                            29 740 27 77</a>
                    </td>
                </tr>
                <tr align="right">
                    <td style="padding-bottom: 20px;">
                        <a href="mailto:sale@sotbit.ru"
                           style="color: #ffffff; font-family: 'Open Sans', sans-serif; font-size: 13px; text-decoration: none;">sale@sotbit.ru</a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</td>
</tr>
</table>
</body>
</html>
