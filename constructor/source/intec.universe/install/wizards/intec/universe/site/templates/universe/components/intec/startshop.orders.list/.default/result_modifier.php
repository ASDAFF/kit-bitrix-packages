<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;

/**
 * @var array $arResult
 */

$arCurrency = CStartShopCurrency::GetBase()->Fetch();
$arStatuses = [];

foreach ($arResult['ORDERS'] as &$arItem) {
    if (!empty($arItem['DELIVERY'])) {
        $arItem['DELIVERY']['PRICE'] = [
            'VALUE' => $arItem['DELIVERY']['PRICE'],
            'PRINT_VALUE' => CStartShopCurrency::FormatAsString(CStartShopCurrency::Convert($arItem['DELIVERY']['PRICE'] , $arCurrency))
        ];
    }

    if (empty($arStates[$arItem['STATUS']['ID']]))
        $arStatuses[$arItem['STATUS']['ID']] = [
        'CODE' => ArrayHelper::getValue($arItem['STATUS'], 'CODE'),
        'NAME' => ArrayHelper::getValue($arItem['STATUS']['LANG'][LANGUAGE_ID], 'NAME')
    ];
}

$arResult['STATUSES'] = $arStatuses;

unset($arItem, $arCurrency, $arStatuses);