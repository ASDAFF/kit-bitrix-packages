<?php

use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
$this->setFrameMode(true);

?>
<div class="blog-detail">
	<div class="blog-detail__top">
        <?php if ($arParams["DISPLAY_PICTURE"] != "N"
            && is_array($arResult["DETAIL_PICTURE"])
        ) : ?>
			<img
					class="detail_picture"
					border="0"
					src="<?= $arResult["DETAIL_PICTURE"]["SRC"] ?>"
					width="<?= $arResult["DETAIL_PICTURE"]["WIDTH"] ?>"
					height="<?= $arResult["DETAIL_PICTURE"]["HEIGHT"] ?>"
					alt="<?= $arResult["DETAIL_PICTURE"]["ALT"] ?>"
					title="<?= $arResult["DETAIL_PICTURE"]["TITLE"] ?>"
			/>
        <?php endif ?>
        <?php
        if (strlen($arResult["PREVIEW_TEXT"]) > 0) {
            ?>
			<div class="blog-detail__preview-text">
                <?= $arResult["PREVIEW_TEXT"] ?>
			</div>
            <?php
        }
        ?>

        <?php if ($arParams["DISPLAY_DATE"] != "N"
            && $arResult["DISPLAY_ACTIVE_FROM"]
        ) : ?>
			<div class="blog-date-time"><?= $arResult["DISPLAY_ACTIVE_FROM"] ?></div>
        <?php endif;
        if ($arResult['TAGS']) {
            ?>
			<div class="blog-detail__tags">
                <?php
                foreach ($arResult['TAGS'] as $tag) {
                    ?>
					<a class="blog-detail__tag"
					   href="<?= $arResult['LIST_PAGE_URL'] ?>?blog_ff%5BTAGS%5D=<?= $tag ?>&set_filter=<?= Loc::getMessage('BUTTON_FILTER') ?>&set_filter=Y">
						#<?= $tag ?>
					</a>
                    <?php
                }
                ?>
			</div>
            <?
        }
        ?>
	</div>
    <?php if (strlen($arResult["DETAIL_TEXT"]) > 0) : ?>
		<div class="blog-detail__middle">
            <?php echo $arResult["DETAIL_TEXT"]; ?>
		</div>
    <?php endif;
    if ($arResult['PHOTOS']) {
        ?>
		<div class="blog-detail__gallery">
			<div class="blog-detail__gallery-title">
                <?= Loc::getMessage('GALLERY_TITLE') ?>
			</div>
			<div class="blog-detail__gallery-items owl-carousel">
                <?
                foreach ($arResult['PHOTOS'] as $photo) {
                    ?>
					<div class="blog-detail__gallery-item">
						<img src="<?= $photo['src'] ?>">
					</div>
                    <?
                }
                ?>
			</div>
		</div>
        <?
    }
    ?>
</div>
