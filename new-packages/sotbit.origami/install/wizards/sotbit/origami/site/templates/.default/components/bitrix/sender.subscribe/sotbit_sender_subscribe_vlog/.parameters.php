<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Sender\MailingTable;

$list = MailingTable::getList(array(
   "filter" => array(
       "ACTIVE" => 'Y'
   )
));
$arList = $list->fetchAll();
foreach ($arList as $it) {
    $rsList[$it['ID']] = $it['NAME'];
}

$arTemplateParameters = array(
    "CAMPANY" => array(
        "PARENT" => "BASE",
        "NAME" => GetMessage('TITLE_CAMPANY'),
        "TYPE" => "LIST",
        "DEFAULT" => "Y",
        "VALUES" => $rsList
    ),
    "FORM_SUBSCRIBE_TITLE" => array(
        "PARENT" => "BASE",
        "NAME" => GetMessage('FORM_SUBSCRIBE_TITLE'),
        "TYPE" => "STRING",
    )
);
