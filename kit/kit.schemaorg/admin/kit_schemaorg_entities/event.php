<?
//-----------------------------------------------------------------------------------------------------------------------------------------------
if(isset($_POST['key']) || isset($_POST['entity']))
    $key = $_POST['key'] == 'ENTITIES' ? $_POST['entity'] : $_POST['key'];
if(!isset($key) || empty($key)) {
    if(isset($arFields["ENTITIES"]) && !empty($arFields["ENTITIES"]))
    {
        $key = strtolower($arFields["ENTITIES"]["@type"]);
        $arFields = $arFields["ENTITIES"];
    }
    else
        $key = strtolower($arFields["@type"]);
}

$aTabs = array(
    array(
        "DIV" => $_POST['entity'],
        "ICON" => "main_user_edit",
    ),);
$langPrefix = 'KIT_SCHEMA_EVENT_';
$tabControl = new CAdminForm("tabControl", $aTabs);
$tabControl->AddSection("OG_COMMON_SETTINGS", GetMessage($langPrefix."SETTINGS"));
$tabControl->BeginCustomField("type_BLOCK", '', false);
?>
    <tr class="adm-detail-file-row">
        <td colspan="2" style="text-align: center">
            <input type="hidden" name="<?=$key ? $key."[@type]" : "@type"?>" value="Event"/>
        </td>
    </tr>
<?
$tabControl->EndCustomField("type_BLOCK");

$tabControl = KitSchema::makeSingleDropDownField($tabControl, "organizer",
    array(
        '' => '...',
        'person' => 'Person',
        'organization' => 'Organization',
    ),
    $key, $langPrefix, $arFields);

if(isset($arFields["contributor"]["@type"]) && !empty($arFields["contributor"]["@type"]))
{
    $entitiName = strtolower($arFields["contributor"]["@type"]);
}
else
{
    $entitiName = "";
}

$tabControl = KitSchema::makeSingleDropDownField($tabControl, "contributor",
    array(
        '' => '...',
        'person' => 'Person',
        'organization' => 'Organization',
    ),
    $key, $langPrefix, $arFields);

$tabControl = KitSchema::makeSingleDropDownField($tabControl, "attendee",
    array(
        '' => '...',
        'person' => 'Person',
        'organization' => 'Organization',
    ),
    $key, $langPrefix, $arFields);

$tabControl->AddEditField($key ? $key."[doorTime]" : "doorTime", GetMessage($langPrefix.'doorTime'), false, false,
    ( isset($arFields["doorTime"]) & !empty($arFields["doorTime"]) ? $arFields["doorTime"] : "" ));
$tabControl->AddEditField($key ? $key."[eventStatus]" : "eventStatus", GetMessage($langPrefix.'eventStatus'), false, false,
    ( isset($arFields["eventStatus"]) & !empty($arFields["eventStatus"]) ? $arFields["eventStatus"] : "" ));

$tabControl = KitSchema::makeSingleDropDownField($tabControl, "isAccessibleForFree",
    array(
        'N' => 'N',
        'Y' => 'Y',
    ),
    $key, $langPrefix, $arFields);

$tabControl = KitSchema::makeSingleDropDownField($tabControl, "performer",
    array(
        '' => '...',
        'person' => 'Person',
        'organization' => 'Organization',
    ),
    $key, $langPrefix, $arFields);

$tabControl->AddEditField($key ? $key."[startDate]" : "startDate", GetMessage($langPrefix.'StartDate'), true, false,
    ( isset($arFields["startDate"]) & !empty($arFields["startDate"]) ? $arFields["startDate"] : "" ));

$tabControl = KitSchema::makeSingleDropDownField($tabControl, "subEvent",
    array(
        '' => '...',
        'event' => 'Event',
    ),
    $key, $langPrefix, $arFields);

$tabControl = KitSchema::makeSingleDropDownField($tabControl, "superEvent",
    array(
        '' => '...',
        'event' => 'Event',
    ),
    $key, $langPrefix, $arFields);

$tabControl->AddEditField($key ? $key."[typicalAgeRange]" : "typicalAgeRange", GetMessage($langPrefix.'typicalAgeRange'), false, false,
    ( isset($arFields["typicalAgeRange"]) & !empty($arFields["typicalAgeRange"]) ? $arFields["typicalAgeRange"] : "" ));

//$tabControl = KitSchema::makeSingleDropDownField($tabControl, "recordedIn",
//    array(
//        '' => '...',
//        'creativework' => 'CreativeWork',
//    ),
//    $key, $langPrefix, $arFields);

