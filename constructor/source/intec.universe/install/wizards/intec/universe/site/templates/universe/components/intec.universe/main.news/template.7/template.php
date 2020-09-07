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

$arVisual = $arResult['VISUAL'];
$arBlocks = $arResult['BLOCKS'];

/**
 * @var Closure $tagsRender($arItem)
 */
$tagsRender = include(__DIR__.'/parts/tags.php');

$sTag = $arVisual['LINK']['USE'] ? 'a' : 'div';

?>
<div class="widget c-news c-news-template-7" id="<?= $sTemplateId ?>">
    <div class="intec-content intec-content-visible">
        <div class="intec-content-wrapper">
            <?php if ($arBlocks['HEADER']['SHOW'] || $arBlocks['DESCRIPTION']['SHOW']) { ?>
                <div class="widget-header">
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
                </div>
            <?php } ?>
            <div class="widget-content">
                <div class="widget-items intec-grid intec-grid-wrap" data-role="items">
                    <?php foreach ($arResult['ITEMS'] as $arItem) {

                        $sId = $sTemplateId.'_'.$arItem['ID'];
                        $sAreaId = $this->GetEditAreaId($sId);
                        $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                        $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                    ?>
                        <div class="widget-item intec-grid-item-1">
                            <div class="widget-item-wrapper" id="<?= $sAreaId ?>">
                                <div class="widget-item-name-wrap">
                                    <?= Html::tag($sTag, $arItem['NAME'], [
                                        'class' => Html::cssClassFromArray([
                                            'widget-item-name' => true,
                                            'intec-cl-text-hover' => $arVisual['LINK']['USE']
                                        ], true),
                                        'href' => $arVisual['LINK']['USE'] ? $arItem['DETAIL_PAGE_URL'] : null,
                                        'target' => $arVisual['LINK']['USE'] && $arVisual['LINK']['BLANK'] ? '_blank' : null
                                    ]) ?>
                                </div>
                                <?php if ($arResult['TAGS']['SHOW'] && !empty($arItem['DATA']['TAGS'])) {
                                    $tagsRender($arItem['DATA']['TAGS']);
                                } ?>
                                <div class="widget-item-date">
                                    <?= $arItem['DATA']['DATE'] ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <?php if ($arResult['TAGS']['SHOW'] && $arResult['TAGS']['MODE'] === 'active')
        include(__DIR__.'/parts/form.php');
    ?>
</div>
