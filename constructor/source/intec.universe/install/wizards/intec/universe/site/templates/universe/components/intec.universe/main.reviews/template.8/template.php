<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
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

$sTag = $arVisual['LINK']['USE'] ? 'a' : 'div';

?>
<div class="widget c-reviews c-reviews-template-8-1" id="<?= $sTemplateId ?>">
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
                <?= Html::beginTag('div', [
                    'class' => 'widget-items',
                    'data' => [
                        'role' => 'container'
                    ]
                ]) ?>
                    <?php foreach ($arResult['ITEMS'] as $arItem) {

                        $sId = $sTemplateId.'_'.$arItem['ID'];
                        $sAreaId = $this->GetEditAreaId($sId);
                        $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                        $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                        $arData = $arItem['DATA'];
                        $arServices = [];
                        $arProjects = [];

                        $sPicture = $arItem['PREVIEW_PICTURE'];

                        if (empty($sPicture))
                            $sPicture = $arItem['DETAIL_PICTURE'];

                        if (!empty($sPicture)) {
                            $sPicture = CFile::ResizeImageGet($sPicture, [
                                    'width' => 150,
                                    'height' => 150
                                ], BX_RESIZE_IMAGE_PROPORTIONAL_ALT
                            );

                            if (!empty($sPicture))
                                $sPicture = $sPicture['src'];
                        }

                        if (empty($sPicture))
                            $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

                        $sText = $arItem['PREVIEW_TEXT'];

                        if (empty($sText))
                            $sText = $arItem['DETAIL_TEXT'];

                        if (empty($sText))
                            continue;

                    ?>
                        <div class="widget-item" id="<?= $sAreaId ?>">
                            <div class="widget-item-wrapper intec-grid intec-grid-768-wrap">
                                <div class="widget-item-main intec-grid-item">
                                    <div class="widget-item-main-wrapper intec-grid intec-grid-500-wrap">
                                        <div class="intec-grid-item-auto intec-grid-item-500-1">
                                            <?= Html::tag($sTag, '', [
                                                'href' => $sTag === 'a' ? $arItem['DETAIL_PAGE_URL'] : null,
                                                'class' => [
                                                    'widget-item-picture',
                                                    'intec-image-effect'
                                                ],
                                                'target' => $sTag === 'a' && $arVisual['LINK']['BLANK'] ? '_blank' : null,
                                                'data' => [
                                                    'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                                    'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                                                ],
                                                'style' => [
                                                    'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null
                                                ]
                                            ]) ?>
                                        </div>
                                        <div class="intec-grid-item">
                                            <?= Html::tag($sTag, $arItem['NAME'], [
                                                'href' => $sTag === 'a' ? $arItem['DETAIL_PAGE_URL'] : null,
                                                'class' => Html::cssClassFromArray([
                                                    'widget-item-name' => true,
                                                    'intec-cl-text' => true,
                                                    'intec-cl-text-light-hover' => $sTag === 'a'
                                                ], true),
                                                'target' => $sTag === 'a' && $arVisual['LINK']['BLANK'] ? '_blank' : null
                                            ]) ?>
                                            <?php if ($arVisual['POSITION']['SHOW'] && !empty($arData['POSITION'])) { ?>
                                                <div class="widget-item-position">
                                                    <?= $arData['POSITION'] ?>
                                                </div>
                                            <?php } ?>
                                            <?php if (
                                                    ($arVisual['SERVICES']['SHOW'] && !empty($arData['SERVICES'])) ||
                                                    ($arVisual['PROJECTS']['SHOW'] && !empty($arData['PROJECTS']))
                                            ) { ?>
                                                <div class="widget-item-additional">
                                                    <?php if ($arVisual['SERVICES']['SHOW'] && !empty($arData['SERVICES'])) { ?>
                                                        <div class="widget-item-additional-item">
                                                            <span class="widget-item-additional-item-name">
                                                                <?php if (count($arData['SERVICES']) > 1) { ?>
                                                                    <?= Loc::getMessage('C_MAIN_REVIEWS_TEMPLATE_8_TEMPLATE_SERVICES') ?>
                                                                <?php } else { ?>
                                                                    <?= Loc::getMessage('C_MAIN_REVIEWS_TEMPLATE_8_TEMPLATE_SERVICE') ?>
                                                                <?php } ?>
                                                            </span>
                                                            <?php foreach ($arData['SERVICES'] as $arService) {
                                                                $arServices[] = Html::tag('a', $arService['NAME'], [
                                                                    'href' => $arService['URL'],
                                                                    'class' => 'widget-item-additional-item-link',
                                                                    'target' => '_blank'
                                                                ]);
                                                            } ?>
                                                            <?= implode(', ', $arServices) ?>
                                                        </div>
                                                    <?php } ?>
                                                    <?php if ($arVisual['PROJECTS']['SHOW'] && !empty($arData['PROJECTS'])) { ?>
                                                        <div class="widget-item-additional-item">
                                                            <span class="widget-item-additional-item-name">
                                                                <?php if (count($arData['PROJECTS']) > 1) { ?>
                                                                    <?= Loc::getMessage('C_MAIN_REVIEWS_TEMPLATE_8_TEMPLATE_PROJECTS') ?>
                                                                <?php } else { ?>
                                                                    <?= Loc::getMessage('C_MAIN_REVIEWS_TEMPLATE_8_TEMPLATE_PROJECT') ?>
                                                                <?php } ?>
                                                            </span>
                                                            <?php foreach ($arData['PROJECTS'] as $arProject) {
                                                                $arProjects[] = Html::tag('a', $arProject['NAME'], [
                                                                    'href' => $arProject['URL'],
                                                                    'class' => 'widget-item-additional-item-link',
                                                                    'target' => '_blank'
                                                                ]);
                                                            } ?>
                                                            <?= implode(', ', $arProjects) ?>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            <?php } ?>
                                            <div class="widget-item-description">
                                                <?= $sText ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($arVisual['VIDEO']['SHOW'] && !empty($arData['VIDEO'])) { ?>
                                    <div class="widget-item-video-wrap intec-grid-item-auto intec-grid-item-768-1">
                                        <?= Html::beginTag('div', [
                                            'class' => 'widget-item-video',
                                            'style' => [
                                                'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$arData['VIDEO'][$arVisual['VIDEO']['QUALITY']].'\')' : null
                                            ],
                                            'data' => [
                                                'role' => 'video',
                                                'src' => $arData['VIDEO']['iframe'],
                                                'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                                'original' => $arVisual['LAZYLOAD']['USE'] ? $arData['VIDEO'][$arVisual['VIDEO']['QUALITY']] : null
                                            ]
                                        ]) ?>
                                            <div class="intec-aligner"></div>
                                            <svg class="widget-item-video-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                                <path fill="#FFF" d="M216 354.9V157.1c0-10.7 13-16.1 20.5-8.5l98.3 98.9c4.7 4.7 4.7 12.2 0 16.9l-98.3 98.9c-7.5 7.7-20.5 2.3-20.5-8.4zM256 8c137 0 248 111 248 248S393 504 256 504 8 393 8 256 119 8 256 8zm0 48C145.5 56 56 145.5 56 256s89.5 200 200 200 200-89.5 200-200S366.5 56 256 56z"></path>
                                            </svg>
                                        <?= Html::endTag('div') ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                <?= Html::endTag('div') ?>
            </div>
            <?php if ($arBlocks['FOOTER']['SHOW']) { ?>
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
            <?php } ?>
        </div>
    </div>
</div>
<?php if ($arVisual['VIDEO']['SHOW']) include(__DIR__.'/parts/script.php') ?>