<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die() ?>
<?php

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\Type;
use intec\core\net\Url;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $this
 */

$arMenu = [];

if (!Loader::includeModule('iblock'))
    return $arMenu;

if (!Loader::includeModule('intec.core'))
	return $arMenu;

$this->setFrameMode(true);
$arParams[] = ArrayHelper::merge([
	'CACHE_TIME' => 36000000,
	'DEPTH_LEVEL' => 1,
    'USUAL' => 'N',
    'ELEMENTS_ROOT' => 'N',
    'ELEMENTS_SECTIONS' => 'N',
    'ELEMENTS_COUNT' => 'N'
], $arParams);

if (empty($arParams['CACHE_TIME']))
	$arParams['CACHE_TIME'] = 36000000;

if ($arParams['DEPTH_LEVEL'] < 0)
	$arParams['DEPTH_LEVEL'] = 1;

$arResult['SECTIONS'] = [];
$arResult['ELEMENTS'] = [];
$arResult['LINKS'] = [];
$arVariables = [];

if ($arParams['IS_SEF'] === 'Y') {
    $oEngine = new CComponentEngine($this);

    if (CModule::IncludeModule('iblock')) {
        $oEngine->addGreedyPart('#SECTION_CODE_PATH#');
        $oEngine->setResolveCallback([
            'CIBlockFindTools',
            'resolveComponentEngine'
        ]);
    }

    $sPage = $oEngine->guessComponentPath(
        $arParams['SEF_BASE_URL'],
        array(
            'section' => $arParams['SECTION_PAGE_URL'],
            'detail' => $arParams['DETAIL_PAGE_URL'],
        ),
        $arVariables
    );

    if ($sPage === 'detail') {
        CComponentEngine::InitComponentVariables(
            $sPage,
            ['SECTION_ID', 'ELEMENT_ID'],
            [
                'section' => ['SECTION_ID' => 'SECTION_ID'],
                'detail' => ['SECTION_ID' => 'SECTION_ID', 'ELEMENT_ID' => 'ELEMENT_ID']
            ],
            $arVariables
        );

        $arParams['ID'] = Type::toInteger($arVariables['ELEMENT_ID']);
    }
}

if ($this->startResultCache()) {
	$rsSections = CIBlockSection::GetList([
		'left_margin' => 'asc'
	], [
		'IBLOCK_ID' => $arParams['IBLOCK_ID'],
		'GLOBAL_ACTIVE' => 'Y',
		'IBLOCK_ACTIVE' => 'Y',
		'<=DEPTH_LEVEL' => $arParams['DEPTH_LEVEL'],
        'CNT_ACTIVE' => 'Y'
	], $arParams['ELEMENTS_COUNT'] === 'Y', [
        'ID',
        'CODE',
        'EXTERNAL_ID',
        'IBLOCK_ID',
        'IBLOCK_SECTION_ID',
        'TIMESTAMP_X',
        'SORT',
        'NAME',
        'ACTIVE',
        'GLOBAL_ACTIVE',
        'PICTURE',
        'DESCRIPTION',
        'DESCRIPTION_TYPE',
        'LEFT_MARGIN',
        'RIGHT_MARGIN',
        'DEPTH_LEVEL',
        'SEARCHABLE_CONTENT',
        'SECTION_PAGE_URL',
        'MODIFIED_BY',
        'DATE_CREATE',
        'CREATED_BY',
        'DETAIL_PICTURE',
        'UF_*'
	]);

    if ($arParams['IS_SEF'] !== 'Y') {
        $rsSections->SetUrlTemplates('', $arParams['SECTION_URL']);
    } else {
        $rsSections->SetUrlTemplates('', $arParams['SEF_BASE_URL'].$arParams['SECTION_PAGE_URL']);
    }

    while ($arSection = $rsSections->GetNext()) {
        if ($arParams['ELEMENTS_SECTIONS'] === 'Y')
        	$arSection['ELEMENTS'] = [];

        $arResult['SECTIONS'][$arSection['ID']] = $arSection;
        $arResult['LINKS'][$arSection['ID']] = [];
    }

    if ($arParams['ELEMENTS_ROOT'] === 'Y' || $arParams['ELEMENTS_SECTIONS'] === 'Y') {
        $arConditions = [];

        if ($arParams['ELEMENTS_ROOT'] === 'Y')
            $arConditions[] = ['IBLOCK_SECTION_ID' => false];

        if ($arParams['ELEMENTS_SECTIONS'] === 'Y') {
            $rsElements = ArrayHelper::getKeys($arResult['SECTIONS']);

            if (!empty($rsElements))
                $arConditions[] = ['IBLOCK_SECTION_ID' => $rsElements];

            unset($rsElements);
        }

        if (!empty($arConditions)) {
            $arConditions['LOGIC'] = 'OR';
        	$rsElements = CIBlockElement::GetList(['SORT' => 'ASC'], [
        		'IBLOCK_ID' => $arParams['IBLOCK_ID'],
				'ACTIVE' => 'Y',
                $arConditions
			]);

        	if ($arParams['IS_SEF'] !== 'Y') {
                $rsElements->SetUrlTemplates(
                    $arParams['DETAIL_URL'],
                    $arParams['SECTION_URL']
                );
            } else {
        	    $rsElements->SetUrlTemplates(
        	        $arParams['SEF_BASE_URL'].$arParams['DETAIL_PAGE_URL'],
                    $arParams['SEF_BASE_URL'].$arParams['SECTION_PAGE_URL']
                );
            }

        	while ($rsElement = $rsElements->GetNextElement()) {
                $arElement = $rsElement->GetFields();
                $arElement['PROPERTIES'] = $rsElement->GetProperties();

                if (!empty($arElement['IBLOCK_SECTION_ID'])) {
                    $arResult['SECTIONS'][$arElement['IBLOCK_SECTION_ID']]['ELEMENTS'][$arElement['ID']] = $arElement;
                } else {
                    $arResult['ELEMENTS'][$arElement['ID']] = $arElement;
                }
            }
		}

		unset($arConditions);
    }

    if (!empty($arParams['ID']) && (Type::toInteger($arVariables['SECTION_ID']) <= 0)) {
        $rsElement = CIBlockElement::GetList([], [
            'ID' => $arParams['ID'],
            'ACTIVE' => 'Y',
            'ACTIVE_DATE' => 'Y',
            'CHECK_PERMISSIONS' => 'Y',
            'MIN_PERMISSION' => 'R',
            'IBLOCK_ID' => $arParams['IBLOCK_ID'],
            'IBLOCK_ACTIVE' => 'Y'
        ], false, false, [
            'ID',
            'IBLOCK_ID',
            'DETAIL_PAGE_URL',
            'IBLOCK_SECTION_ID'
        ]);

        if ($arParams['IS_SEF'] !== 'Y') {
            $rsElement->SetUrlTemplates(
                $arParams['DETAIL_URL'],
                $arParams['SECTION_URL']
            );
        } else {
            $rsElement->SetUrlTemplates(
                $arParams['SEF_BASE_URL'].$arParams['DETAIL_PAGE_URL'],
                $arParams['SEF_BASE_URL'].$arParams['SECTION_PAGE_URL']
            );
        }

        if ($arElement = $rsElement->GetNext())
            $arResult['LINKS'][$arElement['IBLOCK_SECTION_ID']][] = $arElement['~DETAIL_PAGE_URL'];
    }

    $this->EndResultCache();
}

