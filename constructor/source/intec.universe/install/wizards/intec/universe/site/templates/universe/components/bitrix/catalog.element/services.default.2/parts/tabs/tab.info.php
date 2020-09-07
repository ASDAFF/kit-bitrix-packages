<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arTab
 */

?>
<div class="catalog-element-block-information">
    <?php foreach ($arTab['VALUE'] as $arProperty) { ?>
        <div class="catalog-element-block-information-item">
            <div class="intec-content">
                <div class="intec-content-wrapper">
                    <div class="catalog-element-title" data-align="center">
                        <?= $arProperty['NAME'] ?>
                    </div>
                    <div class="catalog-element-block-information-item-value">
                        <?= $arProperty['VALUE'] ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
<?php unset($arProperty) ?>