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

$bHide = $arVisual['HIDE'] && count($arResult['ITEMS']) > 4;
$iCounter = 0;

?>
<div class="widget c-advantages c-advantages-template-28" id="<?= $sTemplateId ?>">
    <div class="intec-content">
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
            <div class="widget-content" data-role="container" data-expanded="false">
                <?= Html::beginTag('div', [
                    'class' => [
                        'widget-items',
                        'intec-grid' => [
                            '',
                            'wrap',
                            'a-v-stretch',
                            'i-h-15',
                            'i-v-25'
                        ]
                    ]
                ]) ?>
                    <?php foreach ($arResult['ITEMS'] as $arItem) {

                        $sId = $sTemplateId.'_'.$arItem['ID'];
                        $sAreaId = $this->GetEditAreaId($sId);
                        $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                        $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                        $iCounter++;

                        $sPicture = $arItem['PREVIEW_PICTURE'];

                        if (empty($sPicture))
                            $sPicture = $arItem['DETAIL_PICTURE'];

                        if (!empty($sPicture)) {
                            $sPicture = CFile::ResizeImageGet($sPicture, [
                                'width' => 120,
                                'height' => 120
                            ], BX_RESIZE_IMAGE_PROPORTIONAL_ALT);

                            if (!empty($sPicture))
                                $sPicture = $sPicture['src'];
                        }

                    ?>
                        <?= Html::beginTag('div', [
                            'class' => [
                                'widget-item',
                                'intec-grid-item' => [
                                    '2',
                                    '768-1'
                                ]
                            ],
                            'data' => [
                                'role' => 'item',
                                'action' => $bHide && $iCounter > 4 ? 'hide' : 'none'
                            ],
                            'style' => [
                                'display' => $bHide && $iCounter > 4 ? 'none' : null
                            ]
                        ]) ?>
                            <div class="widget-item-wrapper" id="<?= $sAreaId ?>">
                                <div class="intec-grid">
                                    <div class="widget-item-picture-wrap intec-grid-item-auto">
                                        <?php if (!empty($sPicture)) { ?>
                                            <?= Html::tag('div', '', [
                                                'class' => [
                                                    'widget-item-picture',
                                                    'intec-image-effect'
                                                ],
                                                'data' => [
                                                    'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                                    'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                                                ],
                                                'style' => [
                                                    'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null
                                                ]
                                            ]) ?>
                                        <?php } ?>
                                    </div>
                                    <div class="intec-grid-item">
                                        <div class="widget-item-name">
                                            <?= $arItem['NAME'] ?>
                                        </div>
                                        <?php if (!empty($arItem['PREVIEW_TEXT'])) { ?>
                                            <div class="widget-item-description">
                                                <?= $arItem['PREVIEW_TEXT'] ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        <?= Html::endTag('div') ?>
                    <?php } ?>
                <?= Html::endTag('div') ?>
            </div>
            <?php if ($bHide) { ?>
                <div class="widget-buttons">
                    <?= Html::tag('div', Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_27_TEMPLATE_BUTTON_SHOW'), [
                        'class' => [
                            'widget-button',
                            'intec-cl-border',
                            'intec-cl-background-hover'
                        ],
                        'data' => [
                            'role' => 'button'
                        ]
                    ]) ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php if ($bHide) include(__DIR__.'/parts/script.php') ?>