<?
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$langPrefix = 'KIT_SCHEMA_ARTICLE_';
$tabControl->AddEditField($key ? $key."[articleBody]" : "articleBody", GetMessage($langPrefix.'articleBody'), false, false,
    ( isset($arFields["articleBody"]) & !empty($arFields["articleBody"]) ? $arFields["articleBody"] : "" ));
$tabControl->AddEditField($key ? $key."[articleSection]" : "articleSection", GetMessage($langPrefix.'articleSection'), false, false,
    ( isset($arFields["articleSection"]) & !empty($arFields["articleSection"]) ? $arFields["articleSection"] : "" ));
$tabControl->AddEditField($key ? $key."[backstory]" : "backstory", GetMessage($langPrefix.'backstory'), false, false,
    ( isset($arFields["backstory"]) & !empty($arFields["backstory"]) ? $arFields["backstory"] : "" ));
$tabControl->AddEditField($key ? $key."[wordCount]" : "wordCount", GetMessage($langPrefix.'wordCount'), false, false,
    ( isset($arFields["wordCount"]) & !empty($arFields["wordCount"]) ? $arFields["wordCount"] : "" ));

require (dirname(__FILE__).'/creativework.php');
?>