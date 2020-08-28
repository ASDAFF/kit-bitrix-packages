<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
use Sotbit\Origami\Helper\Config;
$APPLICATION->SetTitle("Контакты");
include $_SERVER['DOCUMENT_ROOT'].'/'.\SotbitOrigami::contactsDir.'/'
    .Config::get('CONTACTS').'/content.php';
?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php")?>