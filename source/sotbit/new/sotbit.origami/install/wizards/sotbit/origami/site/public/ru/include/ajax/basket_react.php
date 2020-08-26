<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
global $APPLICATION;
global $USER;

$jsonAnswer = json_decode(file_get_contents("php://input"));
foreach ($jsonAnswer as $key => $value)
    $_POST[$key] = $value;

if (isset($_POST['DELETE_ALL'])) {
    if (!CModule::IncludeModule("sale")) return;
    $res = CSaleBasket::GetList(array(), array(
        'FUSER_ID' => CSaleBasket::GetBasketUserID(),
        'LID' => SITE_ID,
        'ORDER_ID' => 'null',
        'DELAY' => 'N'));

    while ($row = $res->fetch()) {
        CSaleBasket::Delete($row['ID']);
    }
}

if (isset($_POST['DELETE_ALL_DELAY'])) {
    if (!CModule::IncludeModule("sale")) return;
    $res = CSaleBasket::GetList(array(), array(
        'FUSER_ID' => CSaleBasket::GetBasketUserID(),
        'LID' => SITE_ID,
        'ORDER_ID' => 'null',
        'DELAY' => 'Y'));

    while ($row = $res->fetch()) {
        CSaleBasket::Delete($row['ID']);
    }
}

if(isset($_POST['IS_REMOVED'])) {
    if (!CModule::IncludeModule("sale")) return;
    if ($_POST['IS_REMOVED']) {
        CSaleBasket::Delete(
            CSaleBasket::GetList(array(), array('PRODUCT_ID' => $_POST['PRODUCT_ID']))->Fetch()['ID']
        );
    }
    else {
        Add2BasketByProductID($_POST['PRODUCT_ID'], $_POST['QUANTITY']);

    }
}

if (isset($_POST['ACTION']))
    if (!CModule::IncludeModule("sale")) return;
    switch ($_POST['ACTION']) {
        case 'count':
            CSaleBasket::Update(
                $_POST['ID'],
                array(
                    'QUANTITY' => $_POST['QUANTITY']
                )
            );
            break;
        case 'toBasket':
        case 'toFavorite':
            CSaleBasket::Update(
                $_POST['ID'],
                array(
                    'DELAY' => $_POST['DELAY']
                )
            );
            break;
    }
//die();


$_POST['arParams']['AJAX'] = 'Y';

//$arParams["TAB_ACTIVE"] = "BUY";
$arParams = array("SHOW_PRODUCTS" => "Y");

$APPLICATION->RestartBuffer();
header('Content-Type: text/html; charset='.LANG_CHARSET);
$json = $APPLICATION->IncludeComponent('bitrix:sale.basket.basket.line', 'origami_react', $arParams);
echo $json;

?>
