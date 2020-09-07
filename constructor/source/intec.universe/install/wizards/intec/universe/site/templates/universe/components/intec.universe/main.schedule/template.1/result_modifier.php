<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;
use Bitrix\Main\Localization\Loc;
use intec\template\Properties;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arParams = ArrayHelper::merge([
    'SETTINGS_USE' => 'N',
    'LAZYLOAD_USE' => 'N',
    'STAFF_IBLOCK_TYPE' => null,
    'STAFF_IBLOCK_ID' => null,
    'PROPERTY_STAFF' => null,
    'PROPERTY_STAFF_POSITION' => null,
    'PROPERTY_TIME_FROM' => null,
    'PROPERTY_TIME_TO' => null,
    'PROPERTY_TEXT' => null,
    'PROPERTY_FILE' => null,
    'LINES_FIRST' => 'dark',
    'TIME_SHOW' => 'N',
    'TIME_FORMAT' => 'from',
    'STAFF_SHOW' => 'N',
    'STAFF_PICTURE_SHOW' => 'N',
    'STAFF_POSITION_SHOW' => 'N',
    'TEXT_SHOW' => 'N',
    'FILE_SHOW' => 'N',
    'FILE_TEXT' => Loc::getMessage('C_SCHEDULE_TEMP1_FILE_TEXT_TEMPLATE_DEFAULT')
], $arParams);

$arCodes = [
    'TIME' => [
        'FROM' => ArrayHelper::getValue($arParams, 'PROPERTY_TIME_FROM'),
        'TO' => ArrayHelper::getValue($arParams, 'PROPERTY_TIME_TO')
    ],
    'TEXT' => ArrayHelper::getValue($arParams, 'PROPERTY_TEXT'),
    'FILE' => ArrayHelper::getValue($arParams, 'PROPERTY_FILE'),
    'STAFF' => ArrayHelper::getValue($arParams, 'PROPERTY_STAFF'),
    'STAFF_POSITION' => ArrayHelper::getValue($arParams, 'PROPERTY_STAFF_POSITION')
];

if ($arParams['SETTINGS_USE'] === 'Y')
    include(__DIR__.'/modifiers/settings.php');

$arVisual = [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
        'STUB' => null
    ],
    'LINES' => [
        'FIRST' => ArrayHelper::fromRange(['dark', 'light'], ArrayHelper::getValue($arParams, 'LINES_FIRST')),
        'SECOND' => null,
    ],
    'TIME' => [
        'SHOW' => ArrayHelper::getValue($arParams, 'TIME_SHOW') == 'Y',
        'FORMAT' => ArrayHelper::fromRange([
            'from-to-1',
            'from',
            'from-to-2',
            'to'
        ], ArrayHelper::getValue($arParams, 'TIME_FORMAT'))
    ],
    'STAFF' => [
        'SHOW' => ArrayHelper::getValue($arParams, 'STAFF_SHOW') == 'Y',
        'SHOW_PICTURE' => ArrayHelper::getValue($arParams, 'STAFF_PICTURE_SHOW') == 'Y',
        'SHOW_POSITION' => ArrayHelper::getValue($arParams, 'STAFF_POSITION_SHOW') == 'Y'
    ],
    'TEXT' => [
        'SHOW' => ArrayHelper::getValue($arParams, 'TEXT_SHOW') == 'Y'
    ],
    'FILE' => [
        'SHOW' => ArrayHelper::getValue($arParams, 'FILE_SHOW') == 'Y',
        'TEXT' => ArrayHelper::getValue($arParams, 'FILE_TEXT')
    ]
];

if (defined('EDITOR'))
    $arVisual['LAZYLOAD']['USE'] = false;

