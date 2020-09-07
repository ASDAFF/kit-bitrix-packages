<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

/**
 * @var array $arCurrentValues
 * @var array $arComponentParameters
 */

if (!Loader::includeModule('intec.core'))
    return;

if (!Loader::includeModule('iblock'))
    return;

/** @var array $arIBlockTypes - Список типов инфоблоков */
$arIBlockTypes = CIBlockParameters::GetIBlockTypes();

/** @var array $arIBlocks - Список инфоблоков по выбранному типу */
$arIBlocks = array();

if (!empty($arCurrentValues['IBLOCK_TYPE'])) {
    $rsIBlocks = CIBlock::GetList(
        array(),
        array(
            'TYPE' => $arCurrentValues['IBLOCK_TYPE']
        )
    );
} else {
    $rsIBlocks = CIBlock::GetList();
}

while ($arIBlock = $rsIBlocks->GetNext()) {
    $arIBlocks[$arIBlock['ID']] = '['.$arIBlock['ID'].'] '.$arIBlock['NAME'];
}

/** Список разделов инфоблока */
$arSections = [];

if ($arCurrentValues['IBLOCK_ID']) {
    $rsSections = CIBlockSection::GetList(
        array(),
        array(
            'ACTIVE' => 'Y',
            'IBLOCK_TYPE' => $arCurrentValues['IBLOCK_TYPE'],
            'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID']
        )
    );

    while ($arSection = $rsSections->GetNext()) {
        $sSectionName = Html::decode($arSection['NAME']);
        $arSections[$arSection['ID']] = '[' . $arSection['ID'] . '] ' . $sSectionName;
    }
}

/** Параметры компонента */
$arComponentParameters = array(
    'GROUPS' => array(
        'SORT' => array(
            'NAME' => Loc::getMessage('C_SHARES_GROUP_SORT'),
            'SORT' => 800
        )
    ),
    'PARAMETERS' => array(
        'IBLOCK_TYPE' => array(
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_SHARES_IBLOCK_TYPE'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlockTypes,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ),
        'IBLOCK_ID' => array(
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_SHARES_IBLOCK_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocks,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ),
        'CACHE_TIME' => array()
    )
);

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arComponentParameters['PARAMETERS']['SECTIONS'] = array(
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_SHARES_SECTIONS'),
        'TYPE' => 'LIST',
        'VALUES' => $arSections,
        'MULTIPLE' => 'Y'
    );
}

$arComponentParameters['PARAMETERS']['ELEMENTS_COUNT'] = array(
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SHARES_ELEMENTS_COUNT'),
    'TYPE' => 'STRING'
);
$arComponentParameters['PARAMETERS']['HEADER_BLOCK_SHOW'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_SHARES_HEADER_BLOCK_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
);

if ($arCurrentValues['HEADER_BLOCK_SHOW'] == 'Y') {
    $arComponentParameters['PARAMETERS'] += array(
        'HEADER_BLOCK_POSITION' => array(
            'PARENT' =>'VISUAL',
            'NAME' => Loc::getMessage('C_SHARES_HEADER_BLOCK_POSITION'),
            'TYPE' => 'LIST',
            'VALUES' => array(
                'left' => Loc::getMessage('C_SHARES_POSITION_LEFT'),
                'center' => Loc::getMessage('C_SHARES_POSITION_CENTER'),
                'right' => Loc::getMessage('C_SHARES_POSITION_RIGHT')
            ),
            'DEFAULT' => 'center'
        ),
        'HEADER_BLOCK_TEXT' => array(
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_SHARES_HEADER_BLOCK_TEXT'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_SHARES_HEADER_BLOCK_TEXT_DEFAULT')
        )
    );
}

$arComponentParameters['PARAMETERS']['DESCRIPTION_BLOCK_SHOW'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_SHARES_DESCRIPTION_BLOCK_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
);

if ($arCurrentValues['DESCRIPTION_BLOCK_SHOW'] == 'Y') {
    $arComponentParameters['PARAMETERS'] += array(
        'DESCRIPTION_BLOCK_POSITION' => array(
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_SHARES_DESCRIPTION_BLOCK_POSITION'),
            'TYPE' => 'LIST',
            'VALUES' => array(
                'left' => Loc::getMessage('C_SHARES_POSITION_LEFT'),
                'center' => Loc::getMessage('C_SHARES_POSITION_CENTER'),
                'right' => Loc::getMessage('C_SHARES_POSITION_RIGHT')
            ),
            'DEFAULT' => 'center'
        ),
        'DESCRIPTION_BLOCK_TEXT' => array(
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_SHARES_DESCRIPTION_BLOCK_TEXT'),
            'TYPE' => 'STRING'
        )
    );
}

$arComponentParameters['PARAMETERS'] += array(
    'LIST_PAGE_URL' => array(
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_SHARES_LIST_PAGE_URL'),
        'TYPE' => 'STRING'
    ),
    'SECTION_URL' => CIBlockParameters::GetPathTemplateParam(
        'SECTION',
        'SECTION_URL',
        Loc::getMessage('C_SHARES_SECTION_URL'),
        '',
        'URL_TEMPLATES'
    ),
    'DETAIL_URL' => CIBlockParameters::GetPathTemplateParam(
        'DETAIL',
        'DETAIL_URL',
        Loc::getMessage('C_SHARES_DETAIL_URL'),
        '',
        'URL_TEMPLATES'
    )
);

$arComponentParameters['PARAMETERS']['SORT_BY'] = array(
    'PARENT' => 'SORT',
    'NAME' => Loc::getMessage('C_SHARES_SORT_BY'),
    'TYPE' => 'LIST',
    'VALUES' => array(
        'ID' => Loc::getMessage('C_SHARES_SORT_BY_ID'),
        'SORT' => Loc::getMessage('C_SHARES_SORT_BY_SORT'),
        'NAME' => Loc::getMessage('C_SHARES_SORT_BY_NAME')
    ),
    'DEFAULT' => 'SORT',
    'ADDITIONAL_VALUES' => 'Y'
);
$arComponentParameters['PARAMETERS']['ORDER_BY'] = array(
    'PARENT' => 'SORT',
    'NAME' => Loc::getMessage('C_SHARES_ORDER_BY'),
    'TYPE' => 'LIST',
    'VALUES' => array(
        'ASC' => Loc::getMessage('C_SHARES_ORDER_BY_ASC'),
        'DESC' => Loc::getMessage('C_SHARES_ORDER_BY_DESC')
    ),
    'DEFAULT' => 'ASC'
);