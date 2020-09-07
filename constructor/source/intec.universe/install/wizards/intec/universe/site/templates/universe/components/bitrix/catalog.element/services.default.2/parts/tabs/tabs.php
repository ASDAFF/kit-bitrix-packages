<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arVisual
 */

$arData = $arResult['DATA']['TAB'];

$bTabsUse = false;

foreach ($arData as &$arItem) {
    if ($arItem['SHOW']) {
        $bTabsUse = true;

        break;
    }
}

unset($arItem);

?>
<?php if ($bTabsUse) { ?>
    <div class="catalog-element-blocks" data-role="element.tabs">
        <div class="catalog-element-blocks-tabs">
            <?php $iCounter = 0 ?>
            <?php foreach ($arData as $arTab) { ?>
                <?php if ($arTab['SHOW']) { ?>
                    <?= Html::beginTag('div', [
                        'class' => Html::cssClassFromArray([
                            'catalog-element-blocks-tab' => true,
                            'intec-cl-background' => $iCounter < 1,
                            'intec-cl-background-light-hover' => $iCounter < 1
                        ], true),
                        'data' => [
                            'role' => 'element.tabs.item',
                            'id' => $iCounter,
                            'active' => $iCounter < 1 ? 'true' : 'false'
                        ]
                    ]) ?>
                        <?= $arTab['NAME'] ?>
                    <?= Html::endTag('div') ?>
                    <?php $iCounter++ ?>
                <?php } ?>
            <?php } ?>
        </div>
        <div class="catalog-element-blocks-content">
            <?php $iCounter = 0 ?>
            <?php foreach ($arData as $sKey => $arTab) { ?>
                <?php if ($arTab['SHOW']) { ?>
                    <?= Html::beginTag('div', [
                        'class' => 'catalog-element-blocks-content-item',
                        'data' => [
                            'role' => 'element.tabs.content.item',
                            'id' => $iCounter,
                            'active' => $iCounter < 1 ? 'true' : 'false'
                        ],
                        'style' => [
                            'display' => $iCounter < 1 ? 'block' : 'none'
                        ]
                    ]) ?>
                        <?php if ($sKey === 'DESCRIPTION') {
                            include(__DIR__.'/tab.description.php');
                        } else if ($sKey === 'VIDEO') {
                            include(__DIR__.'/tab.video.php');
                        } else if ($sKey === 'INFO') {
                            include(__DIR__.'/tab.info.php');
                        } ?>
                    <?= Html::endTag('div') ?>
                    <?php $iCounter++ ?>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
<?php } ?>
<?php unset($arData, $bTabsUse, $iCounter, $sKey, $arTab) ?>