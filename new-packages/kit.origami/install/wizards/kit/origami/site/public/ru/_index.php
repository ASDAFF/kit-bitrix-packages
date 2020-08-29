<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Интернет-магазин \"Оригами\"");

$APPLICATION->IncludeComponent('sotbit:block.include','',['PART' => 'main_'
    .SITE_ID],null,['HIDE_ICONS' => 'Y']);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>