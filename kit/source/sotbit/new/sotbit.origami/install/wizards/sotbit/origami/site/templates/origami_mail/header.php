<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title></title>
        <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
</head>
<body>
<? if (\Bitrix\Main\Loader::includeModule('mail')) : ?>
<?=\Bitrix\Mail\Message::getQuoteStartMarker(true); ?>
<? endif; ?>
<?
global $serverName;
global $phone;
global $email;

$protocol = isset($_SERVER['HTTPS'])? 'https': 'http';
$protocol = \Bitrix\Main\Config\Option::get("main", "mail_link_protocol", $protocol, $arParams["SITE_ID"]);
$serverName = $protocol."://".$arParams["SERVER_NAME"];
?>

<table width="700" align="center" cellpadding="0" cellspacing="0" border="0" style="margin:auto; padding:0"
       bgcolor="#ffffff">
    <tr>
        <td>
            <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff">
                <tr>
                    <td align="left" rowspan="2" width="15">
                        <img src="/local/templates/origami_mail/img/mail.jpg" alt="" border="0" width="14" height="14"
                             style="padding-top: 2px; padding-right: 5px; border:0; outline:none; text-decoration:none; display:block;">
                    </td>
                    <td align="left" rowspan="2">
                        <a href="mailto:sale@sotbit.ru" value="sale@sotbit.ru"
                           style="color: #000000; font-family: 'Open Sans', sans-serif; font-size: 15px; font-weight: bold; text-decoration: none;">sale@sotbit.ru</a>
                    </td>
                    <td rowspan="2" align="center">
                        <a href="<?=SITE_DIR?>"><img src="<?=SITE_DIR?>include/sotbit_origami/images/logo.png" alt="" border="0" width="165" height="45"
                                        style="display:block;text-decoration:none;outline:none;"></a>
                    </td>
                    <td align="right"><img src="/local/templates/origami_mail/img/phone.jpg" alt="" border="0" width="14" height="14"
                                           style="display:block;text-decoration:none;outline:none;"></td>
                    <td align="right" width="135">
                        <a href="tel:+7 (812) 670-07-40" value="+7 (812) 670-07-40"
                           style="color: #000000; font-family: 'Open Sans', sans-serif; font-size: 15px; font-weight: bold; text-decoration: none;">+7
                            (812) 670-07-40</a>
                    </td>
                </tr>
                <tr>
                    <td align="right"><img src="/local/templates/origami_mail/img/phone.jpg" alt="" border="0" width="14" height="14"
                                           style="border:0; outline:none; text-decoration:none; display:block;"></td>
                    <td align="right">
                        <a href="tel:+7 (499) 647-89-31" value="+7 (499) 647-89-31"
                           style="color: #000000; font-family: 'Open Sans', sans-serif; font-size: 15px; font-weight: bold; text-decoration: none;">+7
                            (499) 647-89-31</a>
                    </td>
                </tr>
            </table>
            <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff"
                   style="border-bottom: 1px solid #f2f2f2; border-top: 1px solid #f2f2f2; line-height: 40px; margin-top: 5px;">
                <tr align="center">
                    <td width="16.6%"><a href="<?=SITE_DIR?>catalog/"
                                         style="color: #000000; font-family: 'Open Sans', sans-serif; font-size: 14px; text-decoration: none;">Каталог
                            товаров</a></td>
                    <td width="16.6%"><a href="<?=SITE_DIR?>promotions/"
                                         style="color: #000000; font-family: 'Open Sans', sans-serif; font-size: 14px; text-decoration: none;">Акции</a>
                    </td>
                    <td width="16.6%"><a href="<?=SITE_DIR?>brands/"
                                         style="color: #000000; font-family: 'Open Sans', sans-serif; font-size: 14px; text-decoration: none;">Бренды</a>
                    </td>
                    <td width="16.6%"><a href="<?=SITE_DIR?>news/"
                                         style="color: #000000; font-family: 'Open Sans', sans-serif; font-size: 14px; text-decoration: none;">Новости</a>
                    </td>
                    <td width="16.6%"><a href="<?=SITE_DIR?>about/contacts/"
                                         style="color: #000000; font-family: 'Open Sans', sans-serif; font-size: 14px; text-decoration: none;">О
                            магазине</a></td>
                    <td width="16.6%"><a href="<?=SITE_DIR?>help/payment/"
                                         style="color: #000000; font-family: 'Open Sans', sans-serif; font-size: 14px; text-decoration: none;">Помощь</a>
                    </td>
                </tr>
            </table>
            <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff"
                   style="padding-top: 40px;">
                <tr>
                    <td>