<?php
define("STOP_STATISTICS", true);
define("NOT_CHECK_PERMISSIONS", true);

use Bitrix\Catalog\SubscribeTable;
use \Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $USER;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!Loader::includeModule('catalog')) {
        echo Bitrix\Main\Web\Json::encode([
            'error'   => true,
            'message' => 'not module catalog'
        ]);
        require_once($_SERVER['DOCUMENT_ROOT'].BX_ROOT.'/modules/main/include/epilog_after.php');
        die();
    }

    $userId = false;
    if($USER && is_object($USER) && $USER->isAuthorized())
    {
        $userId = $USER->getId();
    }

    if(ToLower(LANG_CHARSET) == 'windows-1251')
    {
        foreach($_POST as $key => &$val)
        {
            if(!is_array($val))
                $val = iconv('utf-8', 'windows-1251', $val);
        }
    }

    if($userId){
        $rs = SubscribeTable::getList(
            ['filter' => [
                'USER_ID' => $userId,
                'ITEM_ID' => $_POST['itemId']
            ]]
        );
        if($row = $rs->fetch()){
            SubscribeTable::delete($row['ID']);
            unset($_SESSION['SUBSCRIBE_PRODUCT']['LIST_PRODUCT_ID'][$_POST['itemId']]);
            echo Bitrix\Main\Web\Json::encode(
                array('success' => true, 'message' => $_POST['messOk']));
            require_once($_SERVER['DOCUMENT_ROOT'].BX_ROOT.'/modules/main/include/epilog_after.php');
            die();
        }
    }
}
echo Bitrix\Main\Web\Json::encode([
    'error'   => true,
    'message' => 'unknown error'
]);
require_once($_SERVER['DOCUMENT_ROOT'].BX_ROOT.'/modules/main/include/epilog_after.php');
die();
?>
