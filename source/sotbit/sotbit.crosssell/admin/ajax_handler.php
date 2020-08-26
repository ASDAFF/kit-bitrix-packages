<?
use Bitrix\Main\Loader;
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

Loader::includeModule('sotbit.crosssell');
$crossell = new \SotbitCrosssell();

if($_POST['gen'] == 'all') {
    $crossell->generateCondition();
}

if(isset($_POST['id'])) {
    $result = $crossell->generateCondition(intval($_POST['id']));
    echo $result;
}


?>