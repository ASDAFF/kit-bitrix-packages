<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

/**
 * @var array $arResult
 */

?>
<div class="subscribe-edit-notes">
    <?php if (!empty($arResult['MESSAGE'])) { ?>
        <?php foreach ($arResult['MESSAGE'] as $sText) { ?>
            <div class="subscribe-edit-note" data-type="message">
                <?= Html::stripTags($sText) ?>
            </div>
        <?php } ?>
    <?php } ?>
    <?php if (!empty($arResult['ERROR'])) { ?>
        <?php foreach ($arResult['ERROR'] as $sText) { ?>
            <div class="subscribe-edit-note" data-type="error">
                <?= Html::stripTags($sText) ?>
            </div>
        <?php } ?>
    <?php } ?>
</div>
<?php unset($sText) ?>