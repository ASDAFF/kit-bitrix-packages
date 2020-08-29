<?php
define("STOP_STATISTICS", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$moduleIncluded = false;

use Bitrix\Main,
    \Bitrix\Main\Loader,
    Bitrix\Sale,
    Bitrix\Sale\Basket,
    Kit\Origami\Helper\Config;
;

Loader::includeModule('iblock');
Loader::includeModule('sale');
Loader::includeModule('catalog');


try {
    $moduleIncluded = \Bitrix\Main\Loader::includeModule('kit.origami');
} catch (\Bitrix\Main\LoaderException $e) {
}
$params = json_decode($params, true);

$action = $params['action'];
$productID = $params['id'];


$wish = new \Kit\Origami\Sale\Basket\Wish();
if (!$params['props']) {
    $params['props'] = [];
}
$wish->setId($params['id']);
if ($params['props']) {
    $props = unserialize(base64_decode($params['props']));
}

if (!is_array($props)) {
    $props = [];
}
$wish->setProps($props);
if($params["qnt"] > 0)
    $wish->setQnt($params["qnt"]);
//$wish->setPrice($params['price']);
if ($params['action'] == 'add') {
    $result = $wish->add();
} else {
    $result = $wish->remove();
}