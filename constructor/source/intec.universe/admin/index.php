<?php require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php"); ?>
<?php

use Bitrix\Main\Localization\Loc;

global $APPLICATION;

Loc::loadMessages(__FILE__);

$APPLICATION->SetTitle(Loc::getMessage('title'));

?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php"); ?>
<div style="width: 100%; height: 720px; background: #fff;">
    <iframe src="//sp.intecweb.ru/solutions/intec.universe/index.php" style="width: 100%; height: 100%; border: none;"></iframe>
</div>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php"); ?>