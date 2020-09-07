<?
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$langPrefix = 'SOTBIT_SCHEMA_WEBPAGE_';



$tabControl = SotbitSchema::makeSingleDropDownField($tabControl, "breadcrumb",
    array(
        '' => '...',
        'breadcrumblist' => 'BreadcrumbList',
    ),
    $key, $langPrefix, $arFields);

$tabControl->AddEditField($key ? $key."[lastReviewed]" : "lastReviewed", GetMessage($langPrefix.'lastReviewed'), false, false,
    ( isset($arFields["lastReviewed"]) & !empty($arFields["lastReviewed"]) ? $arFields["lastReviewed"] : "" ));

$tabControl = SotbitSchema::makeSingleDropDownField($tabControl, "primaryImageOfPage",
    array(
        '' => '...',
        'imageobject' => 'ImageObject',
    ),
    $key, $langPrefix, $arFields);

$tabControl->AddEditField($key ? $key."[relatedLink]" : "relatedLink", GetMessage($langPrefix.'relatedLink'), false, false,
    ( isset($arFields["relatedLink"]) & !empty($arFields["relatedLink"]) ? $arFields["relatedLink"] : "" ));

$tabControl = SotbitSchema::makeSingleDropDownField($tabControl, "reviewedBy",
    array(
        '' => '...',
        'person' => 'Person',
        'organization' => 'Organization',
    ),
    $key, $langPrefix, $arFields);

$tabControl->AddEditField($key ? $key."[significantLink]" : "significantLink", GetMessage($langPrefix.'significantLink'), false, false,
    ( isset($arFields["significantLink"]) & !empty($arFields["significantLink"]) ? $arFields["significantLink"] : "" ));
$tabControl->AddEditField($key ? $key."[specialty]" : "specialty", GetMessage($langPrefix.'specialty'), false, false,
    ( isset($arFields["specialty"]) & !empty($arFields["specialty"]) ? $arFields["specialty"] : "" ));

require(dirname(__FILE__) . '/creativework.php');
?>