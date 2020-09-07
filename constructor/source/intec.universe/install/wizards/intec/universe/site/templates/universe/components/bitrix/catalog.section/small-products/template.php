<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arParams
 */

$this->setFrameMode(true);

$componentHash = Html::getUniqueId(null, Component::getUniqueId($this));

if (!empty($arParams['TITLE'])) { ?>
    <div class="item-sub-title">
        <?= $arParams['TITLE'] ?>
    </div>
<?php } ?>
<div class="intec-content">
    <div class="intec-content-wrapper">
        <div class="binded-products" id="<?= $componentHash ?>">
            <div class="binded-products-content owl-carousel">
                <?php foreach ($arResult['ITEMS'] as $arItem) {

                    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

                    $sName = ArrayHelper::getValue($arItem, 'NAME');
                    $sDetailPageUrl = ArrayHelper::getValue($arItem, 'DETAIL_PAGE_URL');
                    $sPreviewPicture = ArrayHelper::getValue($arItem, ['PREVIEW_PICTURE', 'SRC']);
                    $sPrice = ArrayHelper::getValue($arItem, ['MIN_PRICE', 'PRINT_DISCOUNT_VALUE']);

                    /** Picture attributes and css-properties */
                    $style = [
                        'background-image' => 'url('.$sPreviewPicture.')'
                    ];

                    $attributes = [
                        'class' => 'element-picture',
                        'href' => $sDetailPageUrl,
                        'style' => $style
                    ];

                ?>
                    <div class="binded-element">
                        <div class="binded-element-wrapper" id="<?= $this->GetEditAreaId($arItem['ID']) ?>">
                            <?= Html::tag('a', '', $attributes) ?>
                            <div class="element-text">
                                <a class="text-name intec-cl-text-hover" href="<?= $sDetailPageUrl ?>">
                                    <?= $sName ?>
                                </a>
                                <div class="text-price">
                                    <?= $sPrice ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php include('script.php'); ?>
    </div>
</div>