//$tabControl = KitSchema::makeSingleDropDownField($tabControl, "workPerformed",
//    array(
//        '' => '...',
//        'creativework' => 'CreativeWork',
//    ),
//    $key, $langPrefix, $arFields);

$tabControl = KitSchema::makeSingleDropDownField($tabControl, "aggregateRating",
    array(
        '' => '...',
        'aggregaterating' => 'AggregateRating',
    ),
    $key, $langPrefix, $arFields);

$tabControl = KitSchema::makeSingleDropDownField($tabControl, "review",
    array(
        '' => '...',
        'review' => 'Review',
    ),
    $key, $langPrefix, $arFields);

//$tabControl = KitSchema::makeSingleDropDownField($tabControl, "location",
//    array(
//        '' => '...',
//        'text' => 'Text',
//        'place' => 'Place',
//        'postaladdress' => 'PostalAddress',
//    ),
//    $key, $langPrefix, $arFields);
$tabControl = KitSchema::makeSingleDropDownField($tabControl, "location",
    array(
        '' => '...',
        'place' => 'Place',
    ),
    $key, $langPrefix, $arFields, true);

$tabControl = KitSchema::makeMultipleDropDownField($tabControl, "offers",
    array(
        '' => '...',
        'offer' => 'Offer',
    ),
    $key, $langPrefix, $arFields);

//$tabControl = KitSchema::makeSingleDropDownField($tabControl, "workFeatured",
//    array(
//        '' => '...',
//        'creativework' => 'CreativeWork',
//    ),
//    $key, $langPrefix, $arFields);

$tabControl = KitSchema::makeSingleDropDownField($tabControl, "actor",
    array(
        '' => '...',
        'person' => 'Person',
    ),
    $key, $langPrefix, $arFields);

$tabControl = KitSchema::makeSingleDropDownField($tabControl, "director",
    array(
        '' => '...',
        'person' => 'Person',
    ),
    $key, $langPrefix, $arFields);

$tabControl->AddEditField($key ? $key."[inLanguage]" : "inLanguage", GetMessage($langPrefix.'inLanguage'), false, false,
    ( isset($arFields["inLanguage"]) & !empty($arFields["inLanguage"]) ? $arFields["inLanguage"] : "" ));

$tabControl = KitSchema::makeSingleDropDownField($tabControl, "sponsor",
    array(
        '' => '...',
        'person' => 'Person',
        'organization' => 'Organization',
    ),
    $key, $langPrefix, $arFields);

$tabControl = KitSchema::makeSingleDropDownField($tabControl, "translator",
    array(
        '' => '...',
        'person' => 'Person',
        'organization' => 'Organization',
    ),
    $key, $langPrefix, $arFields);

$tabControl = KitSchema::makeSingleDropDownField($tabControl, "composer",
    array(
        '' => '...',
        'person' => 'Person',
        'organization' => 'Organization',
    ),
    $key, $langPrefix, $arFields);

$tabControl = KitSchema::makeSingleDropDownField($tabControl, "funder",
    array(
        '' => '...',
        'person' => 'Person',
        'organization' => 'Organization',
    ),
    $key, $langPrefix, $arFields);

$tabControl = KitSchema::makeSingleDropDownField($tabControl, "about",
    array(
        '' => '...',
        'thing' => 'Thing',
    ),
    $key, $langPrefix, $arFields);

$tabControl = KitSchema::makeSingleDropDownField($tabControl, "audience",
    array(
        '' => '...',
        'audience' => 'Audience',
    ),
    $key, $langPrefix, $arFields);

$tabControl->AddEditField($key ? $key."[maximumAttendeeCapacity]" : "maximumAttendeeCapacity", GetMessage($langPrefix.'maximumAttendeeCapacity'), false, false,
    ( isset($arFields["maximumAttendeeCapacity"]) & !empty($arFields["maximumAttendeeCapacity"]) ? $arFields["maximumAttendeeCapacity"] : "" ));
$tabControl->AddEditField($key ? $key."[remainingAttendeeCapacity]" : "remainingAttendeeCapacity", GetMessage($langPrefix.'remainingAttendeeCapacity'), false, false,
    ( isset($arFields["remainingAttendeeCapacity"]) & !empty($arFields["remainingAttendeeCapacity"]) ? $arFields["remainingAttendeeCapacity"] : "" ));

require (dirname(__FILE__).'/blocks/thing.php');
$tabControl->Show();
?>