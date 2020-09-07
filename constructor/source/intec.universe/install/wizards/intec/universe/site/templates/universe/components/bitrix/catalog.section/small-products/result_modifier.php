<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

$sPriceFrom = Loc::getMessage('RP_PRICE_FROM');

if (Loader::includeModule('catalog')) {
    include(__DIR__.'/modifiers/base.php');
} else if (Loader::includeModule('intec.startshop')) {
    include(__DIR__.'/modifiers/lite.php');
}