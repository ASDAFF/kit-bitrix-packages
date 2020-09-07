<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
?>
<? CAdminMessage::ShowNote(Loc::getMessage('intec.universe.installer.uninstall.message')); ?>
<form action="<?= $APPLICATION->GetCurPage() ?>" method="GET">
	<input type="hidden" name="lang" value="<?= LANGUAGE_ID ?>">
	<input type="submit" value="<?= Loc::getMessage('intec.universe.installer.uninstall.back')?>">
<form>
