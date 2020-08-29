<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Sotbit\Origami\Config\Option;
use \Bitrix\Main\Data\Cache;
use Bitrix\Main\Localization\Loc;
use Sotbit\Origami\Helper\Config;
use Bitrix\Main\Loader;

Loc::loadMessages(__FILE__);
$tmp = [];
$tmpD = [];
$tmp[$arResult['ID']] = $arResult['PREVIEW_PICTURE'];
if($arResult['OFFERS']){
    foreach($arResult['OFFERS'] as $offer){
        $tmp[$offer['ID']] = $offer['PREVIEW_PICTURE'];
        $tmpD[$offer['ID']] = $offer['DETAIL_PICTURE'];
    }
}

$checkSlider = $arParams['ADD_DETAIL_TO_SLIDER'];
$arParams['ADD_DETAIL_TO_SLIDER'] = "N";
$arTmpPhoto = $arResult["MORE_PHOTO"];
unset($arResult["MORE_PHOTO"]);
$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();
$arResult["MORE_PHOTO"] = $arTmpPhoto;
$arParams['ADD_DETAIL_TO_SLIDER'] = $checkSlider;
unset($arTmpPhoto);

$arResult[$arResult['ID']] = $tmp[$arResult['ID']];

if($arResult['JS_OFFERS']){
    foreach($arResult['JS_OFFERS'] as $i => $offer)
    {
        $arResult['JS_OFFERS'][$i]['PREVIEW_PICTURE'] = $tmp[$offer['ID']];
        $arResult['JS_OFFERS'][$i]['DETAIL_PICTURE'] = $tmpD[$offer['ID']];
    }
}

$template = $this->__name;
if ($template == '.default') {
    $template = '';
} else if ($this->__name == 'origami_no_tabs') {
    $template = 'NO_TABS';
}

$arResult['TEMPLATE'] = $template;

$arResult['SKU'] = $arResult['ID'];
if($arResult['OFFERS'])
{
    $arResult['SKU'] = $arResult['OFFERS'][0]['ID'];
}

if (!empty($arResult['DISPLAY_PROPERTIES']))
{
    foreach ($arResult['DISPLAY_PROPERTIES'] as &$property)
    {
        if(!is_array($property['DISPLAY_VALUE']))
        {
            $property['DISPLAY_VALUE'] = explode('||',$property['DISPLAY_VALUE']);
            $value = '';
            //$cnt = count($property['DISPLAY_VALUE']);
            foreach ($property['DISPLAY_VALUE'] as $i => $v)
            {
                $value .= trim($v);
                $property['DISPLAY_VALUE'][$i] = $v;
                /*if($i != $cnt-1)
                {
                    $value.=' / ';
                }*/
            }
            //$property['DISPLAY_VALUE'] = $value;
        }
    }
}

$typeCharacteristics = Config::get('PROP_FILTER_MODE_'.$template);

if($typeCharacteristics != '')
{
    $Filter = new \Sotbit\Origami\Helper\Filter();
    $arResult['DISPLAY_PROPERTIES'] = $Filter->getCharacteristics($arResult["SECTION"]["IBLOCK_ID"], $arResult["SECTION"]["ID"], $arResult["SECTION"]["SECTION_PAGE_URL"], $arResult['DISPLAY_PROPERTIES'], $typeCharacteristics, $arParams);
}

$arResult["ALL_PRICES_NAMES"] = \SotbitOrigami::getAllNamePrices($arResult);

\SotbitOrigami::checkPriceDiscount($arResult);

$arResult = \SotbitOrigami::changeColorImages($arResult);

$Element = new \Sotbit\Origami\Image\Element($template);
$arResult = $Element->prepareImages($arResult);

$color = \Sotbit\Origami\Helper\Color::getInstance(SITE_ID);
$arResult = $color::changePropColorView($arResult, $arParams)['RESULT'];


$arResult["ITEM_PRICE_DELTA"] = \SotbitOrigami::getPriceDelta($arResult, $template);



$arResult['BRAND'] = [];
if($arParams['BRAND_USE'] && $arParams['BRAND_PROP_CODE'])
{
    $Brand = new \Sotbit\Origami\Brand($template);
    $Brand->setBrandProps($arParams['BRAND_PROP_CODE']);
    $Brand->setResize(['width' => 205,'height' => 50,'type' => BX_RESIZE_IMAGE_PROPORTIONAL]);
    $arResult['BRAND'] = $Brand->findBrandsForElement($arResult['PROPERTIES']);
}
/**/

$arResult['ADVANTAGES_SECTIONS'] = [];

if($arResult['IBLOCK_SECTION_ID'] > 0)
{
    $navChain = \CIBlockSection::GetNavChain($arParams['IBLOCK_ID'], $arResult['IBLOCK_SECTION_ID']);
    while ($arNav = $navChain->Fetch())
    {
        $arResult['ADVANTAGES_SECTIONS'][] = $arNav['ID'];
    }
}
$arResult['ADVANTAGES_SECTIONS'][] = false;

