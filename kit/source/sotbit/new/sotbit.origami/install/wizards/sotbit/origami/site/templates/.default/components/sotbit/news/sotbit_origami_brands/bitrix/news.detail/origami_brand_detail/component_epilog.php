<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>

<div class="brand-detail__detail">
    <div class="brand-detail__text clearfix">
        <div class="brand-detail__picture">
            <img
                border="0"
                src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>"
                width="<?=$arResult["DETAIL_PICTURE"]["WIDTH"]?>"
                height="<?=$arResult["DETAIL_PICTURE"]["HEIGHT"]?>"
                alt="<?=$arResult["DETAIL_PICTURE"]["ALT"]?>"
                title="<?=$arResult["DETAIL_PICTURE"]["TITLE"]?>"
            />
        </div>
        <?=$arResult['DETAIL_TEXT']?>
    </div>
</div>
<h2><?=\Bitrix\Main\Localization\Loc::getMessage
    (\SotbitOrigami::moduleId.'_BRAND_TITLE',['#BRAND#' => $arResult['NAME']])?>
</h2>
