<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

if ($arResult["ITEMS"]) {

    ?>
	<div class="product-detail-info-block-advantages">
		<div class="block-advantages-title fonts__middle_text"><?= GetMessage('CT_BNL_ELEMENT_CONTINUE') ?></div>
        <?
        foreach ($arResult["ITEMS"] as $arItem) {
            if($arItem['PROPERTIES']['URL']['VALUE'])
            {
            	?>
	            <a href="<?=$arItem['PROPERTIES']['URL']['VALUE']?>"
	            class="product-detail-info-block-advantages-item fonts__middle_comment">
	            <?
            }
            ?>

                <?
                if ($arParams["DISPLAY_PICTURE"] != "N"):?>
                    <?
                    if ($arItem["PREVIEW_PICTURE"]):?>
						<img src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>"
						     width="<?= $arItem["PREVIEW_PICTURE"]["WIDTH"] ?>"
						     height="<?= $arItem["PREVIEW_PICTURE"]["HEIGHT"] ?>"
						     alt="<?= $arItem["PREVIEW_PICTURE"]["ALT"] ?>"
						     title="<?= $arItem["PREVIEW_PICTURE"]["TITLE"] ?>">
                    <? else:?>
						<img src="<?= $templateFolder ?>/images/empty_h.jpg"
						     alt="<?= $arItem["NAME"] ?>"
						     title="<?= $arItem["NAME"] ?>"
						>
                    <?endif ?>
                <?endif ?>
				<span class="block-advantages-comment"><?= $arItem["NAME"] ?></span>
	        <?
            if($arItem['PROPERTIES']['URL']['VALUE'])
            {
            	?>
	            </a>
	            <?
            }
        }
        ?>
	</div>
	</div>
    <?
}