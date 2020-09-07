<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arParams
 * @var array $arResult
 * @var $APPLICATION CMain
 * @var CBitrixComponentTemplate $this
 */

?>
<div class="bx-soa-page bx-soa-page-empty">
	<div class="bx-soa-page-image"></div>
	<div class="bx-soa-page-title">
        <?= Loc::getMessage('EMPTY_BASKET_TITLE') ?>
    </div>
	<?php if (!empty($arParams['EMPTY_BASKET_HINT_PATH'])) { ?>
		<div class="bx-soa-page-description">
			<?= Loc::getMessage('EMPTY_BASKET_HINT', [
                '#A1#' => '<a href="'.$arParams['EMPTY_BASKET_HINT_PATH'].'">',
                '#A2#' => '</a>'
            ]) ?>
		</div>
    <?php } ?>
</div>