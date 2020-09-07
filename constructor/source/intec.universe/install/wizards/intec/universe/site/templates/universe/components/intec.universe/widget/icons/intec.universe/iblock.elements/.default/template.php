<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;

/**
 * @var array $arResult
 * @var array $arParams
 * @var CBitrixComponentTemplate $this
 */

$this->setFrameMode(true);

$bTargetLink = ArrayHelper::getValue($arParams, 'TARGET_BLANK') == 'Y';
$bShowHeader = ArrayHelper::getValue($arParams, 'SHOW_HEADER') == 'Y';
$sHeader = $bShowHeader ? ArrayHelper::getValue($arParams, 'HEADER') : null;
$sHeaderPosition = $bShowHeader ? ArrayHelper::getValue($arParams, 'HEADER_POSITION') : null;
$sView = ArrayHelper::getValue($arParams, 'VIEW');
$sGrid = ArrayHelper::getValue($arParams, 'LINE_ELEMENTS_COUNT');

$sHeaderFontSize = ArrayHelper::getValue($arParams, 'FONT_SIZE_HEADER');
$bHeaderDrawItalic = ArrayHelper::getValue($arParams, 'FONT_STYLE_HEADER_ITALIC') == 'Y';
$bHeaderDrawBold = ArrayHelper::getValue($arParams, 'FONT_STYLE_HEADER_BOLD') == 'Y';
$bHeaderDrawUnderline = ArrayHelper::getValue($arParams, 'FONT_STYLE_HEADER_UNDERLINE') == 'Y';
$sHeaderTextPosition = ArrayHelper::getValue($arParams, 'HEADER_TEXT_POSITION');
$sHeaderTextColor = ArrayHelper::getValue($arParams, 'HEADER_TEXT_COLOR');
$arStyleText = array(
    'target' => $bTargetLink ? '_blank' : null,
    'style' => array(
        'font-size' => !empty($sHeaderFontSize) ? $sHeaderFontSize.'px' : 'inherit',
        'font-style' => $bHeaderDrawItalic ? 'italic' : 'normal',
        'font-weight' => $bHeaderDrawBold ? 'bold' : 'normal',
        'text-decoration' => $bHeaderDrawUnderline ? 'underline' : 'none',
        'text-align' => $sHeaderTextPosition,
        'color' => !empty($sHeaderTextColor) ? $sHeaderTextColor : 'inherit'
    )
);

$sIconBgColor = ArrayHelper::getValue($arParams, 'BACKGROUND_COLOR_ICON');
$sIconOpacity = ArrayHelper::getValue($arParams, 'BACKGROUND_OPACITY_ICON');
$sIconBorderRadius = ArrayHelper::getValue($arParams, 'BACKGROUND_BORDER_RADIUS');
$arStyleIconBg = array(
    'target' => $bTargetLink ? '_blank' : null,
    'class' => 'icon-picture-bg',
    'style' => array(
        'background-color' => !empty($sIconBgColor) ? $sIconBgColor : 'transparent',
        'opacity' => !empty($sIconOpacity) ? $sIconOpacity / 100 : '1',
        'border-radius' => !empty($sIconBorderRadius) ? $sIconBorderRadius.'px' : '0'
    )
);

?>
<div class="intec-content widget-icons">
    <div class="intec-content-wrapper">
        <?php if ($bShowHeader && !empty($sHeader)) { ?>
            <div class="widget-icons-header" style="text-align: <?= $sHeaderPosition ?>">
                <?= $sHeader ?>
            </div>
        <?php }
        if (!empty($arResult['ITEMS'])) { ?>
            <div class="widget-icons-content">
                <?php if (!empty($sView)) {
                    if ($sView == 'default' || $sView == 'center') {
                        require('parts/default.php');
                    } else if ($sView == 'left-float' || $sView == 'with-description') {
                        require('parts/left-float.php');
                    }
                } ?>
                <div class="clear"></div>
            </div>
        <?php } else { ?>
            <div class="no-elements">
                <?= GetMessage('ICONS_NO_ITEMS') ?>
            </div>
        <?php } ?>
    </div>
</div>