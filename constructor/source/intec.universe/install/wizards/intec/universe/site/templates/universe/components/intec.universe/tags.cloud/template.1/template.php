<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\Core;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 */

$this->setFrameMode(true);

$sVariableName = ArrayHelper::getValue($arResult, 'VARIABLE_NAME');

$request = Core::$app->request;
$arTagsActive = $request->get($sVariableName);
$arHeader = ArrayHelper::getValue($arResult, 'HEADER_BLOCK');

$iCounter = 0;

?>
<div class="intec-content">
    <div class="intec-content-wrapper">
        <div class="ns-bitrix c-tags-cloud c-tags-cloud-template-1">
            <?php if ($arHeader['SHOW']) { ?>
                <div class="tags-cloud-header">
                    <?= $arHeader['TEXT'] ?>
                </div>
            <?php } ?>
            <div class="tags-cloud-content">
                <?php foreach ($arResult['ITEMS'] as $arItem) {

                    $iCounter++;
                    $bCurrentValue = ArrayHelper::getValue($arTagsActive, $arItem['ID']) == 'Y';
                    $sButtonValue = $bCurrentValue ? 'N' : 'Y';
                    $sActiveClass = $bCurrentValue ? " active tags-cloud-color-$iCounter"  : " tags-cloud-color-$iCounter-hover";

                ?>
                    <form action="" method="get" class="tags-cloud-element">
                        <?= Html::hiddenInput($sVariableName, $arTagsActive) ?>
                        <button class="tags-cloud-element-button<?= $sActiveClass ?>" name="<?= $sVariableName ?>[<?= $arItem['ID'] ?>]" type="submit" value="<?= $sButtonValue ?>">
                            <?= '#'.$arItem['VALUE'] ?>
                        </button>
                    </form>
                    <?php if ($iCounter == 5) $iCounter = 0 ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