$arResult['VIDEO'] = [];

$videoProp = Option::get('PROP_VIDEO_'.$template);
if($arResult['PROPERTIES'][$videoProp]['VALUE']){
    foreach($arResult['PROPERTIES'][$videoProp]['VALUE'] as $url){
        $Video = new \Sotbit\Origami\Video($url);
        $arResult['VIDEO'][] = $Video->getContent();
    }
}

if($arResult['OFFERS'] && $arResult['SKU_PROPS']) {
    foreach($arResult['SKU_PROPS'] as $code => $sku) {
        $values = [];
        $table = '';
        foreach ($arResult['OFFERS'] as $i => $o) {
            if ($o['PROPERTIES'][$code]['VALUE']) {
                $table = $o['PROPERTIES'][$code]['USER_TYPE_SETTINGS']['TABLE_NAME'];
                $values[$i] = $o['PROPERTIES'][$code]['VALUE'];
            }
        }

        if ($table && $values) {
            $HL = \Bitrix\Highloadblock\HighloadBlockTable::getList([
                "filter" => [
                    'TABLE_NAME' => $table,
                ],
                'limit'  => 1,
            ])->Fetch();
            if ($HL['ID'] > 0) {
                $HLEntity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($HL)->getDataClass();
                $rs = $HLEntity::getList([
                    'filter' => [
                        'UF_XML_ID' => $values,
                    ]
                ]);
                while ($row = $rs->fetch()) {
                    foreach($arResult['OFFERS'] as $i => $o){
                        if($o['PROPERTIES'][$code]['VALUE'] == $row['UF_XML_ID']){
                            $arResult['OFFERS'][$i]['PROPERTIES'][$code]['DISPLAY_VALUE'] = $row['UF_NAME'];
                        }
                    }
                }
            }
        }
    }
}

$colorCode = \Sotbit\Origami\Helper\Config::get('COLOR');
if($arResult['SKU_PROPS'][$colorCode]) {
    $tmp = [$colorCode => $arResult['SKU_PROPS'][$colorCode]];
    foreach($arResult['SKU_PROPS'] as $code => $prop){
        if($code == $colorCode){
            continue;
        }
        $tmp[$code] = $prop;
    }
    $arResult['SKU_PROPS'] = $tmp;
}

if(
    Option::get('SHOW_ANALOG_'.$template) == 'Y' &&
    $arResult['PROPERTIES'][Option::get('ANALOG_PROP_'.$template)]['VALUE']
){
    $analogProducts = $arResult['PROPERTIES'][Option::get('ANALOG_PROP_'.$template)]['VALUE'];
}

$showDescription = !empty($arResult['PREVIEW_TEXT']) ? $arResult['PREVIEW_TEXT'] : $arResult['DETAIL_TEXT'];

$arResult['TABS'] = [];
$arResult['DOCS'] = [];
$arResult['VIDEO_CONTENT'] = [];
$tabs = unserialize(Config::get('TABS_'.$template));
if($tabs){
    foreach($tabs as $tab){
        if($tab == 'AVAILABLE' && $arResult['OFFERS']){
            continue;
        }

        if($tab == 'DESCRIPTION' && empty($showDescription)){
            continue;
        }

        if($tab == 'PROPERTIES' && (empty($arResult['DISPLAY_PROPERTIES']) ||
                ( is_array($arResult['DISPLAY_PROPERTIES']) && count($arResult['DISPLAY_PROPERTIES']) < 1 ))) {
            continue;
        }

		if($tab == 'DELIVERY' && !file_exists($_SERVER['DOCUMENT_ROOT'].'/bitrix/components/kit/regions.delivery/class.php')){
			continue;
		}

        if(Config::get('ACTIVE_TAB_'.$tab.'_'.$template) == 'Y'){
            $name = Option::get('NAME_TAB_'.$tab.'_'.$template);

            if($tab == 'DOCS'){
                $propDoc = Option::get('PROP_TAB_DOCS_'.$template);
                if($propDoc && $arResult['PROPERTIES'][$propDoc]['VALUE']){
                    $link = '';
                    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'){
                        $link.='https://';
                    }
                    else{
                        $link.='http://';
                    }

                    $server = \Bitrix\Main\Context::getCurrent()->getServer();
                    $link.=$server->getServerName();
                    foreach($arResult['PROPERTIES'][$propDoc]['VALUE'] as $doc){
                        if(file_exists($_SERVER['DOCUMENT_ROOT'].$doc)){
                            $arResult['DOCS'][] = [
                                'SIZE' => \SotbitOrigami::FormatFileSize(filesize($_SERVER['DOCUMENT_ROOT'].$doc)),
                                'PATH' => $doc,
                                'NAME' => end(explode('/',$doc)),
                                'LINK' => $link.$doc
                            ];
                        }
                    }
                }
                if(!$arResult['DOCS']){
                    continue;
                }
            }

            if($tab == 'VIDEO'){
                $propVideo = Option::get('PROP_TAB_VIDEO_'.$template);
                if($propVideo && $arResult['PROPERTIES'][$propVideo]['VALUE']){
                    foreach ($arResult['PROPERTIES'][$propVideo]['VALUE'] as $url)
                    {
                        $Video = new \Sotbit\Origami\Video($url);
                        $arResult['VIDEO_CONTENT'][] = $Video->getContent();
                    }
                }
                if(!$arResult['VIDEO_CONTENT']){
                    continue;
                }
            }

            $arResult['TABS'][] = [
                'NAME' => ($name)?$name:Loc::getMessage('TAB_NAME_'.$tab),
                'TYPE' => $tab
            ];
        }
    }
}

