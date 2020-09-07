<?php

use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
$this->setFrameMode(true);
?>
<div class="promotion_detail__banner"
style="<?=($arResult['BACKGROUND'])?"background-image:url('".$arResult['BACKGROUND']['SRC']."');height:".$arResult['BACKGROUND']['HEIGHT']."px":""?>">
	<div class="promotion_detail__banner_background"></div>
	<div class="puzzle_block puzzle_block__promotion_detail bootstrap_style">
		<div class="promotion_detail__banner_info">
			<div class="promotion_detail__banner_info_inner">
				<div class="promotion_detail__banner_title">
					<?=$arResult['NAME']?>
				</div>
				<?
				if($arResult['PROPERTIES']['DESCRIPTION']['VALUE'])
				{
					?>
					<div class="promotion_detail__banner_description">
                        <?=$arResult['PROPERTIES']['DESCRIPTION']['VALUE']?>
					</div>
					<?
				}
				?>
				<div class="promotion_detail__banner_label">
                    <?=Loc::getMessage('BANNER_LABEL')?>
				</div>
			</div>
		</div>
		<?if($arResult['ACTIVE_IMG']['SRC'])
			{
				?>
		<div class="promotion_detail__banner_active_img" style="background-image: url('<?=$arResult['ACTIVE_IMG']['SRC']?>')">
				<?
            }?>
		</div>
	</div>
</div>
<div class="puzzle_block no-padding bootstrap_style">
	<?
	if($arResult['DATE_ACTIVE_TO_DISPLAY'])
	{
		?>
		<div class="promotion_detail__date">
			<?=Loc::getMessage('PROMOTION_DETAIL_UNTIL')?> <?=$arResult['DATE_ACTIVE_TO_DISPLAY']?>
		</div>
		<?
	}
    if($arResult['DETAIL_TEXT'])
    {
    	?>
		<div class="promotion_detail__text">
			<?=$arResult['DETAIL_TEXT']?>
		</div>
	    <?
    }
	?>
</div>