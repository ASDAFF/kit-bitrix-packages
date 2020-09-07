<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;

/**
 * @var array $arParams
 * @var array $arVisual
 * @var CAllMain $APPLICATION
 * @var CBitrixComponent $component
 */

$sPrefix = 'TAGS_';
$sLength = StringHelper::length($sPrefix);

$arParameters['TEMPLATE'] = ArrayHelper::getValue($arParams, $sPrefix.'TEMPLATE');
$arParameters['PARAMETERS'] = [];

$arExcluded = [
    'USE',
    'TEMPLATE',
    'HEADER_SHOW',
    'HEADER_TEXT'
];

if (!empty($arParameters['TEMPLATE'])) {
    foreach ($arParams as $key => $sValue) {
        if (StringHelper::startsWith($key, $sPrefix)) {
            $key = StringHelper::cut($key, $sLength);

            if (!ArrayHelper::isIn($key, $arExcluded))
                $arParameters['PARAMETERS'][$key] = $sValue;
        }
    }

    unset($key, $sValue);
?>
    <div class="news-additional-tags news-additional-item">
        <div class="news-additional-tags-wrapper">
            <?php if ($arVisual['TAGS']['HEADER']['SHOW']) { ?>
                <div class="news-additional-tags-header">
                    <?= Html::stripTags($arVisual['TAGS']['HEADER']['TEXT'], ['br']) ?>
                </div>
            <?php } ?>
            <div class="news-additional-tags-content">
                <?php $APPLICATION->IncludeComponent(
                    'intec.universe:tags.list',
                    $arParameters['TEMPLATE'],
                    ArrayHelper::merge($arParameters['PARAMETERS'], [
                        'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
                        'IBLOCK_ID' => $arParams['IBLOCK_ID'],
                        'SECTION_ID' => null,
                        'PROPERTY' => $arParams['PROPERTY_TAGS'],
                        'FILTER_NAME' => $arParams['FILTER'],
                        'VARIABLE_TAGS' => $arParams['TAGS_VARIABLE'],
                        'CACHE_TYPE' => $arParams['CACHE_TYPE'],
                        'CACHE_TIME' => $arParams['CACHE_TIME']
                    ]),
                    $component
                ) ?>
            </div>
        </div>
    </div>
<?php } ?>
<?php unset($sPrefix, $sLength, $arParameters, $arExcluded) ?>