<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\JavaScript;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 * @var string $sTemplateId
 * @var array $arVisual
 */

?>
<div class="catalog-element-properties">
    <div class="intec-grid intec-grid-wrap intec-grid-i-h-10">
        <?php $iPropertyNumber = 1 ?>
        <?php foreach ($arResult['DISPLAY_PROPERTIES'] as $arProperty) { ?>
        <?php
            if ($iPropertyNumber > $arVisual['PROPERTIES']['PREVIEW']['COUNT'])
                break;

            if (!empty($arProperty['USER_TYPE']))
                continue;
        ?>
            <div class="intec-grid-item-2 intec-grid-item-400-1">
                <div class="catalog-element-property">
                    <div class="catalog-element-property-name">
                        <?= $arProperty['NAME'] ?>
                    </div>
                    <div class="catalog-element-property-value">
                        <?= !Type::isArray($arProperty['DISPLAY_VALUE']) ?
                            $arProperty['DISPLAY_VALUE'] :
                            implode(', ', $arProperty['DISPLAY_VALUE'])
                        ?>;
                    </div>
                </div>
            </div>
            <?php $iPropertyNumber++ ?>
        <?php } ?>
        <?php unset($arProperty) ?>
        <?php unset($iPropertyNumber) ?>
    </div>
    <?php if (!empty($arResult['SECTIONS']['PROPERTIES']) && count($arResult['DISPLAY_PROPERTIES']) > $arVisual['PROPERTIES']['PREVIEW']['COUNT']) { ?>
        <a class="catalog-element-properties-all" onclick="(function () {
            var id = <?= JavaScript::toObject('#'.$sTemplateId.'-'.'properties') ?>;
            var content = $(id);
            var tab = $('[href=\'' + id + '\']');

            tab.tab('show');

            $(document).scrollTo(content, 500);
        })()">
            <span class="catalog-element-properties-all-text">
                <?= Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_2_PROPERTIES_ALL') ?>
            </span>
            <span class="catalog-element-properties-all-icon">
                <i class="far fa-chevron-right"></i>
            </span>
        </a>
    <?php } ?>
</div>