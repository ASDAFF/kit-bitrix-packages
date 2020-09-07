<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

?>
<style type="text/css">
    .install-ok{
        display: inline-block;
        border: 1px solid #a4b9cc;
        padding: 10px 13px;
        margin-bottom: 10px;
        border-radius: 2px;
        -moz-border-radius: 2px;
        -webkit-border-radius: 2px;
        -o-border-radius: 2px;
    }
</style>
<span class="install-ok"><?= Loc::getMessage('intec.universe.installer.install.message') ?></span>
<form action="/bitrix/admin/wizard_list.php" method="GET">
    <input type="hidden" name="lang" value="<?= LANGUAGE_ID ?>">
	<input type="submit" name="" value="<?= Loc::getMessage('intec.universe.installer.install.wizards') ?>">
<form>
