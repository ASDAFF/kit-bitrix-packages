<?php

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

?>
<div class="adm-info-message-wrap adm-info-message-red">
    <div class="adm-info-message">
        <div class="adm-info-message-title">
            <?= Loc::getMessage('intec.core.installer.requires.error') ?>.<br />
        </div>
        <div class="adm-info-message-icon"></div>
    </div>
    <div style="font-size: 14px; color: #2d2d2d;">
        <?= Loc::getMessage('intec.core.installer.requires.extensions') ?>
        <ul>
            <?php if (!extension_loaded('pdo')) { ?>
                <li><?= Loc::getMessage('intec.core.installer.requires.extensions.pdo') ?></li>
            <?php } ?>
            <?php if (!extension_loaded('pdo_mysql')) { ?>
                <li><?= Loc::getMessage('intec.core.installer.requires.extensions.pdoMysql') ?></li>
            <?php } ?>
        </ul>
    </div>
</div>