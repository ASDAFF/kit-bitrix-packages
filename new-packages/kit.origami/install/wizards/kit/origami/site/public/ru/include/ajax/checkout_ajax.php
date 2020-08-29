<?
define('STOP_STATISTICS', true);
define('NOT_CHECK_PERMISSIONS', true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main,
    \Bitrix\Main\Loader,
    Bitrix\Sale,
    Bitrix\Sale\Basket,
    Sotbit\Origami\Helper\Config;
;
global $APPLICATION;
global $USER;


