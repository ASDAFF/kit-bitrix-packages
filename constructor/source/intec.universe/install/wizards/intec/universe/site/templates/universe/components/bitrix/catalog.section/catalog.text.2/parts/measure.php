<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 */

$vMeasure = function (&$arItem) use (&$arVisual) {
    $fRender = function (&$arItem, $bOffer = false) {
        if (!empty($arItem['OFFERS']) && !$bOffer) return ?>
        <?= Html::tag('span', Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_QUANTITY_RATIO', [
            '#QUANTITY_RATIO#' => !empty($arItem['CATALOG_MEASURE_RATIO']) ? $arItem['CATALOG_MEASURE_RATIO'] : '1',
            '#MEASURE#' => $arItem['CATALOG_MEASURE_NAME']
        ]), [
            'data-offer' => $bOffer ? $arItem['ID'] : 'false'
        ]) ?>
    <?php };

    $fRender($arItem);

if ($arItem['ACTION'] === 'buy' && $arVisual['OFFERS']['USE'] && !empty($arItem['OFFERS']))
    foreach ($arItem['OFFERS'] as &$arOffer) {
        $fRender($arOffer, true);

        unset($arOffer);
    }
};
