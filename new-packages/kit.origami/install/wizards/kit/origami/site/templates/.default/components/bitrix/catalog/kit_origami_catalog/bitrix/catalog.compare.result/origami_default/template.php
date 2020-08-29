<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */

/** @var CBitrixComponent $component */

use Sotbit\Origami\Helper\Config;

$isAjax = false;

$hoverClass = implode(" ", Config::getArray("HOVER_EFFECT"));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $isAjax = (
        (isset($_POST['ajax_action']) && $_POST['ajax_action'] == 'Y')
        || (isset($_POST['compare_result_reload']) && $_POST['compare_result_reload'] == 'Y')
    );
}

$templateData = array(
    'TEMPLATE_THEME' => $this->GetFolder() . '/themes/' . $arParams['TEMPLATE_THEME'] . '/style.css',
    'TEMPLATE_CLASS' => 'bx_' . $arParams['TEMPLATE_THEME']
);

?>
<div class="bx_compare <? echo $templateData['TEMPLATE_CLASS']; ?>" id="bx_catalog_compare_block"><?
    if ($isAjax) {
        $APPLICATION->RestartBuffer();
    }
    ?>
    <div class="row personal_block_component">
        <div class="col-sm-12">
        <!-- BREADCRUMBS -->
        <?php
        $APPLICATION->AddChainItem(GetMessage("CATALOG_COMPARE_TITLE"));
        $APPLICATION->IncludeComponent(
            'bitrix:breadcrumb',
            'origami_default',
            [
                "START_FROM" => "0",
                "PATH"       => "",
                "SITE_ID"    => "-",
            ],
            false,
            [
                'HIDE_ICONS' => 'N',
            ]
        );
        ?>
        <!-- / BREADCRUMBS -->
        </div>
        <div class="col-sm-12">
            <!-- TITTLE -->
            <p class="compare-page_title"><?= GetMessage("CATALOG_COMPARE_TITLE") ?></p>
            <!-- / TITTLE -->
        </div>
    </div>

    <div class="compare-content_wrapper">
        <!-- MENUS TABLE -->
        <div class="compare-left_sidebar-wrapper">
            <div class="compare-left_sidebar">
                <div class="compare-menu_panel">
                    <div class="compare-menu_panel-title">
                        <span><?= GetMessage("CATALOG_COMPARE_MENU_TITLE") ?></span><span><?=count($arResult['ITEMS']);?></span>
                    </div>
                    <div class="compare-menu_panel-content">
                        <div class="compare_menu-panel_switch <?= ($_GET['DIFFERENT'] == 'Y' ? 'different' : 'all') ?>">
                            <div class="menu-panel_switch-block">
                                <div class="compare-switch">
                                    <div class="switch <?= ($_GET['DIFFERENT'] == 'Y' ? 'switch-on' : '') ?>"></div>
                                </div>
                            </div>
                            <div class="menu-panel_radio-text_block">
                                <a class="all_params"
                                   href="<? echo $arResult['COMPARE_URL_TEMPLATE'] . 'DIFFERENT=N'; ?>">
                                    <?= GetMessage("CATALOG_ALL_PARAMS") ?></a>
                                <a class="different_params"
                                   href="<? echo $arResult['COMPARE_URL_TEMPLATE'] . 'DIFFERENT=Y'; ?>">
                                    <?= GetMessage("CATALOG_ONLY_DIFFERENT") ?></a>
                            </div>
                        </div>
                        <div class="compare_menu-panel_show-deleted">
                            <div class="main_checkbox ">
                                <input type="checkbox" id="compare-show_deleted1" value="Y"
                                       class="checkbox__input compare-show_deleted">
                                <label for="compare-show_deleted1">
                                    <span></span>
                                    <span><?= GetMessage("CATALOG_SHOW_DELETED") ?></span>
                                </label>
                            </div>
                        </div>
                        <a class="compare_menu-panel_clear"
                           href="/catalog/compare/?action_ccr=DELETE_FROM_COMPARE_RESULT&ID=ALL;">
                            <svg class="icon-clear-cart" width="18" height="22">
                                <use
                                    xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_clear_cart"></use>
                            </svg>
                            <span><?= GetMessage("CATALOG_CLEAR_COMPARE") ?></span>
                        </a>
                    </div>
                </div>
                <?
                if (!empty($arResult["SHOW_PROPERTIES"])) {
                    foreach ($arResult["SHOW_PROPERTIES"] as $code => $arProperty) {
                        $showRow = true;
                        if ($arResult['DIFFERENT']) {
                            $arCompare = array();
                            foreach ($arResult["ITEMS"] as $arElement) {
                                $arPropertyValue = $arElement["DISPLAY_PROPERTIES"][$code]["VALUE"];
                                if (is_array($arPropertyValue)) {
                                    sort($arPropertyValue);
                                    $arPropertyValue = implode(" / ", $arPropertyValue);
                                }
                                $arCompare[] = $arPropertyValue;
                            }
                            unset($arElement);
                            $showRow = (count(array_unique($arCompare)) > 1);
                        }

                        if ($showRow) {
                            ?>
                            <div class="compare-property_title-wrapper">
                                <div class="compare-property_title">
                                    <div class="compare-property_title-content">
                                        <p class="title">
                                            <?= $arProperty["NAME"] ?>
                                        </p>
                                        <div class="compare-property_title-icons">
                                            <div class="show-description">
                                                <!--do not remove. Set inline (display:none) if there are no description -->
                                                <svg class="icon_question_small" width="20" height="20">
                                                    <use
                                                        xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_question_small"></use>
                                                </svg>
                                                <div class="compare-property_title-description">
                                                    <span class="descripption-title"><?= $arProperty["NAME"] ?></span>
                                                    <span class="descripption-text">
                                                        <? if ($arProperty['FILTER_HINT'] != '')
                                                            echo $arProperty["FILTER_HINT"];
                                                        else
                                                            echo GetMessage('FILTER_HINT_NULL');
                                                        ?>
                                                    </span>
                                                </div>
                                                <!-- //// -->
                                            </div>
                                            <svg class="icon_cancel_comparison_small" width="20" height="20">
                                                <use
                                                    xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_cancel_comparison_small"></use>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <? unset($arElement); ?>
                            </div>
                            <?
                        }
                    }
                }
                ?>
                <div class="compare-menu_panel">
                    <div class="compare-menu_panel-title">
                        <span><?= GetMessage("CATALOG_COMPARE_MENU_TITLE") ?></span><span><?=count($arResult['ITEMS']);?></span>
                    </div>
                    <div class="compare-menu_panel-content">
                        <div class="compare_menu-panel_switch <?= ($_GET['DIFFERENT'] == 'Y' ? 'different' : 'all') ?>">
                            <div class="menu-panel_switch-block">
                                <div class="compare-switch">
                                    <div class="switch <?= ($_GET['DIFFERENT'] == 'Y' ? 'switch-on' : '') ?>"></div>
                                </div>
                            </div>
                            <div class="menu-panel_radio-text_block">
                                <a class="all_params"
                                   href="<? echo $arResult['COMPARE_URL_TEMPLATE'] . 'DIFFERENT=N'; ?>">
                                    <?= GetMessage("CATALOG_ALL_PARAMS") ?></a>
                                <a class="different_params"
                                   href="<? echo $arResult['COMPARE_URL_TEMPLATE'] . 'DIFFERENT=Y'; ?>">
                                    <?= GetMessage("CATALOG_ONLY_DIFFERENT") ?></a>
                            </div>
                        </div>
                        <div class="compare_menu-panel_show-deleted">
                            <div class="main_checkbox ">
                                <input type="checkbox" id="compare-show_deleted2" value="Y"
                                       class="checkbox__input compare-show_deleted">
                                <label for="compare-show_deleted2">
                                    <span></span>
                                    <span><?= GetMessage("CATALOG_SHOW_DELETED") ?></span>
                                </label>
                            </div>
                        </div>
                        <a class="compare_menu-panel_clear"
                           href="/catalog/compare/?action_ccr=DELETE_FROM_COMPARE_RESULT&ID=ALL;">
                            <svg class="icon-clear-cart" width="18" height="22">
                                <use
                                    xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_clear_cart"></use>
                            </svg>
                            <span><?= GetMessage("CATALOG_CLEAR_COMPARE") ?></span>
                        </a>
                    </div>
                </div>
            </div>

        </div>
        <!-- / MENUS TABLE -->

        <!-- CONTENT TABLE -->
        <div class="compare-properties_block">
            <div>
                <!-- TOP IMAGES -->
                <div class="compare-product_items-wrapper top-images">
                    <div class="compare-product_items">
                        <? foreach ($arResult["ITEMS"] as $arElement) { ?>

                            <div class="compare-product_item">

                                <div class="compare-product_item-content">

                                    <? if (!empty($arElement["FIELDS"]["DETAIL_PICTURE"]) && is_array($arElement["FIELDS"]["DETAIL_PICTURE"])): ?>
                                        <a class="table_compare_product_img <?= $hoverClass ?>"
                                           href="<?= $arElement["DETAIL_PAGE_URL"] ?>">
                                            <img src="<?= $arElement["FIELDS"]["DETAIL_PICTURE"]["SRC"] ?>"
                                                 alt="<?= $arElement["FIELDS"]["DETAIL_PICTURE"]["ALT"] ?>"
                                                 title="<?= $arElement["FIELDS"]["DETAIL_PICTURE"]["TITLE"] ?>"
                                            />
                                        </a>
                                    <? endif; ?>

                                    <div>
                                        <a class="compare-product_title"
                                           href="<?= $arElement["DETAIL_PAGE_URL"] ?>">
                                            <?= $arElement["NAME"] ?>
                                        </a>

                                        <div class="product-card-inner__rating-block">
                                            <?php
                                                $APPLICATION->IncludeComponent(
                                                    'bitrix:iblock.vote',
                                                    'origami_stars',
                                                    [
                                                        'CUSTOM_SITE_ID' => null,
                                                        'IBLOCK_TYPE' => $arParams['~IBLOCK_TYPE'],
                                                        'IBLOCK_ID' => $arElement['IBLOCK_ID'],
                                                        'ELEMENT_ID' => $arElement['~ID'],
                                                        'ELEMENT_CODE' => '',
                                                        'MAX_VOTE' => '5',
                                                        'VOTE_NAMES' => ['1', '2', '3', '4', '5'],
                                                        'SET_STATUS_404' => 'N',
                                                        'DISPLAY_AS_RATING' => 'vote_avg',
                                                        'CACHE_TYPE' => $arParams['CACHE_TYPE'],
                                                        'CACHE_TIME' => $arParams['CACHE_TIME'],
                                                        'READ_ONLY' => 'Y'
                                                    ],
                                                    $component,
                                                    ['HIDE_ICONS' => 'Y']
                                                );
                                            ?>
                                        </div>

                                        <? if (isset($arElement['MIN_PRICE']) && is_array($arElement['MIN_PRICE'])) { ?>
                                            <div class='compare-price'>
                                                <? echo $arElement['MIN_PRICE']['PRINT_DISCOUNT_VALUE']; ?>
                                            </div>
                                        <? } ?>

                                        <? if (count($_SESSION['CATALOG_COMPARE_LIST'][$arParams['IBLOCK_ID']]['ITEMS']) > 1):?>
                                            <a class="table_compare_product_close"
                                               onclick="CatalogCompareObj.delete('<?= CUtil::JSEscape($arElement['~DELETE_URL']) ?>');"
                                               href="<?= CUtil::JSEscape($arElement['~DELETE_URL']) ?>">
                                            </a>
                                        <? endif; ?>
                                    </div>
                                </div>
                            </div>
                        <? }
                        unset($arElement); ?>
                    </div>
                </div>
                <!-- TOP IMAGES -->

                <? if (!empty($arResult["SHOW_PROPERTIES"])) {

                    ?>
                    <div class="compare-properties_wrapper">
                        <?

                        foreach ($arResult["SHOW_PROPERTIES"] as $code => $arProperty) {
                            $showRow = true;

                            if ($arResult['DIFFERENT']) {

                                $arCompare = array();

                                foreach ($arResult["ITEMS"] as $arElement) {
                                    $arPropertyValue = $arElement["DISPLAY_PROPERTIES"][$code]["VALUE"];
                                    if (is_array($arPropertyValue)) {
                                        sort($arPropertyValue);
                                        $arPropertyValue = implode(" / ", $arPropertyValue);
                                    }
                                    $arCompare[] = $arPropertyValue;
                                }

                                unset($arElement);
                                $showRow = (count(array_unique($arCompare)) > 1);
                            }

                            if ($showRow) { ?>
                                <div class="mobile-title-wrapper">
                                    <div class="mobile-compare-property_title-border">
                                        <div class="mobile-compare-property_title">
                                            <span><?= $arProperty["NAME"] ?></span>
                                            <div class="mobile-compare-property_title-icons">
                                                <!--do not remove. Set inline (display:none) if there are no description -->
                                                <svg class="mobile-icon_question_small" width="20" height="20">
                                                    <use
                                                        xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_question_small"></use>
                                                </svg>
                                                <!-- //// -->
                                                <svg class="mobile-icon_cancel_comparison_small" width="20" height="20">
                                                    <use
                                                        xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_cancel_comparison_small"></use>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    <!--do not remove. Set inline (display:none) if there are no description -->
                                    <div class="mobile-compare-property_title-description-wrapper">
                                        <div class="mobile-compare-property_title-content-wrapper">
                                            <div class="mobile-compare-property_title-description">
                                                <span class="mobile-description-title"><?=$arProperty['NAME']?></span>
                                                <span class="mobile-description-text">
                                                     <? if ($arProperty['FILTER_HINT'] != '')
                                                         echo $arProperty["FILTER_HINT"];
                                                     else
                                                         echo GetMessage('FILTER_HINT_NULL');
                                                     ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- //// -->
                                </div>
                                <div class="compare-row_mobile_wrapper">
                                    <div class="table-properties-wrapper">
                                        <div class="mobile-compare-product_titles_wrapper">
                                            <? foreach ($arResult["ITEMS"] as $arElement) { ?>
                                                <div class="mobile-compare-product_title-border_wrapper">
                                                    <div class="mobile-compare-product_title">
                                                        <span>
                                                            <?= $arElement["NAME"] ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            <? }
                                            unset($arElement); ?>
                                        </div>
                                        <div class="compare-propertie_row">
                                            <? foreach ($arResult["ITEMS"] as $arElement) { ?>
                                                <div class="compare-propertie_wpapper">
                                                    <div class="compare-propertie_item">
                                                        <span>
                                                             <?= (is_array($arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])
                                                                 ? implode("/ ", $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])
                                                                 : $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]) ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            <? }
                                            unset($arElement); ?>
                                        </div>
                                    </div>
                                </div>
                            <? }
                        } ?>
                    </div>
                <? } ?>

                <!-- BOTTOM IMAGES -->
                <div class="compare-product_items-wrapper bottom-images">
                    <div class="compare-product_items">
                        <? foreach ($arResult["ITEMS"] as $arElement) { ?>

                            <div class="compare-product_item">

                                <div class="compare-product_item-content">

                                    <? if (!empty($arElement["FIELDS"]["DETAIL_PICTURE"]) && is_array($arElement["FIELDS"]["DETAIL_PICTURE"])): ?>
                                        <a class="table_compare_product_img <?= $hoverClass ?>"
                                           href="<?= $arElement["DETAIL_PAGE_URL"] ?>">
                                            <img src="<?= $arElement["FIELDS"]["DETAIL_PICTURE"]["SRC"] ?>"
                                                 alt="<?= $arElement["FIELDS"]["DETAIL_PICTURE"]["ALT"] ?>"
                                                 title="<?= $arElement["FIELDS"]["DETAIL_PICTURE"]["TITLE"] ?>"
                                            />
                                        </a>
                                    <? endif; ?>

                                    <div>
                                        <a class="compare-product_title"
                                           href="<?= $arElement["DETAIL_PAGE_URL"] ?>">
                                            <?= $arElement["NAME"] ?>
                                        </a>

                                        <div class="product-card-inner__rating-block">
                                            <?
                                            $APPLICATION->IncludeComponent(
                                                'bitrix:iblock.vote',
                                                'origami_stars',
                                                [
                                                    'CUSTOM_SITE_ID' => null,
                                                    'IBLOCK_TYPE' => $arParams['~IBLOCK_TYPE'],
                                                    'IBLOCK_ID' => $arElement['IBLOCK_ID'],
                                                    'ELEMENT_ID' => $arElement['ID'],
                                                    'ELEMENT_CODE' => '',
                                                    'MAX_VOTE' => '5',
                                                    'VOTE_NAMES' => ['1', '2', '3', '4', '5'],
                                                    'SET_STATUS_404' => 'N',
                                                    'DISPLAY_AS_RATING' => 'vote_avg',
                                                    'CACHE_TYPE' => $arParams['CACHE_TYPE'],
                                                    'CACHE_TIME' => $arParams['CACHE_TIME'],
                                                    'READ_ONLY' => 'Y'
                                                ],
                                                $component,
                                                ['HIDE_ICONS' => 'Y']
                                            );
                                            ?>
                                        </div>

                                        <? if (isset($arElement['MIN_PRICE']) && is_array($arElement['MIN_PRICE'])) { ?>
                                            <div class='compare-price'>
                                                <? echo $arElement['MIN_PRICE']['PRINT_DISCOUNT_VALUE']; ?>
                                            </div>
                                        <? } ?>

                                        <? if (count($_SESSION['CATALOG_COMPARE_LIST'][$arParams['IBLOCK_ID']]['ITEMS']) > 1):?>
                                            <a class="table_compare_product_close"
                                               onclick="CatalogCompareObj.delete('<?= CUtil::JSEscape($arElement['~DELETE_URL']) ?>');"
                                               href="<?= CUtil::JSEscape($arElement['~DELETE_URL']) ?>">
                                            </a>
                                        <? endif; ?>

                                    </div>
                                </div>
                            </div>
                        <? }
                        unset($arElement); ?>
                    </div>
                </div>
                <!-- BOTTOM IMAGES -->

                <div class="mobile-compare-menu_panel">
                    <div class="compare-menu_panel">
                        <div class="compare-menu_panel-title">
                            <span><?= GetMessage("CATALOG_COMPARE_MENU_TITLE") ?></span><span><?=count($arResult['ITEMS']);?></span>
                        </div>
                        <div class="compare-menu_panel-content">
                            <div
                                class="compare_menu-panel_switch <?= ($_GET['DIFFERENT'] == 'Y' ? 'different' : 'all') ?>">
                                <div class="menu-panel_switch-block">
                                    <div class="compare-switch">
                                        <div class="switch <?= ($_GET['DIFFERENT'] == 'Y' ? 'switch-on' : '') ?>"></div>
                                    </div>
                                </div>
                                <div class="menu-panel_radio-text_block">
                                    <a class="all_params"
                                       href="<? echo $arResult['COMPARE_URL_TEMPLATE'] . 'DIFFERENT=N'; ?>">
                                        <?= GetMessage("CATALOG_ALL_PARAMS") ?></a>
                                    <a class="different_params"
                                       href="<? echo $arResult['COMPARE_URL_TEMPLATE'] . 'DIFFERENT=Y'; ?>">
                                        <?= GetMessage("CATALOG_ONLY_DIFFERENT") ?></a>
                                </div>
                            </div>
                            <div class="compare_menu-panel_show-deleted">
                                <div class="main_checkbox ">
                                    <input type="checkbox" id="compare-show_deleted3" value="Y"
                                           class="checkbox__input compare-show_deleted">
                                    <label for="compare-show_deleted3">
                                        <span></span>
                                        <span><?= GetMessage("CATALOG_SHOW_DELETED") ?></span>
                                    </label>
                                </div>
                            </div>
                            <a class="compare_menu-panel_clear"
                               href="/catalog/compare/?action_ccr=DELETE_FROM_COMPARE_RESULT&ID=ALL;">
                                <svg class="icon-clear-cart" width="18" height="22">
                                    <use
                                        xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_clear_cart"></use>
                                </svg>
                                <span><?= GetMessage("CATALOG_CLEAR_COMPARE") ?></span>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- /CONTENT TABLE -->

        <?
        if ($isAjax) {
            die();
        }
        ?>
    </div>
</div>

<script type="text/javascript">
    var CatalogCompareObj = new BX.Iblock.Catalog.CompareClass("bx_catalog_compare_block", '<?=CUtil::JSEscape($arResult['~COMPARE_URL_TEMPLATE']); ?>');
</script>
