<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use Bitrix\Main\Localization\Loc;

/**
 * @var array $arParams
 * @var array $arResult
 */

$this->setFrameMode(true);

$bRecommendation = ArrayHelper::getValue($arResult, ['MARKERS', 'RECOMMENDATION']);
$bNew = ArrayHelper::getValue($arResult, ['MARKERS', 'NEW']);
$bHit = ArrayHelper::getValue($arResult, ['MARKERS', 'HIT']);
$bDiscount = ArrayHelper::getValue($arResult, ['MARKERS', 'DISCOUNT', 'ACTIVE']);
$sDiscountValue = ArrayHelper::getValue($arResult, ['MARKERS', 'DISCOUNT', 'VALUE']);

?>
<div class="intec-marks">
    <?php if ($bRecommendation) { ?>
        <div class="intec-mark recommend">
            <?= Loc::getMessage('MARKERS_VIEW_MARKER_RECOMMENDATION') ?>
        </div>
    <?php } ?>
    <?php if ($bNew) { ?>
        <div class="intec-mark new">
            <?= Loc::getMessage('MARKERS_VIEW_MARKER_NEW') ?>
        </div>
    <?php } ?>
    <?php if ($bHit) { ?>
        <div class="intec-mark hit">
            <?= Loc::getMessage('MARKERS_VIEW_MARKER_HIT') ?>
        </div>
    <?php } ?>
    <?php if ($bDiscount) { ?>
        <div class="intec-mark action">
            <?= '- '.$sDiscountValue.' %' ?>
        </div>
    <?php } ?>
</div>