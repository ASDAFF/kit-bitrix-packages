<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\Core;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 */

$arCompare = Core::$app->session->get($arParams['COMPARE_NAME']);

if (Type::isArray($arCompare)) {
    foreach ($arCompare as $arIBlock) {
        $arItems = ArrayHelper::getValue($arIBlock, 'ITEMS');

        if (Type::isArray($arItems)) {
            foreach ($arItems as $arItem) {
                $iId = ArrayHelper::getValue($arItem, 'ID');

                if (!empty($iId))
                    $arResult['COMPARE'][] = [
                        'id' => $iId
                    ];
            }
        }
    }
}

unset($iId);
unset($arItem);
unset($arItems);
unset($arIBlock);
unset($arCompare);