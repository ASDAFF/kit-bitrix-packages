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

<div class="brand_list_inner">
    <? foreach ($arResult["ITEMS"] as $arItem): ?>
        <?
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>
        <div class="brand_list__item" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
            <? if ($arParams["DISPLAY_PICTURE"] != "N"): ?>
                <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="brand_block_variant_two__item_link">
                    <div class="brand_list__item--image-wrapper">
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
                    </div>
                    <?if($arParams['DISPLAY_VIEW_TITLE'] == 'Y'):?>
                        <p class="brand_block__title"><? if ($arItem["PREVIEW_PICTURE"]): ?>
                                <?= $arItem["PREVIEW_PICTURE"]["TITLE"] ?>
                            <? else: ?>
                                <?= $arItem["NAME"] ?>
                            <? endif ?>
                        </p>
                    <?endif;?>
                </a>
            <? endif ?>
        </div>
    <? endforeach; ?>

</div>
<div data-pagination-num="<?= $navParams['NavNum'] ?>">
    <?= $arResult['NAV_STRING'] ?>
</div>
