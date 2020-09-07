<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$dependencies = [
    'intec.core'
];

?>
<style type="text/css">
    .modules {
        display: block;
        margin-bottom: 20px;
    }

    .modules .module {
        display: block;
    }
</style>
<h3 class="message">
    <?= Loc::getMessage('intec.intec.installer.modules.message') ?>
</h3>
<div class="modules">
    <?php foreach ($dependencies as $dependency) { ?>
        <?php if (Loader::includeModule($dependency)) continue; ?>
        <div class="module">
            <a href="http://marketplace.1c-bitrix.ru/solutions/<?= $dependency ?>/" target="_blank" class="module-wrapper">
                <?= $dependency ?>
            </a>
        </div>
    <?php } ?>
</div>
<form action="<?= $APPLICATION->GetCurPage() ?>" method="GET">
    <input type="hidden" name="lang" value="<?= LANGUAGE_ID ?>">
    <input type="submit" value="<?= Loc::getMessage('intec.intec.installer.modules.back') ?>">
<form>
