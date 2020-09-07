<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arVisual
 */

$bSpecialButtonsShow = false;

foreach ($arVisual['BUTTONS'] as $arSpecialButton)
    if ($arSpecialButton['SHOW'])
        $bSpecialButtonsShow = true;

if (!$bSpecialButtonsShow)
    return;

?>
<div class="widget-special-button-container intec-content">
    <div class="intec-content-wrapper">
        <div class="widget-special-button-container-body">
            <?php if ($arVisual['BUTTONS']['BACK']['SHOW']) { ?>
                <a class="widget-special-button" href="<?= $arVisual['BUTTONS']['BACK']['LINK'] ?>">
                    <span class="widget-special-button-part">
                        <i class="widget-special-button-icon far fa-angle-left"></i>
                    </span>
                    <span class="widget-special-button-part">
                        <span class="widget-special-button-text">
                            <?= Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_BUTTONS_BACK_TEXT') ?>
                        </span>
                    </span>
                </a>
            <?php } ?>
        </div>
    </div>
</div>