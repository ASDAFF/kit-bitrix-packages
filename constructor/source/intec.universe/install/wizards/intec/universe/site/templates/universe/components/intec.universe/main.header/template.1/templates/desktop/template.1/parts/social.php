<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\bitrix\component\InnerTemplate;
use intec\core\helpers\StringHelper;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arData
 * @var InnerTemplate $this
 */

?>
<div class="widget-panel-social-wrap intec-grid-item-auto">
    <!--noindex-->
    <div class="widget-panel-social">
        <div class="widget-panel-social-items intec-grid intec-grid-nowrap intec-grid-i-h-6 intec-grid-a-v-center">
            <?php foreach ($arResult['SOCIAL']['ITEMS'] as $sKey => $arSocial) { ?>
                <?php if (!$arSocial['SHOW']) continue ?>
                <div class="widget-panel-social-item-wrap intec-grid-item-auto">
                    <a rel="nofollow" target="_blank" href="<?= $arSocial['VALUE'] ?>" class="widget-panel-social-item intec-cl-text-hover">
                        <i class="glyph-icon-<?= StringHelper::toLowerCase($sKey) ?>"></i>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>
    <!--/noindex-->
</div>
