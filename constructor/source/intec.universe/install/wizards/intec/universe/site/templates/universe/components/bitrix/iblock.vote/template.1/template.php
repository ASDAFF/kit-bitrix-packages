<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\bitrix\Component;
use intec\core\helpers\Html;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 */

$this->setFrameMode(true);
$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));
$sVoteDisplayValue = null;

if ($arParams['DISPLAY_AS_RATING'] == 'vote_avg') {
	if ($arResult['PROPERTIES']['vote_count']['VALUE'])
        $sVoteDisplayValue = round($arResult['PROPERTIES']['vote_sum']['VALUE']/$arResult['PROPERTIES']['vote_count']['VALUE'], 2);
	else
        $sVoteDisplayValue = 0;
} else {
    $sVoteDisplayValue = $arResult['PROPERTIES']['rating']['VALUE'];
}

?>
<div class="ns-bitrix c-iblock-vote c-iblock-vote-template-1" id="<?= $sTemplateId ?>" data-id="<?= $arResult["ID"] ?>">
    <div class="iblock-vote-rating" data-role="container">
        <?php foreach ($arResult['VOTE_NAMES'] as $key => $sName) { ?>
            <?= Html::Tag('i', '', [
                'class' => 'iblock-vote-rating-item intec-ui-icon intec-ui-icon-star-1',
                'data-role' => 'container.vote',
                'data-active' => ($sVoteDisplayValue && round($sVoteDisplayValue) > $key) ? 'true' : 'false',
                'data-value' => $key,
                'title' => $sName
            ])?>
        <?php } ?>
        <?php if ($arParams['SHOW_RATING'] == 'Y' && $sVoteDisplayValue) { ?>
            <div class="iblock-vote-rating-total"><?= $sVoteDisplayValue ?></div>
        <?php } ?>
    </div>
    <?php include(__DIR__.'/parts/script.php') ?>
</div>