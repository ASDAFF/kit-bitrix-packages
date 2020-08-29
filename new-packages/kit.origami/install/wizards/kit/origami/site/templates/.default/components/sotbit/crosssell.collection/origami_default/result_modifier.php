<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
use Sotbit\Origami\Helper\Config;
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$promoIds = array();

global $USER;

$cache_id = serialize(array($arParams, ($arParams['CACHE_GROUPS']==='N'? false: $USER->GetGroups())));
$obCache = new CPHPCache;

if ($obCache->InitCache($arParams['CACHE_TIME'], $cache_id, 'sotbit.crosssell'))
{
    $vars = $obCache->GetVars();
    $promoIds = $vars['promoIds'];
}
elseif ($obCache->StartDataCache())
{
    $rs = CIBlockElement::GetList(
        array(),
        array(
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => Config::get('IBLOCK_ID_PROMOTION'),
            '!PROPERTY_LINK_PRODUCTS' => false
        ),
        false,
        false,
        array('PROPERTY_LINK_PRODUCTS')
    );

    while($el = $rs->Fetch())
    {
        if($el['PROPERTY_LINK_PRODUCTS_VALUE'] > 0)
        {
            $promoIds[] = $el['PROPERTY_LINK_PRODUCTS_VALUE'];
        }
    }

    $obCache->EndDataCache(array(
        'promoIds' => $promoIds,
    ));
}

if (count($promoIds) > 0)
{
    $arResult['COLLECTION_LIST_NAMES'] = array('PROMOTION_ID' => Loc::getMessage('PROMOTION_TITLE')) + $arResult['COLLECTION_LIST_NAMES'];
    $arResult['PROMOTION_FILTER_ARRAY'] = array('=ID' => $promoIds);
}