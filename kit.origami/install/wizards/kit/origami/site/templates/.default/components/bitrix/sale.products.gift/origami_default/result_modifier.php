<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$ids = [];

$tmp = [];
$tmpD = [];
if($arResult['ITEMS']){
    foreach($arResult['ITEMS'] as $j => $item){
        $tmp[$item['ID']] = $item['PREVIEW_PICTURE'];
        $tmpD[$item['ID']] = $item['DETAIL_PICTURE'];
        if($item['OFFERS']){
            foreach($item['OFFERS'] as $offer){
                $tmp[$offer['ID']] = $offer['PREVIEW_PICTURE'];
                $tmpD[$offer['ID']] = $offer['DETAIL_PICTURE'];
            }
        }
    }
}

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();
$arResult['SKU'] = $component->getTemplateSkuPropList();

if($arResult['ITEMS']) {
    foreach ($arResult['ITEMS'] as $j => $item) {
        $arResult['ITEMS'][$j]['PREVIEW_PICTURE'] = $tmp[$item['ID']];
        $arResult['ITEMS'][$j]['DETAIL_PICTURE'] = $tmpD[$item['ID']];
        if ($item['JS_OFFERS']) {
            foreach ($item['JS_OFFERS'] as $i => $offer) {
                $arResult['ITEMS'][$j]['JS_OFFERS'][$i]['PREVIEW_PICTURE']
                    = $tmp[$offer['ID']];
                $arResult['ITEMS'][$j]['JS_OFFERS'][$i]['DETAIL_PICTURE']
                    = $tmpD[$offer['ID']];
            }
        }
    }
}

if (!empty($arResult['ITEMS'])  && \Bitrix\Main\Loader::includeModule('kit.origami')){
    foreach ($arResult['ITEMS'] as $i => $item){
        if(empty($item['OFFERS'])){
            /*unset($arResult['ITEMS'][$i]);
            continue;*/
        }
        $ids[] = $item['ID'];
    }

    if($ids){
        $rs = \CIBlockElement::GetList(
            [],
            [
                'IBLOCK_ID' => \Kit\Origami\Config\Option::get('IBLOCK_ID_PROMOTION'),
                'PROPERTY_LINK_PRODUCTS' => $ids
            ],
            false,
            false,
            ['ID','IBLOCK_ID','PROPERTY_LINK_PRODUCTS']
        );
        while($promotion = $rs->Fetch()){
            foreach ($arResult['ITEMS'] as &$item){
                if($item['ID'] == $promotion['PROPERTY_LINK_PRODUCTS_VALUE']){
                    $item['PROMOTION'] = 'Y';
                    break;
                }
            }
        }
    }
}
?>