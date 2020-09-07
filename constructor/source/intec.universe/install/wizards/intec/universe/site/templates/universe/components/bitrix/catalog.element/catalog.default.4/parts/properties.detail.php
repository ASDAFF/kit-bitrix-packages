<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arResult
 */

?>
<div class="catalog-element-section">
    <div class="catalog-element-section-name intec-template-part intec-template-part-title">
        <?= Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_TEMPLATE_PROPERTIES_DETAIL_NAME') ?>
    </div>
    <div class="catalog-element-section-content">
        <div class="intec-grid intec-grid-wrap">
            <?php foreach ($arResult['DISPLAY_PROPERTIES'] as $arProperty) { ?>
                <div class="catalog-element-properties-detail-item intec-grid-item-2 intec-grid-item-768-1">
                    <div class="catalog-element-properties-detail-item-wrapper intec-grid intec-grid-500-wrap">
                        <div class="intec-grid-item intec-grid-item-500-1">
                            <div class="catalog-element-properties-detail-name">
                                <?= $arProperty['NAME'] ?>
                            </div>
                        </div>
                        <div class="intec-grid-item intec-grid-item-500-1">
                            <div class="catalog-element-properties-detail-value">
                                <?= $arProperty['DISPLAY_VALUE'] ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php unset($arProperty) ?>
        </div>
    </div>
</div>