<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
use Kit\Origami\Helper\Config;
$APPLICATION->SetTitle("Контакты");
include $_SERVER['DOCUMENT_ROOT'].'/'.\KitOrigami::contactsDir.'/'
    .Config::get('CONTACTS').'/content.php';
?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php")?>