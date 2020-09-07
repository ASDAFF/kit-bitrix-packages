<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
if (!empty($arResult['NAV_RESULT'])) {
    $navParams = array(
        'NavPageCount' => $arResult['NAV_RESULT']->NavPageCount,
        'NavPageNomer' => $arResult['NAV_RESULT']->NavPageNomer,
        'NavNum' => $arResult['NAV_RESULT']->NavNum
    );
} else {
    $navParams = array(
        'NavPageCount' => 1,
        'NavPageNomer' => 1,
        'NavNum' => $this->randString()
    );
}
?>

<div class="brand-list-wrapper">
    <? foreach ($arResult["ITEMS"] as $arItem): ?>
        <?
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>
        <div class="brand-list-wrapper__item">
            <div class="front" >
                <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="inner logo-wrapper">
                    <? if ($arParams["DISPLAY_PICTURE"] != "N"): ?>
                        <? if ($arItem["PREVIEW_PICTURE"]): ?>
                            <img src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>"
                                 width="<?= $arItem["PREVIEW_PICTURE"]["WIDTH"] ?>"
                                 height="<?= $arItem["PREVIEW_PICTURE"]["HEIGHT"] ?>"
                                 alt="<?= $arItem["PREVIEW_PICTURE"]["ALT"] ?>"
                                 title="<?= $arItem["PREVIEW_PICTURE"]["TITLE"] ?>"
                            >
                        <? else: ?>
                            <img src="<?= $templateFolder ?>/images/empty_h.jpg"
                                 alt="<?= $arItem["NAME"] ?>"
                                 title="<?= $arItem["NAME"] ?>"
                            >
                        <? endif ?>
                    <? endif ?>
                </a>
            </div>

            <div class="back">
                <div class="inner">
                    <div class="brand-list-wrapper__item-description">
                        <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="brand-list-wrapper__item-description-logo">
                            <span><?=$arItem['NAME']?></span>
                        </a>
                        <div class="brand-list-wrapper__item-description-text">
                            <span><?=$arItem['PREVIEW_TEXT']?></span>
                        </div>
                        <?if($arItem['BRAND_SITE']):?>
                            <div class="brand-list-wrapper__item-description-link">
                                <span><?=GetMessage('SITE_TITLE');?> </span><a href="<?=$arItem['BRAND_SITE']?>" target="_blank" class="main-color"><?=$arItem['BRAND_SITE']?></a>
                            </div>
                        <?endif;?>
                    </div>
                </div>
            </div>


        </div>
    <? endforeach; ?>
</div>
<div class="pagin" data-pagination-num="<?= $navParams['NavNum'] ?>">
    <?= $arResult['NAV_STRING'] ?>
</div>
