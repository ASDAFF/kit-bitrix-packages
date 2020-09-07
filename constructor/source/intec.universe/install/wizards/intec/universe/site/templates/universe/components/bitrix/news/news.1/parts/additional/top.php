<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;

/**
 * @var array $arResult
 * @var array $arParams
 * @var array $arVisual
 * @var string $sPart
 * @var CAllMain $APPLICATION
 * @var CBitrixComponent $component
 */

$sPrefix = 'TOP_';
$sLength = StringHelper::length($sPrefix);

$arParameters = [];

$arExcluded = [
    'USE',
    'PAGES',
    'COUNT',
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

if ($sPart === 'detail')
    $arParameters['TAGS_MODE'] = 'default';

?>
<div class="news-additional-top news-additional-item">
    <div class="news-additional-top-wrapper">
        <?php if ($arVisual['TOP']['HEADER']['SHOW']) { ?>
            <div class="news-additional-top-header">
                <?= Html::stripTags($arVisual['TOP']['HEADER']['TEXT'], ['br']) ?>
            </div>
        <?php } ?>
        <div class="news-additional-top-content">
            <?php $APPLICATION->IncludeComponent(
                'intec.universe:main.news',
                'template.7',
                ArrayHelper::merge($arParameters, [
                    'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
                    'IBLOCK_ID' => $arParams['IBLOCK_ID'],
                    'ELEMENTS_COUNT' => $arParams['TOP_COUNT'],
                    'PROPERTY_TAGS' => $arParams['PROPERTY_TAGS'],
                    'DATE_FORMAT' => $arParams['LIST_ACTIVE_DATE_FORMAT'],
                    'TAGS_VARIABLE' => $arParams['TAGS_VARIABLE'],
                    'DETAIL_URL' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['detail'],
                    'CACHE_TYPE' => $arParams['CACHE_TYPE'],
                    'CACHE_TIME' => $arParams['CACHE_TIME']
                ])
            ) ?>
        </div>
    </div>
</div>
<?php unset($sPrefix, $sLength, $arParameters, $arExcluded) ?>