<?
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$langPrefix = 'KIT_SCHEMA_CREATIVEWORK_';

$tabControl->AddEditField($key ? $key."[about]" : "about", GetMessage($langPrefix.'about'), false, false,
    ( isset($arFields["about"]) & !empty($arFields["about"]) ? $arFields["about"] : "" ));

$tabControl = KitSchema::makeMultipleDropDownField($tabControl, "mainEntity",
    array(
        '' => '...',
        'question' => 'Question',
    ),
    $key, $langPrefix, $arFields);

$tabControl->AddEditField($key ? $key."[abstract]" : "abstract", GetMessage($langPrefix.'abstract'), false, false,
    ( isset($arFields["abstract"]) & !empty($arFields["abstract"]) ? $arFields["abstract"] : "" ));
$tabControl = KitSchema::makeSingleDropDownField($tabControl, "author",
    array(
        '' => '...',
        'person' => 'Person',
        'organization' => 'Organization',
    ),
    $key, $langPrefix, $arFields);
$tabControl->AddEditField($key ? $key."[datePublished]" : "datePublished", GetMessage($langPrefix.'datePublished'), false, false,
    ( isset($arFields["datePublished"]) & !empty($arFields["datePublished"]) ? $arFields["datePublished"] : "" ));
$tabControl->AddEditField($key ? $key."[dateModified]" : "dateModified", GetMessage($langPrefix.'dateModified'), false, false,
    ( isset($arFields["dateModified"]) & !empty($arFields["dateModified"]) ? $arFields["dateModified"] : "" ));
$tabControl->AddEditField($key ? $key."[headline]" : "headline", GetMessage($langPrefix.'headline'), false, false,
    ( isset($arFields["headline"]) & !empty($arFields["headline"]) ? $arFields["headline"] : "" ));
$tabControl->AddEditField($key ? $key."[image]" : "image", GetMessage($langPrefix.'image'), false, false,
    ( isset($arFields["image"]) & !empty($arFields["image"]) ? $arFields["image"] : "" ));
$tabControl = KitSchema::makeSingleDropDownField($tabControl, "publisher",
    array(
        '' => '...',
        'organization' => 'Organization',
    ),
    $key, $langPrefix, $arFields);
$tabControl = KitSchema::makeSingleDropDownField($tabControl, "accountablePerson",
    array(
        '' => '...',
        'person' => 'Person'
    ),
    $key, $langPrefix, $arFields);
$tabControl = KitSchema::makeSingleDropDownField($tabControl, "aggregateRating",
    array(
        '' => '...',
        'aggregateRating' => 'AggregateRating'
    ),
    $key, $langPrefix, $arFields);
$tabControl->AddEditField($key ? $key."[alternativeHeadline]" : "alternativeHeadline", GetMessage($langPrefix.'alternativeHeadline'), false, false,
    ( isset($arFields["alternativeHeadline"]) & !empty($arFields["alternativeHeadline"]) ? $arFields["alternativeHeadline"] : "" ));
$tabControl->AddEditField($key ? $key."[award]" : "award", GetMessage($langPrefix.'award'), false, false,
    ( isset($arFields["award"]) & !empty($arFields["award"]) ? $arFields["award"] : "" ));
$tabControl->AddEditField($key ? $key."[citation]" : "citation", GetMessage($langPrefix.'citation'), false, false,
    ( isset($arFields["citation"]) & !empty($arFields["citation"]) ? $arFields["citation"] : "" ));
$tabControl = KitSchema::makeSingleDropDownField($tabControl, "contentLocation",
    array(
        '' => '...',
        'place' => 'Place'
    ),
    $key, $langPrefix, $arFields);
$tabControl->AddEditField($key ? $key."[contentReferenceTime]" : "contentReferenceTime", GetMessage($langPrefix.'contentReferenceTime'), false, false,
    ( isset($arFields["contentReferenceTime"]) & !empty($arFields["contentReferenceTime"]) ? $arFields["contentReferenceTime"] : "" ));
$tabControl = KitSchema::makeSingleDropDownField($tabControl, "contributor",
    array(
        '' => '...',
        'person' => 'Person',
        'organization' => 'Organization',
    ),
    $key, $langPrefix, $arFields);
$tabControl = KitSchema::makeSingleDropDownField($tabControl, "copyrightHolder",
    array(
        '' => '...',
        'person' => 'Person',
        'organization' => 'Organization',
    ),
    $key, $langPrefix, $arFields);
$tabControl->AddEditField($key ? $key."[copyrightYear]" : "copyrightYear", GetMessage($langPrefix.'copyrightYear'), false, false,
    ( isset($arFields["copyrightYear"]) & !empty($arFields["copyrightYear"]) ? $arFields["copyrightYear"] : "" ));
