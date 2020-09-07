<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\bitrix\Component;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 */

$this->setFrameMode(true);

if (empty($arResult['ITEMS']))
    return;

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arBlocks = $arResult['BLOCKS'];
$arVisual = $arResult['VISUAL'];


/**
 * @var Closure $vItems()
 */
include (__DIR__.'/parts/items.php');

?>
<div class="widget c-gallery c-gallery-template-3" id="<?= $sTemplateId ?>">
    <?php if (!$arVisual['WIDE']) { ?>
        <div class="intec-content">
            <div class="intec-content-wrapper">
    <?php } ?>
        <?php if ($arBlocks['HEADER']['SHOW'] || $arBlocks['DESCRIPTION']['SHOW']) { ?>
            <div class="widget-header">
                <?php if ($arVisual['WIDE']) { ?>
                    <div class="intec-content">
                        <div class="intec-content-wrapper">
                <?php } ?>
                <?php if ($arBlocks['HEADER']['SHOW']) { ?>
                    <div class="widget-title align-<?= $arBlocks['HEADER']['POSITION'] ?>">
                        <?= Html::encode($arBlocks['HEADER']['TEXT']) ?>
                    </div>
                <?php } ?>
                <?php if ($arBlocks['DESCRIPTION']['SHOW']) { ?>
                    <div class="widget-description align-<?= $arBlocks['DESCRIPTION']['POSITION'] ?>">
                        <?= Html::encode($arBlocks['DESCRIPTION']['TEXT']) ?>
                    </div>
                <?php } ?>
                <?php if ($arVisual['WIDE']) { ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
        <?= Html::beginTag('div', [
            'class' => 'widget-content',
            'data' => [
                'indent' => $arVisual['INDENT']['USE'] ? $arVisual['INDENT']['VALUE'] : null
            ]
        ]) ?>
            <?php if ($arVisual['TABS']['USE']) {
                include(__DIR__.'/parts/tabs.php');
            } else {
                $vItems($arResult['ITEMS']);
            } ?>
        <?= Html::endTag('div') ?>
        <?php if ($arBlocks['FOOTER']['SHOW']) { ?>
            <?php if ($arVisual['WIDE']) { ?>
                <div class="intec-content">
                    <div class="intec-content-wrapper">
            <?php } ?>
            <div class="widget-footer align-<?= $arBlocks['FOOTER']['POSITION'] ?>">
                <?php if ($arBlocks['FOOTER']['BUTTON']['SHOW']) { ?>
                    <?= Html::tag('a', $arBlocks['FOOTER']['BUTTON']['TEXT'], [
                        'href' => $arBlocks['FOOTER']['BUTTON']['LINK'],
                        'class' => [
                            'widget-footer-button',
                            'intec-ui' => [
                                '',
                                'size-5',
                                'scheme-current',
                                'control-button',
                                'mod' => [
                                    'transparent',
                                    'round-half'
                                ]
                            ]
                        ]
                    ]) ?>
                <?php } ?>
            </div>
            <?php if ($arVisual['WIDE']) { ?>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    <?php if (!$arVisual['WIDE']) { ?>
            </div>
        </div>
    <?php } ?>
</div>
<?php include(__DIR__.'/parts/script.php') ?>