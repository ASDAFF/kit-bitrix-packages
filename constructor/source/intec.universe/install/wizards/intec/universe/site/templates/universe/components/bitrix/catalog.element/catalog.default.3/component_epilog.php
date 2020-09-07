<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Error;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\Core;

global $APPLICATION;

if (!Loader::includeModule('intec.core'))
    return;

if ($this->arResult['TAB']['USE'] && !empty($this->arResult['TAB']['VALUE'])) {
    $this->errorCollection->setError(
        new Error(Loc::getMessage('CATALOG_ELEMENT_NOT_FOUND'),
            CatalogElementComponent::ERROR_404)
    );
}

if (!empty($arResult['DETAIL_PICTURE']))
    $sPicture = $arResult['DETAIL_PICTURE']['SRC'];

if (empty($sPicture) && !empty($arResult['PREVIEW_PICTURE']))
    $sPicture = $arResult['PREVIEW_PICTURE']['SRC'];

if (!empty($sPicture))
    $APPLICATION->SetPageProperty('og:image', Core::$app->request->getHostInfo().$sPicture);