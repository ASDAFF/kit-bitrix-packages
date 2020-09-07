<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */

?>
<div class="intec-content">
    <div class="intec-content-wrapper startshop-payment default">
        <div class="startshop-aligner-vertical"></div>
        <?php if ($arResult['STATUS'] == 'SUCCESS') { ?>
            <div class="startshop-payment-message startshop-payment-message-success">
                <div class="startshop-payment-icon"></div>
                <div class="startshop-payment-text"><?= GetMessage('SP_DEFAULT_SUCCESS_TEXT', $arResult['ORDER']) ?></div>
            </div>
        <?php } else { ?>
            <div class="startshop-payment-message startshop-payment-message-fail">
                <div class="startshop-payment-icon"></div>
                <?php if (!empty($arResult['ORDER'])) { ?>
                    <div class="startshop-payment-text"><?= GetMessage('SP_DEFAULT_FAIL_TEXT_WITH_ORDER', $arResult['ORDER']) ?></div>
                <?php } else { ?>
                    <div class="startshop-payment-text"><?= GetMessage('SP_DEFAULT_FAIL_TEXT_WITHOUT_ORDER') ?></div>
                <?php } ?>
            </div>
        <?php } ?>
        <?php if ($arParams['URL_CATALOG']) { ?>
            <div class="startshop-payment-link-wrapper">
                <a href="<?= $arParams['URL_CATALOG'] ?>"
                   class="intec-button intec-button-cl-default intec-button-transparent intec-button-md"><?= GetMessage('SP_DEFAULT_CONTINUE_SHOPPING') ?></a>
            </div>
        <?php } ?>
    </div>
</div>