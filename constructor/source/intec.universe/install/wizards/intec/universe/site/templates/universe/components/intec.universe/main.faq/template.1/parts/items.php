<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

/**
 * @var array $arParams
 * @var array $arResult
 * @var string $sTemplateId
 * @var array $arBlocks
 * @var array $arVisual
 */

if (empty($arItems))
    return;

?>
<div class="widget-items" data-role="items">
    <?php foreach ($arItems as $arItem) {

    $sId = $sTemplateId.'_'.$arItem['ID'];
    $sAreaId = $this->GetEditAreaId($sId);
    $this->AddEditAction($sId, $arItem['EDIT_LINK']);
    $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

    ?>
        <div id="<?= $sAreaId ?>" class="widget-item" data-role="item" data-expanded="false" data-alignment="<?= $arVisual['TEXT']['ALIGNMENT'] ?>">
            <div class="widget-item-question" data-role="item.title">
                <div class="widget-item-question-wrapper">
                    <span class="widget-item-question-text">
                        <?= $arItem['NAME'] ?>
                    </span>
                    <span class="widget-item-question-icon">
                        <i class="far fa-chevron-down"></i>
                    </span>
                </div>
            </div>
            <div class="widget-item-answer" data-role="item.content">
                <div class="widget-item-answer-wrapper">
                    <?php if (!empty($arItem['PREVIEW_TEXT'])) { ?>
                        <?= $arItem['PREVIEW_TEXT'] ?>
                    <?php } else { ?>
                        <?= $arItem['DETAIL_TEXT'] ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>