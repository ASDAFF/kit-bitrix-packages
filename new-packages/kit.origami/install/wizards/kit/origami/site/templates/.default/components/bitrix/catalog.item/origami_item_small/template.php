<?

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Kit\Origami\Helper\Config;

CJSCore::Init(array('currency'));

$this->setFrameMode(true);

if (isset($arResult['ITEM']))
{
    $item = $arResult['ITEM'];
    $areaId = $arResult['AREA_ID'];

    $obName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $areaId);
    $isBig = isset($arResult['BIG']) && $arResult['BIG'] === 'Y';

    $productTitle = isset($item['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $item['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] != ''
        ? $item['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
        : $item['NAME'];

    $imgTitle = isset($item['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']) && $item['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE'] != ''
        ? $item['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']
        : $item['NAME'];

    $imgAlt = isset($item['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_ALT']) && $item['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_ALT'] != ''
        ? $item['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_ALT']
        : $item['NAME'];

    $skuProps = array();

    $haveOffers = !empty($item['OFFERS']);

    $allPrices = [];

    if ($haveOffers)
    {
        $actualItem = isset($item['OFFERS'][$item['OFFERS_SELECTED']])
            ? $item['OFFERS'][$item['OFFERS_SELECTED']]
            : reset($item['OFFERS']);
    }
    else
    {
        $actualItem = $item;
    }

    if ($arParams['PRODUCT_DISPLAY_MODE'] === 'N' && $haveOffers)
    {
        $price = $item['ITEM_START_PRICE'];
    }
    else
    {
        $price = $actualItem['ITEM_PRICES'][$actualItem['ITEM_PRICE_SELECTED']];
    }

    $strLazyLoad = '';

    if($arResult['LAZY_LOAD'])
    {
        $strLazyLoad = 'src="'.SITE_TEMPLATE_PATH.'/assets/img/loader_lazy.svg" data-src="'.$item['PICT']['SRC'].'"';
    }else
    {
        $strLazyLoad = 'src="'.$item['PICT']['SRC'].'"';
    }

    $this->AddEditAction($item['ID'], $item['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
    $this->AddDeleteAction($item['ID'], $item['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));

    ?>
	<div id="<?=$this->GetEditAreaID($item['ID'])?>" class="check_basket_<?=$item['ID']?>">
		<div class="small-product__product-inner" id="<?=$areaId?>" data-entity="item">
            <?
            $documentRoot = Main\Application::getDocumentRoot();
            $templatePath = strtolower($arResult['TYPE']).'/template.php';
            $file = new Main\IO\File($documentRoot.$templateFolder.'/'.$templatePath);
            if ($file->isExists())
            {
                include($file->getPath());
            }
            ?>
		</div>
	</div>
    <?
    unset($item, $actualItem, $minOffer);
}
