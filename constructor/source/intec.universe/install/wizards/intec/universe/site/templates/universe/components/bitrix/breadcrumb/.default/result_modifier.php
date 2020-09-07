<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;

if (!Loader::includeModule('iblock'))
    return;

$sIBlockType = ArrayHelper::getValue($GLOBALS, 'BreadCrumbIBlockType');
$iIBlockId = ArrayHelper::getValue($GLOBALS, 'BreadCrumbIBlockId');

$oSections = new Arrays();

if (!empty($sIBlockType) && !empty($iIBlockId)) {
    $oSections = Arrays::fromDBResult(CIBlockSection::GetList([], [
        'ACTIVE' => 'Y',
        'IBLOCK_TYPE' => $sIBlockType,
        'IBLOCK_ID' => $iIBlockId
    ]), true);
}