$tabControl->AddEditField($key ? $key."[correction]" : "correction", GetMessage($langPrefix.'correction'), false, false,
    ( isset($arFields["correction"]) & !empty($arFields["correction"]) ? $arFields["correction"] : "" ));
$tabControl->AddEditField($key ? $key."[discussionUrl]" : "discussionUrl", GetMessage($langPrefix.'discussionUrl'), false, false,
    ( isset($arFields["discussionUrl"]) & !empty($arFields["discussionUrl"]) ? $arFields["discussionUrl"] : "" ));
$tabControl = KitSchema::makeSingleDropDownField($tabControl, "editor",
    array(
        '' => '...',
        'person' => 'Person'
    ),
    $key, $langPrefix, $arFields);
$tabControl->AddEditField($key ? $key."[encodingFormat]" : "encodingFormat", GetMessage($langPrefix.'encodingFormat'), false, false,
    ( isset($arFields["encodingFormat"]) & !empty($arFields["encodingFormat"]) ? $arFields["encodingFormat"] : "" ));
$tabControl->AddEditField($key ? $key."[expires]" : "expires", GetMessage($langPrefix.'expires'), false, false,
    ( isset($arFields["expires"]) & !empty($arFields["expires"]) ? $arFields["expires"] : "" ));

$tabControl = KitSchema::makeSingleDropDownField($tabControl, "funder",
    array(
        '' => '...',
        'person' => 'Person',
        'organization' => 'Organization',
    ),
    $key, $langPrefix, $arFields);

$tabControl->AddEditField($key ? $key."[genre]" : "genre", GetMessage($langPrefix.'genre'), ``, false,
    ( isset($arFields["genre"]) & !empty($arFields["genre"]) ? $arFields["genre"] : "" ));
$tabControl->AddEditField($key ? $key."[inLanguage]" : "inLanguage", GetMessage($langPrefix.'inLanguage'), false, false,
    ( isset($arFields["inLanguage"]) & !empty($arFields["inLanguage"]) ? $arFields["inLanguage"] : "" ));
$tabControl->AddEditField($key ? $key."[isBasedOn]" : "isBasedOn", GetMessage($langPrefix.'isBasedOn'), false, false,
    ( isset($arFields["isBasedOn"]) & !empty($arFields["isBasedOn"]) ? $arFields["isBasedOn"] : "" ));
$tabControl->AddEditField($key ? $key."[keywords]" : "keywords", GetMessage($langPrefix.'keywords'), false, false,
    ( isset($arFields["keywords"]) & !empty($arFields["keywords"]) ? $arFields["keywords"] : "" ));
$tabControl->AddEditField($key ? $key."[license]" : "license", GetMessage($langPrefix.'license'), false, false,
    ( isset($arFields["license"]) & !empty($arFields["license"]) ? $arFields["license"] : "" ));
$tabControl = KitSchema::makeSingleDropDownField($tabControl, "locationCreated",
    array(
        '' => '...',
        'place' => 'Place'
    ),
    $key, $langPrefix, $arFields);

$tabControl = KitSchema::makeSingleDropDownField($tabControl, "offers",
    array(
        '' => '...',
        'offer' => 'Offer',
    ),
    $key, $langPrefix, $arFields);


$tabControl->AddEditField($key ? $key."[text]" : "text", GetMessage($langPrefix.'text'), false, false,
    ( isset($arFields["text"]) & !empty($arFields["text"]) ? $arFields["text"] : "" ));
$tabControl->AddEditField($key ? $key."[thumbnailUrl]" : "thumbnailUrl", GetMessage($langPrefix.'thumbnailUrl'), false, false,
    ( isset($arFields["thumbnailUrl"]) & !empty($arFields["thumbnailUrl"]) ? $arFields["thumbnailUrl"] : "" ));

$tabControl = KitSchema::makeSingleDropDownField($tabControl, "translationOfWork",
    array(
        '' => '...',
        'creativework' => 'CreativeWork'
    ),
    $key, $langPrefix, $arFields);

$tabControl = KitSchema::makeSingleDropDownField($tabControl, "translator",
    array(
        '' => '...',
        'person' => 'Person',
        'organization' => 'Organization',
    ),
    $key, $langPrefix, $arFields);

$tabControl->AddEditField($key ? $key."[version]" : "version", GetMessage($langPrefix.'version'), false, false,
    ( isset($arFields["version"]) & !empty($arFields["version"]) ? $arFields["version"] : "" ));
?>

