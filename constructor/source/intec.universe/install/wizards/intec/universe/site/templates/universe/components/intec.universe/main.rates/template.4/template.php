<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\FileHelper;
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
include(__DIR__.'/parts/items.php');

$sIcon = FileHelper::getFileData(__DIR__.'/svg/icon.svg');

?>
<div class="widget c-rates c-rates-template-4" id="<?= $sTemplateId ?>">
    <div class="widget-wrapper intec-content intec-content-visible">
        <div class="widget-wrapper-2 intec-content-wrapper">
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
                <?php $vItems($arResult['ITEMS']); ?>
            </div>
            <?php if ($arVisual['PRICE_LIST']['SHOW']) { ?>
                <div  class="widget-price-list-wrap">
                    <a href="<?= $arResult['PRICE_LIST']['LINK'] ?>" class="widget-price-list">
                        <span class="widget-price-list-icon intec-cl-background intec-cl-background-light-hover">
                            <?= $sIcon ?>
                        </span>
                        <div class="widget-price-list-text">
                            <?= $arVisual['PRICE_LIST']['BUTTON_TEXT'] ?>
                        </div>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>
</div>