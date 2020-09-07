<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$modules = [
    'intec.startshop',
    'intec.constructorlite'
];

$included = [];

foreach ($modules as $module)
    if (Loader::includeModule($module))
    $included[] = $module;

?>
<form action="<?= $APPLICATION->GetCurPage() ?>" method="POST">
    <?=bitrix_sessid_post(); ?>
    <input type="hidden" name="lang" value="<?= LANGUAGE_ID ?>">
    <input type="hidden" name="id" value="<?= $_GET['id'] ?>">
    <input type="hidden" name="uninstall" value="Y">
    <input type="hidden" name="go" value="Y">
    <?php if (!empty($included)) { ?>
        <h3><?= Loc::getMessage('intec.universe.installer.uninstall.modules.title') ?></h3>
        <div><?= Loc::getMessage('intec.universe.installer.uninstall.modules.description') ?></div>
        <?php $number = 0 ?>
        <?php foreach ($included as $module) { ?>
            <?php $number++ ?>
            <p>
                <input type="hidden" name="modules[<?= $module ?>]" value="N">
                <input class="adm-checkbox adm-designed-checkbox" type="checkbox" id="modules-<?= $number ?>" name="modules[<?= $module ?>]" value="Y">
                <label for="modules-<?= $number ?>" class="adm-checkbox adm-designed-checkbox-label"></label>
                <label for="modules-<?= $number ?>" class="adm-checkbox-label"><?= $module ?></label>
            </p>
        <?php } ?>
    <?php } ?>
    <div>
        <div style="margin-bottom: 10px;"><?= Loc::getMessage('intec.universe.installer.uninstall.warning') ?></div>
        <input type="submit" value="<?= Loc::getMessage('intec.universe.installer.uninstall.go')?>">
    </div>
<form>