if (Bitrix\Main\Loader::includeModule( "kit.price" ))
{
    //$arResult = SotbitPrice::ChangeMinPrice( $arResult );
}
if (Bitrix\Main\Loader::includeModule( "kit.regions" ))
{
    //$arResult = \Sotbit\Regions\Sale\Price::change( $arResult );
}

$arResult["SHOW_BUY"] = 0;
$arResult["SHOW_DELAY"] = 0;
$arResult["SHOW_COMPARE"] = 0;

if(isset($arParams['ACTION_PRODUCTS']))
{
    if(in_array("BUY", $arParams['ACTION_PRODUCTS']))
        $arResult["SHOW_BUY"] = 1;

    if(in_array("DELAY", $arParams['ACTION_PRODUCTS']))
        $arResult["SHOW_DELAY"] = 1;

    if(in_array("COMPARE", $arParams['ACTION_PRODUCTS']))
        $arResult["SHOW_COMPARE"] = 1;
}

$arResult['FIRST_OFFERS_SELECTED'] = $arResult['OFFERS_SELECTED'];
\SotbitOrigami::checkOfferPage($arResult, $arParams);
$arResult['OFFERS_SELECTED'] = \SotbitOrigami::getOffersSelected($arResult, $arParams);
$arResult['IPROPERTY_VALUES'] = \SotbitOrigami::getSeoOffer($arResult);


$arFilter["IBLOCK_ID"] = $arParams["IBLOCK_ID"];
$arFilter["ACTIVE"] = "Y";
$arFilter["SECTION_ID"] = $arResult["IBLOCK_SECTION_ID"];
$arSelect = array("ID", "NAME", "PREVIEW_PICTURE", "DETAIL_PICTURE", "DETAIL_PAGE_URL");
$rsItems = CIBlockElement::GetList(array($arSort), $arFilter, false, array(), $arSelect);
while ($item = $rsItems->GetNext())
    if ($item != false)
        $lastItem = $item;
$rsElement = CIBlockElement::GetList(array($arSort), $arFilter, false, array("nPageSize"=>2, "nElementID"=>$arResult["ID"]), $arSelect);
$arElementNav = array();
while($arElement = $rsElement->GetNext()) {
    $arElementNav[] = $arElement;
}

foreach ($arElementNav as $key => $item) {
    if ($item['ID'] == $arResult['ID']) {

        if ($arElementNav[$key+1]) {
            $nextItem = $arElementNav[$key+1];
        } else {
            $nextItem = CIBlockElement::GetList(array($arSort), $arFilter, false, array("nPageSize"=>1), $arSelect)->GetNext();
        }

        if ($arElementNav[$key-1]) {
            $prevItem = $arElementNav[$key-1];
        } else {
            $prevItem = $lastItem;
        }

    }
}

$arResult['PREV_ITEM'] = $prevItem;
$arResult['NEXT_ITEM'] = $nextItem;

$this->__component->arResultCacheKeys = array_merge( $this->__component->arResultCacheKeys, [
    'ADVANTAGES_SECTIONS',
    'OFFERS',
    'SKU_PROPS',
    'PROPERTIES',
    'ITEM_MEASURE',
    'ITEM_MEASURE_RATIOS',
    'TABS',
    'ID',
    'OFFERS_SELECTED',
    'FIRST_OFFERS_SELECTED',
    'SECTION',
    'IBLOCK_ID',
    'QUANTITY',
    'PRODUCT_PROVIDER_CLASS',
    'MODULE',
    'OFFERS_IBLOCK',
    'CATALOG',
    'PREVIEW_TEXT',
    'DETAIL_TEXT',
    'PREVIEW_PICTURE',
    'DETAIL_PICTURE',
    'BRAND',
    'DETAIL_PAGE_URL',
    'SKU',
    'SHOW_BUY',
    'SHOW_DELAY',
    'SHOW_COMPARE',
    'ALL_PRICES_NAMES'
] );
?>
