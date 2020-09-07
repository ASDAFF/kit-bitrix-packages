<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;
use intec\core\helpers\ArrayHelper;

/**
 * @var array $arResult
 * @var array $arParams
 * @var string $sGrid
 * @var array $arStyleIconBg
 * @var array $arStyleText
 * @var string $sView
 * @var boolean $bTargetLink
 */

$bTemplateDescription = $sView == 'with-description';

$arStyleDescription = array();

if ($bTemplateDescription) {
    $sDescriptionFontSize = ArrayHelper::getValue($arParams, 'FONT_SIZE_DESCRIPTION');
    $bDescriptionDrawBold = ArrayHelper::getValue($arParams, 'FONT_STYLE_DESCRIPTION_BOLD') == 'Y';
    $bDescriptionDrawItalic = ArrayHelper::getValue($arParams, 'FONT_STYLE_DESCRIPTION_ITALIC') == 'Y';
    $bDescriptionDrawUnderline = ArrayHelper::getValue($arParams, 'FONT_STYLE_DESCRIPTION_UNDERLINE') == 'Y';
    $sDescriptionTextPosition = ArrayHelper::getValue($arParams, 'DESCRIPTION_TEXT_POSITION');
    $sDescriptionTextColor = ArrayHelper::getValue($arParams, 'DESCRIPTION_TEXT_COLOR');
    $arStyleDescription = array(
        'class' => 'icons-description-text',
        'style' => array(
            'font-size' => !empty($sDescriptionFontSize) ? $sDescriptionFontSize.'px' : 'inherit',
            'font-style' => $bDescriptionDrawItalic ? 'italic' : 'normal',
            'font-weight' => $bDescriptionDrawBold ? 'bold' : 'normal',
            'text-decoration' => $bDescriptionDrawUnderline ? 'underline' : 'none',
            'text-align' => $sDescriptionTextPosition,
            'color' => !empty($sDescriptionTextColor) ? $sDescriptionTextColor : 'inherit'
        )
    );
}

foreach ($arResult['ITEMS'] as $arItem) {

    $sId = $sTemplateId.'_'.$sType.'_default_'.$arItem['ID'];
    $sAreaId = $this->GetEditAreaId($sId);
    $this->AddEditAction($sId, $arItem['EDIT_LINK']);
    $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

    $bUseLink = ArrayHelper::getValue($arItem, ['PROPERTIES', $arParams['PROPERTY_USE_LINK'], 'VALUE']) == 'Y';
    $sLink = ArrayHelper::getValue($arItem, ['PROPERTIES', $arParams['PROPERTY_LINK'], 'VALUE']);
    $sTag = $bUseLink ? 'a' : 'span';

    $arStyleMain = array(
        'class' => 'icons-left-float-wrapper clearfix',
        'id' => $sAreaId
    );
    $sIconImage = ArrayHelper::getValue($arItem, ['PREVIEW_PICTURE', 'RESIZE_SRC']);
    $arStyleIcon = array(
        'class' => 'icons-picture',
        'href' => $bUseLink ? $sLink : null,
        'target' => $bTargetLink ? '_blank' : null,
        'style' => array(
            'background-image' => !empty($sIconImage) ? 'url('.$sIconImage.')' : null
        )
    );

    $arStyleIconBg['href'] = $bUseLink ? $sLink : null;

    $arStyleText['class'] = $bUseLink ? 'icons-name-link intec-cl-text-hover' : 'icons-name-text';
    $arStyleText['href'] = $bUseLink ? $sLink : null;

    ?>
    <div class="icons-left-float icons-<?= $sGrid ?>">
        <?= Html::beginTag('div', $arStyleMain)?>
            <span class="icons-picture-wrapper">
                <?= Html::beginTag($sTag, $arStyleIconBg) ?><?= Html::endTag($sTag) ?>
                <?= Html::beginTag($sTag, $arStyleIcon) ?><?= Html::endTag($sTag) ?>
            </span>
            <span class="icons-name-wrapper">
                <?php if (!$bTemplateDescription) { ?>
                    <?= Html::beginTag($sTag, $arStyleText) ?>
                        <?= $arItem['NAME'] ?>
                    <?= Html::endTag($sTag) ?>
                <?php } else { ?>
                    <span class="icons-with-description">
                        <?= Html::beginTag($sTag, $arStyleText) ?>
                            <?= $arItem['NAME'] ?>
                        <?= Html::endTag($sTag) ?>
                        <?= Html::beginTag('span', $arStyleDescription) ?>
                            <?= $arItem['PREVIEW_TEXT'] ?>
                        <?= Html::endTag('span') ?>
                    </span>
                <?php } ?>
            </span>
        <?= Html::endTag('div') ?>
    </div>
<?php }
