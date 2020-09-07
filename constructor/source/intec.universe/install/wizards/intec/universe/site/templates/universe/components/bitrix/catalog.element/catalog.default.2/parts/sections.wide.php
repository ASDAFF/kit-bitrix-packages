<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arVisual
 */

?>
<?php if (!empty($arResult['SECTIONS']['DESCRIPTION'])) { ?>
    <?php if ($arVisual['WIDE']) { ?>
            </div>
        </div>
    <?php } ?>
    <div class="catalog-element-sections catalog-element-sections-wide">
        <div class="<?= Html::cssClassFromArray([
            'catalog-element-section' => true,
            'catalog-element-section-dark' => $arVisual['WIDE'],
            'intec-content-wrap' => $arVisual['WIDE']
        ], true) ?>">
            <div class="catalog-element-section-wrapper">
                <div class="catalog-element-section-name intec-ui-markup-header">
                    <?= $arResult['SECTIONS']['DESCRIPTION']['NAME'] ?>
                </div>
                <div class="catalog-element-section-content">
                    <?php if ($arVisual['WIDE']) { ?>
                        <div class="intec-content">
                            <div class="intec-content-wrapper">
                    <?php } ?>
                    <?php include(__DIR__.'/sections/description.php') ?>
                    <?php if ($arVisual['WIDE']) { ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <?php if ($arVisual['WIDE']) { ?>
        <div class="catalog-element-wrapper intec-content intec-content-visible">
            <div class="catalog-element-wrapper-2 intec-content-wrapper">
    <?php } ?>
<?php } ?>
<div class="catalog-element-sections catalog-element-sections-wide" data-role="sections">
    <?php if (!empty($arResult['SECTIONS']['PROPERTIES'])) { ?>
        <div id="<?= $sTemplateId.'-'.'properties' ?>" class="catalog-element-section" data-role="section">
            <div class="catalog-element-section-name intec-ui-markup-header">
                <div class="catalog-element-section-name-wrapper">
                    <span data-role="section.toggle">
                        <?= $arResult['SECTIONS']['PROPERTIES']['NAME'] ?>
                    </span>
                    <div class="catalog-element-section-name-decoration" data-role="section.toggle"></div>
                </div>
            </div>
            <div class="catalog-element-section-content" data-role="section.content">
                <?php include(__DIR__.'/sections/properties.php') ?>
            </div>
        </div>
    <?php } ?>
    <?php if (!empty($arResult['SECTIONS']['STORES'])) { ?>
        <div class="catalog-element-section" data-role="section">
            <div class="catalog-element-section-name intec-ui-markup-header">
                <div class="catalog-element-section-name-wrapper">
                    <span data-role="section.toggle">
                        <?= $arResult['SECTIONS']['STORES']['NAME'] ?>
                    </span>
                    <div class="catalog-element-section-name-decoration" data-role="section.toggle"></div>
                </div>
            </div>
            <div class="catalog-element-section-content" data-role="section.content">
                <?php include(__DIR__.'/sections/stores.php') ?>
            </div>
        </div>
    <?php } ?>
    <?php if (!empty($arResult['SECTIONS']['DOCUMENTS'])) { ?>
        <div class="catalog-element-section" data-role="section">
            <div class="catalog-element-section-name intec-ui-markup-header">
                <div class="catalog-element-section-name-wrapper">
                    <span data-role="section.toggle">
                        <?= $arResult['SECTIONS']['DOCUMENTS']['NAME'] ?>
                    </span>
                    <div class="catalog-element-section-name-decoration" data-role="section.toggle"></div>
                </div>
            </div>
            <div class="catalog-element-section-content" data-role="section.content">
                <?php include(__DIR__.'/sections/documents.php') ?>
            </div>
        </div>
    <?php } ?>
    <?php if (!empty($arResult['SECTIONS']['VIDEO'])) { ?>
        <div class="catalog-element-section" data-role="section">
            <div class="catalog-element-section-name intec-ui-markup-header">
                <div class="catalog-element-section-name-wrapper">
                    <span data-role="section.toggle">
                        <?= $arResult['SECTIONS']['VIDEO']['NAME'] ?>
                    </span>
                    <div class="catalog-element-section-name-decoration" data-role="section.toggle"></div>
                </div>
            </div>
            <div class="catalog-element-section-content" data-role="section.content">
                <?php include(__DIR__.'/sections/video.php') ?>
            </div>
        </div>
    <?php } ?>
    <?php if (!empty($arResult['SECTIONS']['REVIEWS'])) { ?>
        <div class="catalog-element-section" data-role="section">
            <div class="catalog-element-section-name intec-ui-markup-header">
                <div class="catalog-element-section-name-wrapper">
                    <span data-role="section.toggle">
                        <?= $arResult['SECTIONS']['REVIEWS']['NAME'] ?>
                    </span>
                    <div class="catalog-element-section-name-decoration" data-role="section.toggle"></div>
                </div>
            </div>
            <div class="catalog-element-section-content" data-role="section.content">
                <?php include(__DIR__.'/sections/reviews.php') ?>
            </div>
        </div>
    <?php } ?>
</div>