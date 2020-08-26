<?
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$langPrefix = 'SOTBIT_SCHEMA_ACTION_';

$tabControl = SotbitSchema::makeSingleDropDownField($tabControl, "target",
    array(
        '' => '...',
        'entrypoint' => 'EntryPoint',
    ),
    $key, $langPrefix, $arFields, true);

$tabControl->AddEditField($key ? $key."[actionStatus]" : "actionStatus", GetMessage($langPrefix.'actionStatus'), false, false,
    ( isset($arFields["actionStatus"]) & !empty($arFields["actionStatus"]) ? $arFields["actionStatus"] : "" ));

$tabControl = SotbitSchema::makeSingleDropDownField($tabControl, "agent",
    array(
        '' => '...',
        'person' => 'Person',
        'organization' => 'Organization',
    ),
    $key, $langPrefix, $arFields);

$tabControl->AddEditField($key ? $key."[startTime]" : "startTime", GetMessage($langPrefix.'startTime'), false, false,
    ( isset($arFields["startTime"]) & !empty($arFields["startTime"]) ? $arFields["startTime"] : "" ));
$tabControl->AddEditField($key ? $key."[endTime]" : "endTime", GetMessage($langPrefix.'endTime'), false, false,
    ( isset($arFields["endTime"]) & !empty($arFields["endTime"]) ? $arFields["endTime"] : "" ));

$tabControl = SotbitSchema::makeSingleDropDownField($tabControl, "error",
    array(
        '' => '...',
        'thing' => 'Thing',
    ),
    $key, $langPrefix, $arFields);

$tabControl = SotbitSchema::makeSingleDropDownField($tabControl, "instrument",
    array(
        '' => '...',
        'thing' => 'Thing',
    ),
    $key, $langPrefix, $arFields);

$tabControl = SotbitSchema::makeSingleDropDownField($tabControl, "location",
    array(
        '' => '...',
        'place' => 'Place',
    ),
    $key, $langPrefix, $arFields);

$tabControl = SotbitSchema::makeSingleDropDownField($tabControl, "object",
    array(
        '' => '...',
        'thing' => 'Thing',
    ),
    $key, $langPrefix, $arFields);

$tabControl = SotbitSchema::makeSingleDropDownField($tabControl, "participant",
    array(
        '' => '...',
        'person' => 'Person',
        'organization' => 'Organization',
    ),
    $key, $langPrefix, $arFields);

$tabControl = SotbitSchema::makeSingleDropDownField($tabControl, "result",
    array(
        '' => '...',
        'thing' => 'Thing',
    ),
    $key, $langPrefix, $arFields);

require(dirname(__FILE__) . '/thing.php');
?>