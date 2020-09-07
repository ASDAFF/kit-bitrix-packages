<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $templateData */
/** @var @global CMain $APPLICATION */

$oMenu = new \Kit\Origami\Helper\Menu();
$arResult = $oMenu->getMenuRootCatalog($arResult, false, $arParams['IBLOCK_ID']);
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
if($request->get('action_ccr')=="DELETE_FROM_COMPARE_RESULT" && $request->get('ID') == 'ALL;')
{
    foreach ($_SESSION['CATALOG_COMPARE_LIST'][$arParams['IBLOCK_ID']]['ITEMS'] as $key => $item) {
        unset($_SESSION['CATALOG_COMPARE_LIST'][$arParams['IBLOCK_ID']]['ITEMS'][$key]);
    }
    if (count($_SESSION['CATALOG_COMPARE_LIST'][$arParams['IBLOCK_ID']]['ITEMS']) == 0) {
        LocalRedirect($arResult[0]['LINK']);
    }

}
