<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Sender\MailingTable;

$listÑampaign = MailingTable::getList(array("filter" => array("ACTIVE" => 'Y')));
while ($arList = $listÑampaign->Fetch()) {
    $mailingList[$arList['ID']] = $arList['NAME'];
}

$arTemplateParameters = array(
    "MAILING_LISTS" => array(
        "PARENT" => "BASE",
        "NAME" => GetMessage('TITLE_MAILING_LISTS'),
        "TYPE" => "LIST",
        'MULTIPLE' => 'Y',
        "DEFAULT" => "Y",
        "VALUES" => $mailingList
    ),
    "FORM_SUBSCRIBE_TITLE" => array(
        "PARENT" => "BASE",
        "NAME" => GetMessage('FORM_SUBSCRIBE_TITLE'),
        "TYPE" => "STRING",
    )
);
