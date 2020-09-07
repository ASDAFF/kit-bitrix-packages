<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arParams
 * @var string $sGrid
 * @var array $arStyleText
 * @var array $arStyleIconBg
 * @var string $sView
 */

foreach ($arResult['ITEMS'] as $arItem) {

    $sId = $sTemplateId.'_'.$sType.'_default_'.$arItem['ID'];
    $sAreaId = $this->GetEditAreaId($sId);
    $this->AddEditAction($sId, $arItem['EDIT_LINK']);
    $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

    $bTemplateCentered = $sView == 'center';
    $sTemplateCenteredClass = $bTemplateCentered ? ' icon-center' : null;

    $bUseLink = ArrayHelper::getValue($arItem, ['PROPERTIES', $arParams['PROPERTY_USE_LINK'], 'VALUE']) == 'Y';
    $sLink = ArrayHelper::getValue($arItem, ['PROPERTIES', $arParams['PROPERTY_LINK'], 'VALUE']);
    $sTag = $bUseLink ? 'a' : 'span';

    $arStyleMain = array(
        'class' => 'icons-default-wrapper',
        'id' => $sAreaId
    );
    $sIconImage = ArrayHelper::getValue($arItem, ['PREVIEW_PICTURE', 'RESIZE_SRC']);
    $arStyleIcon = array(
        'class' => 'icons-picture',
        'href' => $bUseLink ? $sLink : null,
        'style' => array(
            'background-image' => !empty($sIconImage) ? 'url('.$sIconImage.')' : null
        )
    );

    $arStyleIconBg['href'] = $bUseLink ? $sLink : null;

    $arStyleText['class'] = $bUseLink ? 'icons-name-link intec-cl-text-hover' : 'icons-name-text';
    $arStyleText['href'] = $bUseLink ? $sLink : null;

?>
    <div class="icons-default icons-<?= $sGrid ?>">
        <?= Html::beginTag('div', $arStyleMain) ?>
            <span class="icons-picture-wrapper<?= $sTemplateCenteredClass ?>">
                <?= Html::beginTag($sTag, $arStyleIconBg) ?><?= Html::endTag($sTag) ?>
                <?= Html::beginTag($sTag, $arStyleIcon) ?><?= Html::endTag($sTag) ?>
            </span>
            <?= Html::beginTag($sTag, $arStyleText) ?>
                <?= $arItem['NAME'] ?>
            <?= Html::endTag($sTag) ?>
        <?= Html::endTag('div') ?>
    </div>
<?php }