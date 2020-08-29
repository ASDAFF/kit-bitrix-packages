<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
?>

<div class="brand-detail-info <?= !is_array($arResult["DETAIL_PICTURE"]) ? 'without-banner' : '' ?>">
    <? if (is_array($arResult["DETAIL_PICTURE"]) || is_array($arResult["PREVIEW_PICTURE"])): ?>
        <div class="brand-detail-info__banner">
            <?if(is_array($arResult["DETAIL_PICTURE"])):?>
                <div class="brand-detail-info__banner-bg-image">
                    <img
                        border="0"
                        src="<?= $arResult["DETAIL_PICTURE"]["SRC"] ?>"
                        alt="<?= $arResult["DETAIL_PICTURE"]["ALT"] ?>"
                        title="<?= $arResult["DETAIL_PICTURE"]["TITLE"] ?>"
                    />
                </div>
            <? endif; ?>
            <?if(is_array($arResult["PREVIEW_PICTURE"])):?>
                <div class="brand-detail-info__banner-logo-wrapper">
                    <div class="brand-detail-info__banner-logo">
                        <img
                            border="0"
                            src="<?= $arResult["PREVIEW_PICTURE"]["SRC"] ?>"
                            alt="<?= $arResult["PREVIEW_PICTURE"]["ALT"] ?>"
                            title="<?= $arResult["PREVIEW_PICTURE"]["TITLE"] ?>"
                        />
                    </div>
                </div>
            <? endif; ?>

        </div>
    <? endif; ?>

    <? if (!is_array($arResult["DETAIL_PICTURE"])) { ?>
        <?= $arResult['DETAIL_TEXT'] ?>
    <? } ?>

    <? if (is_array($arResult["DETAIL_PICTURE"])): ?>

        <div class="brand-detail-info__description">
            <div class="brand-detail-info__description-text-wrapper">
            <span class="brand-detail-info__description-text">
                  <?= $arResult['DETAIL_TEXT'] ?>
            </span>
            </div>
            <div class="brand-detail-info__collapse-btns main-color">
                <span class="brand-detail-info__collapse-btns-show"><?= GetMessage("BRAND_BUTTON_SHOW_ALL"); ?></span>
                <span class="brand-detail-info__collapse-btns-hide"><?= GetMessage("BRAND_BUTTON_COLLAPSE"); ?></span>
            </div>
        </div>

    <? endif; ?>

</div>
<h2><?= \Bitrix\Main\Localization\Loc::getMessage
    (\KitOrigami::moduleId . '_BRAND_TITLE', ['#BRAND#' => $arResult['NAME']]) ?>
</h2>
