<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;
use intec\core\helpers\Type;

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

$this->setFrameMode(true);
?>
<?php if (!empty($arResult['STORES'])) { ?>
    <div class="intec-content">
        <div class="intec-content-wrapper">
            <div class="c-catalog-store-amount c-catalog-store-amount-template-1">
                <?php if (!empty($arParams['MAIN_TITLE'])) { ?>
                    <div class="item-sub-title">
                        <?= $arParams['MAIN_TITLE'] ?>
                    </div>
                <?php } ?>
                <div class="catalog-store-amount-elements">
                    <?php foreach ($arResult['STORES'] as $arStore) { ?>
                        <div class="catalog-store-amount-element" style="<?= ($arParams['SHOW_EMPTY_STORE'] == 'N' && isset($arStore['REAL_AMOUNT']) && $arStore['REAL_AMOUNT'] <= 0 ? 'display: none' : null) ?>">
                            <div class="catalog-store-amount-element-wrapper">
                                <div class="catalog-store-amount-element-table">
                                    <?php if (!empty($arStore['TITLE']) || !empty($arStore['DESCRIPTION'])) { ?>
                                        <div class="catalog-store-amount-element-column column-1">
                                            <?php if (!empty($arStore['TITLE'])){ ?>
                                                <?= Html::tag( !empty($arStore['URL']) ? 'a' : 'div',
                                                    $arStore['TITLE'],
                                                    [
                                                        'class' => 'catalog-store-amount-field address',
                                                        'href' => $arStore['URL']
                                                    ]
                                                ) ?>
                                            <?php } ?>
                                            <?php if (!empty($arStore['DESCRIPTION'])){ ?>
                                                <div class="catalog-store-amount-field description">
                                                    <?= $arStore['DESCRIPTION'] ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                    <?php if (!empty($arStore['PHONE']) || !empty($arStore['EMAIL'])) { ?>
                                        <div class="catalog-store-amount-element-column column-2">
                                            <?php if (!empty($arStore['PHONE'])) { ?>
                                                <div class="catalog-store-amount-field tel"><?= $arStore['PHONE'] ?></div>
                                            <?php } ?>
                                            <?php if (!empty($arStore['EMAIL'])) { ?>
                                                <div class="catalog-store-amount-field email"><?= $arStore['EMAIL'] ?></div>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                    <?php if (!empty($arStore['SCHEDULE'])) { ?>
                                        <div class="catalog-store-amount-element-column column-3">
                                            <div class="catalog-store-amount-field schedule">
                                                <?= Html::decode($arStore['SCHEDULE']) ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="catalog-store-amount-element-column column-4">
                                        <?php if ($arParams['SHOW_GENERAL_STORE_INFORMATION'] === 'N') { ?>
                                            <?php $iAmount = Type::toInteger($arStore['REAL_AMOUNT']); ?>
                                            <?php if ($iAmount > 0) { ?>
                                                <i class="catalog-store-amount-icon fas fa-check"></i>
                                            <?php } else { ?>
                                                <i class="catalog-store-amount-icon catalog-store-amount-icon-times fas fa-times"></i>
                                            <?php } ?>
                                        <?php } ?>
                                        <?= Loc::getMessage('C_CATALOLG_STORE_AMOUNT_TEMPLATE1_AMOUNT') ?>:
                                        <div class="catalog-store-amount-field count" id="<?= $arResult['JS']['ID'] ?>_<?= $arStore['ID'] ?>"><?= $arStore["AMOUNT"] ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
<?php } else { ?>
    <div class="intec-content">
        <div class="intec-content-wrapper">
            <div class="c-catalog-store-amount c-catalog-store-amount-template-1">
                <?= Loc::getMessage('C_CATALOLG_STORE_AMOUNT_TEMPLATE1_EMPTY') ?>
            </div>
        </div>
    </div>
<?php } ?>

<?if (isset($arResult["IS_SKU"]) && $arResult["IS_SKU"] == 1):?>
	<script type="text/javascript">
        $(document).on('ready', function()
        {
            var obStoreAmount = new JCCatalogStoreSKU(<? echo CUtil::PhpToJSObject($arResult['JS'], false, true, true); ?>);
            offers.on('change', function (event, offer, values) {
                obStoreAmount.offerOnChange(offer.id);
            })
        });
    </script>
	<?
endif;?>