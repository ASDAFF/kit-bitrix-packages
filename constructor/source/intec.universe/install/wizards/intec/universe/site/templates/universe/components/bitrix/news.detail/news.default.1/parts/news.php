<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;

/**
 * @var array $arParams
 * @var array $arVisual
 * @var array $arData
 * @var CAllMain $APPLICATION
 * @var CBitrixComponent $component
 */

$sPrefix = 'ADDITIONAL_NEWS_';
$sLength = StringHelper::length($sPrefix);

$arParameters = [];

$arExcluded = [
    'SHOW',
    'HEADER_SHOW',
    'HEADER_TEXT'
];

foreach ($arParams as $key => $sValue) {
    if (StringHelper::startsWith($key, $sPrefix)) {
        $key = StringHelper::cut($key, $sLength);

        if (!ArrayHelper::isIn($key, $arExcluded))
            $arParameters[$key] = $sValue;
    }
}

unset($key, $sValue);

?>
<div class="news-detail-additional-item">
    <?php if ($arVisual['ADDITIONAL']['NEWS']['HEADER']['SHOW']) { ?>
        <div class="news-detail-additional-header intec-template-part intec-template-part-title">
            <?= Html::stripTags($arVisual['ADDITIONAL']['NEWS']['HEADER']['TEXT'], ['br']) ?>
        </div>
    <?php } ?>
    <div class="news-detail-additional-content">
        <?php $APPLICATION->IncludeComponent(
            'intec.universe:main.news',
            'template.2',
            ArrayHelper::merge($arParameters, [
                'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
                'IBLOCK_ID' => $arParams['IBLOCK_ID'],
                'DATE_FORMAT' => $arParams['DATE_FORMAT'],
                'DATE_TYPE' => $arParams['DATE_TYPE'],
                'DETAIL_URL' => $arParams['DETAIL_URL'],
                'CACHE_TYPE' => $arParams['CACHE_TYPE'],
                'CACHE_TIME' => $arParams['CACHE_TIME'],
                'FILTER' => [
                    'ID' => $arData['ADDITIONAL']['NEWS']
                ],
                'SETTINGS_USE' => 'N',
                'LAZYLOAD_USE' => $arParams['LAZYLOAD_USE']
            ]),
            $component
        ) ?>
    </div>
</div>
<?php unset($sPrefix, $sLength, $arParameters, $arExcluded) ?>