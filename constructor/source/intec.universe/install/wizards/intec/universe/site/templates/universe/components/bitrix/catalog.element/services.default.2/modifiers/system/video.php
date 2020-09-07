<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arVideo = [
    'USE' => false,
    'TYPE' => $arParams['VIDEO_IBLOCK_TYPE'],
    'ID' => $arParams['VIDEO_IBLOCK_ID'],
    'SETTINGS' => [
        'PROPERTY_URL' => $arParams['VIDEO_PROPERTY_URL'],
        'PICTURE_SOURCES' => $arParams['VIDEO_PICTURE_SOURCES'],
        'PICTURE_SERVICE_QUALITY' => ArrayHelper::fromRange([
            'sddefault',
            'maxresdefault',
            'hqdefault',
            'mqdefault'
        ], $arParams['VIDEO_PICTURE_SERVICE_QUALITY'])
    ]
];

if (!empty($arVideo['ID']) && !empty($arVideo['SETTINGS']['PROPERTY_URL']))
    $arVideo['USE'] = true;

$arResult['VIDEO'] = $arVideo;

unset($arVideo);