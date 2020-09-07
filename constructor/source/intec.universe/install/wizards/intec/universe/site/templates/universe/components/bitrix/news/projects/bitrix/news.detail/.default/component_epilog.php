<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use intec\Core;

if (!empty($arResult['DETAIL_PICTURE']))
    $sPicture = $arResult['DETAIL_PICTURE']['SRC'];

if (empty($sPicture) && !empty($arResult['PREVIEW_PICTURE']))
    $sPicture = $arResult['PREVIEW_PICTURE']['SRC'];

if (!empty($sPicture))
    $APPLICATION->SetPageProperty('og:image', Core::$app->request->getHostInfo().$sPicture);