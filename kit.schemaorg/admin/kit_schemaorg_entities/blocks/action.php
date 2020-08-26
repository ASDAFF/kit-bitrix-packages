<?
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$langPrefix = 'KIT_SCHEMA_ACTION_';

$tabControl = KitSchema::makeSingleDropDownField($tabControl, "target",
    array(
        '' => '...',
        'entrypoint' => 'EntryPoint',
    ),
    $key, $langPrefix, $arFields, true);

$tabControl->AddEditField($key ? $key."[actionStatus]" : "actionStatus", GetMessage($langPrefix.'actionStatus'), false, false,
    ( isset($arFields["actionStatus"]) & !empty($arFields["actionStatus"]) ? $arFields["actionStatus"] : "" ));

$tabControl = KitSchema::makeSingleDropDownField($tabControl, "agent",
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

$tabControl = KitSchema::makeSingleDropDownField($tabControl, "error",
    array(
        '' => '...',
        'thing' => 'Thing',
    ),
    $key, $langPrefix, $arFields);

$tabControl = KitSchema::makeSingleDropDownField($tabControl, "instrument",
    array(
        '' => '...',
        'thing' => 'Thing',
    ),
    $key, $langPrefix, $arFields);

$tabControl = KitSchema::makeSingleDropDownField($tabControl, "location",
    array(
        '' => '...',
        'place' => 'Place',
    ),
    $key, $langPrefix, $arFields);

$tabControl = KitSchema::makeSingleDropDownField($tabControl, "object",
    array(
        '' => '...',
        'thing' => 'Thing',
    ),
    $key, $langPrefix, $arFields);

$tabControl = KitSchema::makeSingleDropDownField($tabControl, "participant",
    array(
        '' => '...',
        'person' => 'Person',
        'organization' => 'Organization',
    ),
    $key, $langPrefix, $arFields);

$tabControl = KitSchema::makeSingleDropDownField($tabControl, "result",
    array(
        '' => '...',
        'thing' => 'Thing',
    ),
    $key, $langPrefix, $arFields);

require(dirname(__FILE__) . '/thing.php');
?>