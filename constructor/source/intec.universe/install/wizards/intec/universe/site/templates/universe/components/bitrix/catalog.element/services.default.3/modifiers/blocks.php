<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 */

$arResult['BLOCKS'] = include(__DIR__.'/../common/blocks.php');

/** Структура блоков */
foreach ($arResult['BLOCKS'] as &$arBlock) {
    $arBlock['ACTIVE'] = false;
    $arBlock['SORT'] = -1;

    if (!isset($arBlock['SORTABLE']))
        $arBlock['SORTABLE'] = true;

    if (!isset($arBlock['TYPE']))
        $arBlock['TYPE'] = 'normal';
}

unset($arBlock);

/** Активность блоков */
if (isset($arParams['BLOCKS']) && Type::isArray($arParams['BLOCKS'])) {
    foreach ($arParams['BLOCKS'] as $sBlock)
        if (isset($arResult['BLOCKS'][$sBlock]))
            $arResult['BLOCKS'][$sBlock]['ACTIVE'] = true;

    unset($sBlock);
}

/** Сортировка блоков */
$iSort = 0;

foreach ($arResult['BLOCKS'] as $sBlock => &$arBlock) {
    if (!$arBlock['SORTABLE']) {
        $arBlock['SORT'] = $iSort;
        $iSort++;
    }
}

if (isset($arParams['BLOCKS_ORDER']) && Type::isString($arParams['BLOCKS_ORDER'])) {
    $arOrder = explode(',', $arParams['BLOCKS_ORDER']);

    foreach ($arOrder as $sBlock) {
        if (isset($arResult['BLOCKS'][$sBlock])) {
            $arResult['BLOCKS'][$sBlock]['SORT'] = $iSort;
            $iSort++;
        }
    }

    unset($sBlock);
    unset($arOrder);
}

foreach ($arResult['BLOCKS'] as $sBlock => &$arBlock) {
    if ($arBlock['SORT'] < 0)
        $arBlock['SORT'] = $iSort;
}

unset($arBlock);
unset($iSort);

uasort($arResult['BLOCKS'], function ($arBlockLeft, $arBlockRight) {
    return $arBlockLeft['SORT'] - $arBlockRight['SORT'];
});