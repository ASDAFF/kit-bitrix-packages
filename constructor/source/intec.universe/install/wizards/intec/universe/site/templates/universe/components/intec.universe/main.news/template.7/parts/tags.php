<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @var array $arVisual
 * @var array $arTags
 * @var array $arItemTags
 */

?>
<?php return function (&$arData) use (&$arResult) { ?>
    <div class="widget-item-tags">
        <div class="widget-item-tags-items intec-grid intec-grid-wrap intec-grid-i-5">
            <?php foreach ($arData as $key => $sTag) {

                $bActive = ArrayHelper::isIn($key, $arResult['TAGS']['ACTIVE']);

            ?>
                <div class="widget-item-tags-item intec-grid-item-auto">
                    <label class="widget-item-tags-item-wrapper">
                        <?= Html::checkbox($arResult['TAGS']['VARIABLE'].'[]', $bActive, [
                            'value' => $key,
                            'disabled' => $arResult['TAGS']['MODE'] !== 'active',
                            'data-role' => 'items.input'
                        ]) ?>
                        <?= Html::tag('span', '#'.$sTag, [
                            'class' => Html::cssClassFromArray([
                                'intec-cl-background-light-hover' => $arResult['TAGS']['MODE'] === 'active'
                            ], true)
                        ]) ?>
                    </label>
                </div>
            <?php } ?>
        </div>
    </div>
<?php } ?>