<?php
define('STOP_STATISTICS', true);
define('NOT_CHECK_PERMISSIONS', true);
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
echo json_encode($APPLICATION->CaptchaGetCode());
?>