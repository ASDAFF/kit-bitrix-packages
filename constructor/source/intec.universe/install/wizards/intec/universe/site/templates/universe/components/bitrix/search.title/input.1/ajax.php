<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

if (empty($arResult['CATEGORIES']))
    return;

?>
<div class="ns-bitrix c-search-title c-search-title-input-1 search-title-results">
    <table class="search-title-table">
        <?php foreach ($arResult['CATEGORIES'] as $sKey => $arCategory) { ?>
            <?php foreach ($arCategory['ITEMS'] as $arItem) { ?>
                <?php if ($sKey == 'all') { ?>
                    <tr class="search-title-row">
                        <td class="search-title-cell search-title-all">
                            <a class="search-title-link intec-cl-text-hover" href="<?= $arItem['URL'] ?>">
                                <?= $arItem['NAME'] ?>
                            </a>
                        </td>
                    </tr>
                <?php } else if (!empty($arItem['ITEM_ID'])) { ?>
                    <tr class="search-title-row">
                        <td class="search-title-cell search-title-item">
                            <a class="search-title-link intec-cl-text-hover" href="<?= $arItem['URL'] ?>">
                                <?= $arItem['NAME'] ?>
                            </a>
                        </td>
                    </tr>
                <?php } else { ?>
                    <tr class="search-title-row">
                        <td class="search-title-cell search-title-more">
                            <a class="search-title-link intec-cl-text-hover" href="<?= $arItem['URL'] ?>">
                                <?= $arItem['NAME'] ?>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
        <?php } ?>
    </table>
</div>