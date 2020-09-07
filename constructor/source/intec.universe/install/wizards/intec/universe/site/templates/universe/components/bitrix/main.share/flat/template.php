<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die() ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Type;

/**
 * @var array $arResult
 */

$this->setFrameMode(true);

if (!Loader::includeModule('intec.core'))
    return;

if (!Type::isArray($arResult['BOOKMARKS']) || empty($arResult['BOOKMARKS']))
    return;

?>
<div class="ns-bitrix c-main-share c-main-share-flat">
    <?php if ($arResult["PAGE_URL"]) { ?>
        <ul class="main-share-items intec-ui-mod-simple">
            <?php foreach ($arResult['BOOKMARKS'] as $sKey => $arBookmark) { ?>
                <li class="main-share-item">
                    <?= $arBookmark['ICON'] ?>
                </li>
            <?php } ?>
        </ul>
    <?php } else { ?>
        <?= Loc::getMessage('C_MAIN_SHARE_ERRORS_EMPTY_SERVER') ?>
    <?php } ?>
</div>
