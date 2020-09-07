<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

?>
<form action="<?= $APPLICATION->GetCurPage() ?>" method="POST">
    <?=bitrix_sessid_post(); ?>
    <input type="hidden" name="lang" value="<?= LANGUAGE_ID ?>">
    <input type="hidden" name="id" value="<?= $_GET['id'] ?>">
    <input type="hidden" name="uninstall" value="Y">
    <input type="hidden" name="go" value="Y">
    <h3><?= Loc::getMessage('intec.constructor.install.uninstall.database.title') ?></h3>
    <div><?= Loc::getMessage('intec.constructor.install.uninstall.database.description') ?></div>
    <p>
        <input type="hidden" name="database[tables]" value="N">
        <input class="adm-checkbox adm-designed-checkbox" checked="checked" type="checkbox" id="remove-database" name="remove[database]" value="Y">
        <label for="remove-database" class="adm-checkbox adm-designed-checkbox-label"></label>
        <label for="remove-database" class="adm-checkbox-label">
            <?= Loc::getMessage('intec.constructor.install.uninstall.database.remove') ?>
        </label>
    </p>
    <div>
        <input type="submit" value="<?= Loc::getMessage('intec.constructor.install.uninstall.go')?>">
    </div>
<form>