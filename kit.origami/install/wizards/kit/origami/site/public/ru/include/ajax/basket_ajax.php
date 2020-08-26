<?
define('STOP_STATISTICS', true);
define('NOT_CHECK_PERMISSIONS', true);
if (!isset($_POST['siteId']) || !is_string($_POST['siteId']))
    die();

if (!isset($_POST['templateName']) || !is_string($_POST['templateName']))
    die();

if ($_SERVER['REQUEST_METHOD'] != 'POST' ||
    preg_match('/^[A-Za-z0-9_]{2}$/', $_POST['siteId']) !== 1 ||
    preg_match('/^[.A-Za-z0-9_-]+$/', $_POST['templateName']) !== 1)
    die;

define('SITE_ID', $_POST['siteId']);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main,
    \Bitrix\Main\Loader,
    Bitrix\Sale,
    Bitrix\Sale\Basket,
    Kit\Origami\Helper\Config;
    ;


use Bitrix\Sale\Internals\BasketPropertyTable;
global $APPLICATION;
global $USER;

Loader::includeModule('catalog');
Loader::includeModule('sale');

if (!check_bitrix_sessid())
    die;

$_POST['arParams']['AJAX'] = 'Y';

$templateName = htmlspecialcharsEx($_POST["templateName"]);
$arParams = isset($_POST["arParams"]) ? $_POST["arParams"] : array();


if(isset($_POST["refresh"]))
{
    $ID = htmlspecialcharsEx($_POST["ID"]);
    if(isset($_POST["ID"]) && isset($_POST["count"]))
    {
        $count = htmlspecialcharsEx($_POST["count"]);

        $PRODUCT_ID = htmlspecialcharsEx($_POST["productID"]);


        $ratio = \Bitrix\Catalog\MeasureRatioTable::getList(
            array(
                'select' => array('RATIO', 'PRODUCT_ID'),
                'filter' => array('PRODUCT_ID' => $PRODUCT_ID)
            )
        )->Fetch();
        $val = $ratio["RATIO"];
        if($count<=$val)
            $count = $val;
        else{
            $ost = fmod($count, $val);
            if($ost != 0)
            {
                $count = $count - $ost;
            }
        }

        $arFields = array(
            "QUANTITY" => $count
        );
    }elseif(isset($_POST["ID"]) && isset($_POST["delay"]))
    {
        $arFields = array(
            "DELAY" => "Y",
        );
    }elseif(isset($_POST["ID"]) && isset($_POST["buy"]))
    {
        $arFields = array(
            "DELAY" => "N",
        );
    }
    $resultBasket = \Bitrix\Sale\Internals\BasketTable::Update($ID, $arFields);
}elseif(isset($_POST["deleteList"]))
{
    if($_POST["tab"]=="buy")
    {
        $filter = array(
            'FUSER_ID' => \Bitrix\Sale\Fuser::getId(),
            'LID' => SITE_ID,
            'ORDER_ID' => false,
            'DELAY' => 'N'
        );
    }else{
        $filter = array(
            'FUSER_ID' => \Bitrix\Sale\Fuser::getId(),
            'LID' => SITE_ID,
            'ORDER_ID' => false,
            'DELAY' => 'Y',
        );
    }

    $dbBasket = \Bitrix\Sale\Internals\BasketTable::getList(array('filter' => $filter, 'select' => array('ID')));
    while($arBasket = $dbBasket->Fetch())
    {
        \Bitrix\Sale\Internals\BasketTable::Delete($arBasket["ID"]);
    }
}
?>

<?
$arParams["TAB_ACTIVE"] = ($_POST["tab"]=="delay") ? "DELAY" : "BUY";
$APPLICATION->RestartBuffer();
header('Content-Type: text/html; charset='.LANG_CHARSET);
$APPLICATION->IncludeComponent('bitrix:sale.basket.basket.line', $templateName, $arParams);
?>