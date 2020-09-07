<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\bitrix\Component;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 */

$this->setFrameMode(true);

if (empty($arResult['ITEM']))
    return;

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arBlocks = $arResult['BLOCKS'];
$arVisual = $arResult['VISUAL'];

$sId = $sTemplateId.'_'.$arResult['ITEM']['ID'];
$sAreaId = $this->GetEditAreaId($sId);
$this->AddEditAction($sId, $arResult['ITEM']['EDIT_LINK']);
$this->AddDeleteAction($sId, $arResult['ITEM']['DELETE_LINK']);

$sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';
if (!empty($arResult['PICTURE']['SRC'])) {
    $sPicture = $arResult['PICTURE']['SRC'];
}
?>
<div class="widget c-video c-video-template-1" id="<?= $sTemplateId ?>">
    <?php if ($arBlocks['HEADER']['SHOW'] || $arBlocks['DESCRIPTION']['SHOW']) { ?>
        <div class="widget-header intec-content">
            <div class="intec-content-wrapper">
                <?php if ($arBlocks['HEADER']['SHOW']) { ?>
                    <div class="widget-title align-<?= $arBlocks['HEADER']['POSITION'] ?>">
                        <?= Html::stripTags($arBlocks['HEADER']['TEXT'], ['br']) ?>
                    </div>
                <?php } ?>
                <?php if ($arBlocks['DESCRIPTION']['SHOW']) { ?>
                    <div class="widget-description align-<?= $arBlocks['DESCRIPTION']['POSITION'] ?>">
                        <?= Html::stripTags($arBlocks['DESCRIPTION']['TEXT'], ['br']) ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
    <div class="widget-content">
        <?php if (!$arVisual['WIDE']) { ?>
            <div class="widget-content-wrapper intec-content intec-content-visible">
                <div class="widget-content-wrapper-2 intec-content-wrapper">
        <?php } ?>
        <div class="widget-item" id="<?= $sAreaId ?>" data-role="item">
            <?= Html::beginTag('div', [
                'class' => 'widget-item-wrapper',
                'title' => $arResult['ITEM']['NAME'],
                'style' => [
                    'height' => $arVisual['HEIGHT'] !== 'auto' ? $arVisual['HEIGHT'].'px' : null,
                    'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null
                ],
                'data' => [
                    'mode' => $arVisual['HEIGHT'] === 'auto' ? 'auto' : 'fixed',
                    'theme' => $arVisual['THEME'],
                    'rounded' => $arVisual['ROUNDED'] ? 'true' : 'false',
                    'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                    'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                ],
                'data-shadow' => $arVisual['SHADOW']['USE'] ? $arVisual['SHADOW']['MODE'] : null,
                'data-src' => !empty($arResult['LINK']['embed']) ? $arResult['LINK']['embed'] : null,
                'data-stellar-background-ratio' => $arVisual['PARALLAX']['USE'] ? $arVisual['PARALLAX']['RATIO'] : null
            ]) ?>
                <?php if ($arVisual['FADE']) { ?>
                    <div class="widget-item-fade"></div>
                <?php } ?>
                <svg class="widget-item-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" >
                    <path d="M216 354.9V157.1c0-10.7 13-16.1 20.5-8.5l98.3 98.9c4.7 4.7 4.7 12.2 0 16.9l-98.3 98.9c-7.5 7.7-20.5 2.3-20.5-8.4zM256 8c137 0 248 111 248 248S393 504 256 504 8 393 8 256 119 8 256 8zm0 48C145.5 56 56 145.5 56 256s89.5 200 200 200 200-89.5 200-200S366.5 56 256 56z"></path>
                </svg>
            <?= Html::endTag('div') ?>
        </div>
        <?php if (!$arVisual['WIDE']) { ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<?php if (!empty($arResult['LINK']) && !defined('EDITOR')) include(__DIR__.'/parts/script.php') ?>