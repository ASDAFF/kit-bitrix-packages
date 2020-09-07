<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Html;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arBlock
 */

$sPrefix = 'SERVICES_';
$arServices['PARAMETERS'] = [];

foreach ($arParams as $sKey => $mValue) {
    if (StringHelper::startsWith($sKey, $sPrefix)) {
        $sKey = StringHelper::cut(
            $sKey,
            StringHelper::length($sPrefix)
        );

        $arServices['PARAMETERS'][$sKey] = $mValue;
    }
}

$arServices['PARAMETERS'] = ArrayHelper::merge($arServices['PARAMETERS'] , [
    'IBLOCK_TYPE' => $arBlock['IBLOCK']['TYPE'],
    'IBLOCK_ID' => $arBlock['IBLOCK']['ID'],
    'FILTER' => [
        'ID' => $arBlock['IBLOCK']['ELEMENTS']
    ],
    'SETTINGS_USE' => 'N',
    'LAZYLOAD_USE' => $arVisual['LAZYLOAD']['USE'] ? 'Y' : 'N'
]);

?>

<div class="news-detail-services widget">
    <div class="news-detail-services-wrapper intec-content intec-content-visible">
        <div class="news-detail-services-wrapper-2 intec-content-wrapper">
            <?php if (!empty($arBlock['HEADER'])) { ?>
                <div class="news-detail-services-header widget-header">
                    <?= Html::tag('div', $arBlock['HEADER']['VALUE'], [
                        'class' => [
                            'widget-title',
                            'align-'.$arBlock['HEADER']['POSITION']
                        ]
                    ]) ?>
                </div>
            <?php } ?>
            <div class="news-detail-services-content widget-content">
                <?php $APPLICATION->IncludeComponent(
                    'intec.universe:main.services',
                    'template.8',
                    $arServices['PARAMETERS'],
                    $component
                ) ?>
            </div>
        </div>
    </div>
</div>