if ($arVisual['LAZYLOAD']['USE'])
    $arVisual['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

$arVisual['TIME']['SHOW'] = !empty($arCodes['TIME']['FROM']) && $arVisual['TIME']['SHOW'];
$arVisual['LINES']['SECOND'] = $arVisual['LINES']['FIRST'] == 'dark' ? 'light' : 'dark';
$arVisual['STAFF']['SHOW'] = !empty($arParams['STAFF_IBLOCK_ID']) && !empty($arCodes['STAFF']) && $arVisual['STAFF']['SHOW'];
$arVisual['TEXT']['SHOW'] = !empty($arCodes['TEXT']) && $arVisual['TEXT']['SHOW'];
$arVisual['FILE']['SHOW'] = !empty($arCodes['FILE']) && $arVisual['FILE']['SHOW'];

$arResult['PROPERTY_CODES'] = $arCodes;
$arResult['VISUAL'] = $arVisual;

$arStaff = [];
$arFiles = [];
$arImages = [];

foreach ($arResult['ITEMS'] as $arItem) {
    $sStaff = ArrayHelper::getValue($arItem, ['PROPERTIES', $arCodes['STAFF'], 'VALUE']);

    if (!empty($sStaff))
        $arStaff[$arItem['ID']] = $sStaff;

    $sFile = ArrayHelper::getValue($arItem, ['PROPERTIES', $arCodes['FILE'], 'VALUE']);

    if (!empty($sFile))
        $arFiles[$arItem['ID']] = $sFile;
}

if ($arVisual['STAFF']['SHOW'] && !empty($arStaff)) {
    $rsStaff = CIBlockElement::GetList(['SORT' => 'ASC'], [
        'IBLOCK_ID' => $arParams['STAFF_IBLOCK_ID'],
        'ID' => $arStaff,
        'ACTIVE' => 'Y'
    ]);

    $arStaff = [];

    while ($rsEmployee = $rsStaff->GetNextElement()) {
        $arEmployee = $rsEmployee->GetFields();
        $arEmployee['PROPERTIES'] = $rsEmployee->GetProperties();
        $arEmployee['DATA'] = [
            'POSITION' => ArrayHelper::getValue($arEmployee['PROPERTIES'], $arCodes['STAFF_POSITION'])
        ];

        if (!empty($arEmployee['PREVIEW_PICTURE']))
            $arImages[] = $arEmployee['PREVIEW_PICTURE'];

        if (!empty($arEmployee['DETAIL_PICTURE']))
            $arImages[] = $arEmployee['DETAIL_PICTURE'];

        $arStaff[$arEmployee['ID']] = $arEmployee;
    }

    unset($arStaffs, $arEmployee, $rsEmployee, $rsStaff);

    if(!empty($arImages)) {
        $arImages = Arrays::fromDBResult(CFile::GetList([], [
            '@ID' => implode(',', $arImages)
        ]))->each(function ($iIndex, &$arImage) {
            $arImage['SRC'] = CFile::GetFileSRC($arImage);
        })->indexBy('ID');
    } else {
        $arImages = new Arrays();
    }

    foreach ($arStaff as $iKey => &$arEmployee) {

        if (!empty($arEmployee['PREVIEW_PICTURE']) && !Type::isArray($arEmployee['PREVIEW_PICTURE']))
            $arEmployee['PREVIEW_PICTURE'] = $arImages->get($arEmployee['PREVIEW_PICTURE']);

        if (!empty($arEmployee['DETAIL_PICTURE']) && !Type::isArray($arEmployee['DETAIL_PICTURE']))
            $arEmployee['DETAIL_PICTURE'] = $arImages->get($arEmployee['DETAIL_PICTURE']);

    }
    unset($arEmployee);
}

if($arVisual['FILE']['SHOW'] && !empty($arFiles)) {
    $arFiles = Arrays::fromDBResult(CFile::GetList([], [
        '@ID' => implode(',', $arFiles)
    ]))->each(function ($iIndex, &$arFile) {
        $arFile['SRC'] = CFile::GetFileSRC($arFile);
    })->indexBy('ID');
} else {
    $arFiles = new Arrays();
}

if (!empty($arStaff) || !empty($arFiles)) {
    foreach ($arResult['ITEMS'] as &$arItem) {
        $arItem['DATA'] = [
            'EMPLOYEE' => null,
            'FILE' => null
        ];

        if ($arVisual['STAFF']['SHOW']) {
            $sStaffId = ArrayHelper::getValue($arItem, ['PROPERTIES', $arCodes['STAFF'], 'VALUE']);

            if (ArrayHelper::keyExists($sStaffId, $arStaff))
                $arItem['DATA']['EMPLOYEE'] = &$arStaff[$sStaffId];
        }

        if ($arVisual['FILE']['SHOW']) {
            $sFileId = ArrayHelper::getValue($arItem, ['PROPERTIES', $arCodes['FILE'], 'VALUE']);

            if (ArrayHelper::keyExists($sFileId, $arFiles))
                $arItem['DATA']['FILE'] = &$arFiles[$sFileId];
        }
    }

    unset($sStaffId, $arStaff, $arFiles, $sFileId, $arImages, $arItem);
}