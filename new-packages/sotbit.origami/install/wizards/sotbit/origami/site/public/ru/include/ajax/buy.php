<?php
define("STOP_STATISTICS", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
\Bitrix\Main\Loader::includeModule('sotbit.origami');
$moduleIncluded = false;
try {
    $moduleIncluded = \Bitrix\Main\Loader::includeModule('sotbit.origami');
} catch (\Bitrix\Main\LoaderException $e) {
}
$params = json_decode($params, true);

\Bitrix\Main\Loader::includeModule('catalog');

$Buy = new \Sotbit\Origami\Sale\Basket\Buy();
if (!$params['props']) {
    $params['props'] = [];
}
$Buy->setId($params['id']);
if ($params['props']) {
    $props = unserialize(base64_decode($params['props']));
}

if (!is_array($props)) {
    $props = [];
}
$Buy->setProps($props);
//$Buy->setPrice($params['price']);
if($params['qnt'] > 0)
    $Buy->setQnt($params['qnt']);
if ($params['action'] == 'add') {
    $result = $Buy->add();
} else {
    $result = $Buy->remove();
}
if (!$result) {
    echo json_encode(['STATUS' => 'ERROR','MESSAGE' => 'Error basket']);
}
else{
    echo json_encode(['STATUS' => 'OK']);
}