<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @var array $arParams
 * @var array $arResult
 * @global CMain $APPLICATION
 * @var CBitrixComponentTemplate $this
 * @var CBitrixComponent $component
 */

$this->setFrameMode(true);

$arViewParams = ArrayHelper::getValue($arResult, 'VIEW_PARAMETERS');

$arVisual = $arResult['VISUAL'];
?>
<div class="photo-sections photo-sections-default">
    <div class="photo-sections-items intec-grid intec-grid-wrap intec-grid-a-v-start intec-grid-a-h-start intec-grid-i-10">
    <?php foreach($arResult["SECTIONS"] as $arSection) {

        $this->AddEditAction('section_'.$arSection['ID'], $arSection['ADD_ELEMENT_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "ELEMENT_ADD"), array('ICON' => 'bx-context-toolbar-create-icon'));
        $this->AddEditAction('section_'.$arSection['ID'], $arSection['EDIT_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_EDIT"));
        $this->AddDeleteAction('section_'.$arSection['ID'], $arSection['DELETE_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BPS_SECTION_DELETE_CONFIRM')));

        $sSectionUrl = ArrayHelper::getValue($arSection, 'SECTION_PAGE_URL');
        $sName = ArrayHelper::getValue($arSection, 'NAME');
        $sPicture = ArrayHelper::getValue($arSection, 'PICTURE');
        $sItemsCount = ArrayHelper::getValue($arSection, 'ITEMS_COUNT');

    ?>
        <div class="intec-grid-item-<?= $arViewParams['LINE_ELEMENT_COUNT'];?> intec-grid-item-1000-3 intec-grid-item-768-2 intec-grid-item-550-1">
            <div class="section-element-wrapper" id="<?=$this->GetEditAreaId('section_'.$arSection['ID']);?>">
                <?= Html::beginTag('div', [
                    'class' => 'section-element intec-cl-background-hover',
                    'data' => [
                        'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                        'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                    ],
                    'style' => [
                        'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null
                    ]
                ]) ?>
                    <a href="<?= $sSectionUrl ?>" class="section-element-fade"></a>
                        <a href="<?= $sSectionUrl ?>" class="section-element-name">
                            <?= $sName ?>
                        </a>
                        <?php if (!empty($arSection['ITEMS'])) { ?>
                            <span class="section-element-count">
                                <i class="glyph-icon-landscape"></i>
                                <?= $sItemsCount ?>
                            </span>
                        <?php } ?>
                <?= Html::endTag('div') ?>
            </div>
        </div>
    <?php } ?>
</div>
</div>