<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;

/**
 * @var array $arParams
 * @var array $arResult
 * @var string $sTemplateId
 * @var array $arVisual
 */

?>
<div class="catalog-element-sections catalog-element-sections-narrow" data-role="sections">
    <?php foreach ($arResult['SECTIONS'] as $sCode => $arSection) { ?>
        <?php if ($sCode === 'STORES') continue ?>
        <div id="<?= $sTemplateId.'-'.$arSection['CODE'] ?>" class="catalog-element-section" data-role="section" data-expanded="false">
            <div class="catalog-element-section-name intec-ui-markup-header">
                <div class="catalog-element-section-name-wrapper">
                    <span data-role="section.toggle">
                        <?= $arSection['NAME'] ?>
                    </span>
                    <div class="catalog-element-section-name-decoration" data-role="section.toggle"></div>
                </div>
            </div>
            <div class="catalog-element-section-content" data-role="section.content">
                <div class="catalog-element-section-content-wrapper">
                    <?php include(__DIR__.'/sections/'.$arSection['CODE'].'.php') ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
<?php

unset($sCode, $arSection);