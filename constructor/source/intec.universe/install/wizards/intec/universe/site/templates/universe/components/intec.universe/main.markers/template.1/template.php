<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

/**
 * @var $arResult
 * @var CBitrixComponentTemplate $this
 */

$this->setFrameMode(true);
$arVisual = $arResult['VISUAL'];

if (
    !$arResult['RECOMMEND'] &&
    !$arResult['NEW'] &&
    !$arResult['HIT']
) return;

?>
<div class="widget c-markers c-markers-template-1" data-orientation="<?= $arVisual['ORIENTATION'] ?>">
    <?php if ($arResult['RECOMMEND']) { ?>
        <div class="widget-markers-wrap">
            <?= Html::tag('div', Loc::getMessage('C_MAIN_MARKERS_TEMP1_RECOMMEND'), [
                'class' => 'widget-markers widget-markers-recommend'
            ]) ?>
        </div>
    <?php } ?>
    <?php if ($arResult['NEW']) { ?>
        <div class="widget-markers-wrap">
            <?= Html::tag('div', Loc::getMessage('C_MAIN_MARKERS_TEMP1_NEW'), [
                'class' => 'widget-markers widget-markers-new'
            ]) ?>
        </div>
    <?php } ?>
    <?php if ($arResult['HIT']) { ?>
        <div class="widget-markers-wrap">
            <?= Html::tag('div', Loc::getMessage('C_MAIN_MARKERS_TEMP1_HIT'), [
                'class' => 'widget-markers widget-markers-hit'
            ]) ?>
        </div>
    <?php } ?>
</div>