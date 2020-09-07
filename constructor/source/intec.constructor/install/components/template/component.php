<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Data\Cache;
use Bitrix\Main\Loader;
use intec\core\db\ActiveRecords;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;
use intec\constructor\models\build\Area;
use intec\constructor\models\build\Template;
use intec\constructor\models\build\template\Containers;
use intec\constructor\models\build\template\Container;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $this
 */

if (!Loader::includeModule('intec.core'))
    return null;

if (!Loader::IncludeModule('intec.constructor'))
    return null;

$arParams = ArrayHelper::merge([
    'TEMPLATE_ID' => null,
    'DISPLAY' => 'HEADER',
    'CACHE_USE' => 'Y',
    'CACHE_TIME' => 3600
], $arParams);

$arResult['DISPLAY'] = ArrayHelper::fromRange([
    'HEADER',
    'FOOTER'
], $arParams['DISPLAY']);

$arResult['CACHE'] = [
    'USE' => $arParams['CACHE_USE'] === 'Y',
    'TIME' => Type::toInteger($arParams['CACHE_TIME'])
];

if ($arResult['CACHE']['TIME'] < 1)
    $arResult['CACHE']['USE'] = false;

$oCache = Cache::createInstance();
$arData = ArrayHelper::getValue($arParams, 'DATA');

/**
 * Шаблон с контейнерами компонентами и виджетами.
 * @var Template $oTemplate
 */
$oTemplate = ArrayHelper::getValue($arData, 'template');

if (!$oTemplate instanceof Template)
    $oTemplate = Template::find()
        ->where(['id' => $arParams['TEMPLATE_ID']])
        ->one();

if (empty($oTemplate))
    return null;

$oBuild = $oTemplate->getBuild(true);

if (empty($oBuild))
    return null;

/**
 * Коллекция всех зон синхронизации.
 * @var ActiveRecords $oAreas
 */
$oAreas = null;

/**
 * Коллекция всех контейнеров шаблона.
 * @var Containers $oContainers
 */
$oContainers = null;

if ($arResult['CACHE']['USE'] && $oCache->initCache(
        $arResult['CACHE']['TIME'],
        $this->getName().':'.$oBuild->id.':'.$oTemplate->id,
        '/'
    )) {
    $arVariables = $oCache->getVars();
    $oAreas = $arVariables['areas'];
    $oContainers = $arVariables['containers'];

    unset($arVariables);
} else {
    $bCached = false;

    if ($arResult['CACHE']['USE'])
        $bCached = $oCache->startDataCache();

    /**
     * Коллекция всех зон синхронизации.
     * @var ActiveRecords $oAreas
     */
    $oAreas = ArrayHelper::getValue($arData, 'areas');

    if (!$oAreas instanceof ActiveRecords)
        $oAreas = $oBuild->getAreas(true);

    /**
     * Коллекция всех контейнеров шаблона.
     * @var Containers $oContainers
     */
    $oContainers = ArrayHelper::getValue($arData, 'containers');

    if (!$oContainers instanceof Containers) {
        if (!$oTemplate->isRelationPopulated('containers')) {
            $arConditions = [
                'or',
                ['templateId' => $oTemplate->id]
            ];

            if (!$oAreas->isEmpty())
                $arConditions[] = ['areaId' => $oAreas->asArray(function ($iIndex, $oArea) {
                    /** @var Area $oArea */

                    return [
                        'value' => $oArea->id
                    ];
                })];

            $oContainers = Container::find()
                ->where($arConditions)
                ->with([
                    'link',
                    'area',
                    'component',
                    'widget',
                    'block',
                    'variator',
                    'variator.variants'
                ])
                ->all();
        } else {
            $oContainers = $oTemplate->getContainers(true);
        }
    }

    if ($bCached)
        $oCache->endDataCache([
            'areas' => $oAreas,
            'containers' => $oContainers
        ]);

    unset($bCached);
}

if (!$oTemplate->isRelationPopulated('containers'))
    $oTemplate->populateRelation('containers', $oContainers->asArray());

/**
 * Корневой контейнер.
 * @var Container $oContainer
 */
$oContainer = ArrayHelper::getValue($arData, 'container');

if (!$oContainer instanceof Container)
    $oContainer = $oContainers->getTree($oBuild, $oTemplate);

if (empty($oContainer))
    return null;

$arSettings = ArrayHelper::merge([
    'text' => [
        'color' => null,
        'font' => null,
        'size' => [
            'value' => null,
            'measure' => 'px'
        ],
        'lineHeight' => [
            'value' => null,
            'measure' => 'px'
        ],
        'letterSpacing' => [
            'value' => null,
            'measure' => 'px'
        ],
        'uppercase' => false
    ]
], $oTemplate->settings);

if (!empty($arSettings['text']['color']))
    $oContainer->setStyleColor(
        $arSettings['text']['color']
    );

if (!empty($arSettings['text']['font']))
    $oContainer->setStyleFontFamily(
        $arSettings['text']['font']
    );

if (!empty($arSettings['text']['size']['value']))
    $oContainer->setStyleFontSize(
        $arSettings['text']['size']['value'],
        $arSettings['text']['size']['measure']
    );

if (!empty($arSettings['text']['lineHeight']['value']))
    $oContainer->setStyleLineHeight(
        $arSettings['text']['lineHeight']['value'],
        $arSettings['text']['lineHeight']['measure']
    );

if (!empty($arSettings['text']['letterSpacing']['value']))
    $oContainer->setStyleLetterSpacing(
        $arSettings['text']['letterSpacing']['value'],
        $arSettings['text']['letterSpacing']['measure']
    );

if ($arSettings['text']['uppercase'])
    $oContainer->setStyleTextTransform('uppercase');

$arResult['BUILD'] = $oBuild;
$arResult['TEMPLATE'] = $oTemplate;
$arResult['CONTAINER'] = $oContainer;

if ($arResult['DISPLAY'] == 'HEADER')
    $oContainer->execute(true);

$this->IncludeComponentTemplate();

return [
    'areas' => $oAreas,
    'containers' => $oContainers,
    'container' => $oContainer,
    'template' => $oTemplate
];