$iIndex = 0;
$iDepth = 1;

foreach ($arResult['SECTIONS'] as $arSection) {
    if ($iIndex > 0)
        $arMenu[$iIndex - 1][3]['IS_PARENT'] = $arSection['DEPTH_LEVEL'] > $iDepth;

    $iDepth = $arSection['DEPTH_LEVEL'];

    $arResult['LINKS'][$arSection['ID']][] = Url::decode($arSection['~SECTION_PAGE_URL']);
    $arSectionParameters = [
        'IS_PARENT' => !empty($arSection['ELEMENTS']),
        'DEPTH_LEVEL' => $iDepth,
        'FROM_IBLOCK' => $arParams['USUAL'] !== 'Y'
    ];

    if ($arSectionParameters['FROM_IBLOCK'])
        $arSectionParameters['SECTION'] = $arSection;

    $arMenu[$iIndex++] = [
        Html::encode($arSection['~NAME']),
        $arSection['~SECTION_PAGE_URL'],
        $arResult['LINKS'][$arSection['ID']],
        $arSectionParameters
    ];

    if (!empty($arSection['ELEMENTS'])) {
        $iDepth++;

        foreach ($arSection['ELEMENTS'] as $arElement) {
            $arElementParameters = [
                'IS_PARENT' => false,
                'DEPTH_LEVEL' => $iDepth,
                'FROM_IBLOCK' => $arParams['USUAL'] !== 'Y'
            ];

            if ($arElementParameters['FROM_IBLOCK'])
                $arElementParameters['ELEMENT'] = $arElement;

            $arMenu[$iIndex++] = [
                Html::encode($arElement['~NAME']),
                $arElement['~DETAIL_PAGE_URL'], [
                    $arElement['~DETAIL_PAGE_URL']
                ],
                $arElementParameters
            ];
        }
    }
}

foreach ($arResult['ELEMENTS'] as $arElement) {
    $arElementParameters = [
        'IS_PARENT' => false,
        'DEPTH_LEVEL' => 1,
        'FROM_IBLOCK' => $arParams['USUAL'] !== 'Y'
    ];

    if ($arElementParameters['FROM_IBLOCK'])
        $arElementParameters['ELEMENT'] = $arElement;

    $arMenu[$iIndex++] = [
        Html::encode($arElement['~NAME']),
        $arElement['~DETAIL_PAGE_URL'], [
            $arElement['~DETAIL_PAGE_URL']
        ],
        $arElementParameters
    ];
}

return $arMenu;