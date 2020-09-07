<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */

$this->setFrameMode(true);

?>
<a class="startshop-basket-small default startshop-link startshop-link-hover-dark <?= $arParams['DISPLAY_COUNT'] == 'Y' ? 'startshop-with-count' : '' ?>"
   href="<?= $arParams['URL_BASKET'] ?>">
	<?php $frame = $this->createFrame()->begin(); ?>
        <?php if ($arParams['USE_COUNT'] == 'Y' && ($arResult['COUNT'] > 0 || $arParams['USE_COUNT_IF_EMPTY'] == 'Y')) { ?>
			<div class="startshop-basket-small-count startshop-element-background">
				<div class="startshop-aligner-vertical"></div>
				<div class="startshop-basket-small-text"><?= $arResult['COUNT'] ?></div>
			</div>
		<?php } ?>
		<div class="startshop-basket-small-icon"></div>
		<?php if ($arParams['USE_SUM'] == 'Y') { ?>
			<div class="startshop-basket-small-text-total"><?= $arResult['SUM']['PRINT_VALUE'] ?></div>
		<?php } ?>
	<?php $frame->beginStub(); ?>
        <?php if ($arParams['USE_COUNT'] == 'Y' && ($arResult['COUNT'] > 0 || $arParams['USE_COUNT_IF_EMPTY'] == 'Y')) { ?>
			<div class="startshop-basket-small-count startshop-element-background">
				<div class="startshop-aligner-vertical"></div>
				<div class="startshop-basket-small-text">0</div>
			</div>
        <?php } ?>
        <div class="startshop-basket-small-icon"></div>
        <?php if ($arParams['USE_SUM'] == 'Y') { ?>
            <div class="startshop-basket-small-text-total">
                0
            </div>
        <?php } ?>
	<?php $frame->end(); ?>
</a>