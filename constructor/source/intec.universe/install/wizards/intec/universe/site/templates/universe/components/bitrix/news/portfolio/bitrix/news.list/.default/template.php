<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\bitrix\Component;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 */

$this->setFrameMode(true);

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arVisual = $arResult['VISUAL']

?>
<div class="ns-bitrix c-news-list c-news-list-default" id="<?= $sTemplateId ?>">
    <?php if (!$arVisual['WIDE']) { ?>
        <div class="intec-content intec-content-visible">
            <div class="intec-content-wrapper">
    <?php } ?>
        <div class="widget-content">
            <?php if ($arVisual['TABS']['USE']) {
                include(__DIR__.'/parts/tabs.php');
            } ?>
            <?= Html::beginTag('div', [
                'class' => [
                    'widget-items',
                    'intec-grid' => [
                        '',
                        'wrap'
                    ]
                ],
                'data' => [
                    'wide' => $arVisual['WIDE'] ? 'true' : 'false',
                    'grid' => $arVisual['COLUMNS']
                ]
            ]) ?>
                <?php foreach ($arResult['ITEMS'] as $arItem) {

                    $sId = $sTemplateId.'_'.$arItem['ID'];
                    $sAreaId = $this->GetEditAreaId($sId);
                    $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                    $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                    $arData = $arItem['DATA'];

                    $sPicture = $arItem['PREVIEW_PICTURE'];

                    if (empty($sPicture))
                        $sPicture = $arItem['DETAIL_PICTURE'];

                    if (!empty($sPicture)) {
                        $sPicture = CFile::ResizeImageGet(
                            $sPicture, [
                                'width' => 700,
                                'height' => 700
                            ],
                            BX_RESIZE_IMAGE_PROPORTIONAL_ALT
                        );

                        if (!empty($sPicture))
                            $sPicture = $sPicture['src'];
                    }

                    if (empty($sPicture))
                        $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

                ?>
                    <?= Html::beginTag('div', [
                        'class' => Html::cssClassFromArray([
                            'widget-item' => true,
                            'intec-grid-item' => [
                                $arVisual['COLUMNS'] => true,
                                '1200-4' => $arVisual['COLUMNS'] >= 5,
                                '1024-3' => $arVisual['COLUMNS'] >= 4,
                                '768-2' => $arVisual['COLUMNS'] >= 3,
                                '500-1' => true
                            ]
                        ], true),
                        'data' => [
                            'role' => 'item',
                            'type' => $arData['TYPE'],
                            'active' => 'true'
                        ]
                    ]) ?>
                        <?= Html::beginTag('a', [
                            'id' => $sAreaId,
                            'class' => 'widget-item-wrapper',
                            'href' => $arItem['DETAIL_PAGE_URL']
                        ]) ?>
                            <?= Html::tag('div', '', [
                                'class' => 'widget-item-picture',
                                'style' => [
                                    'background-image' => 'url("'.$sPicture.'")'
                                ]
                            ]) ?>
                            <div class="widget-item-fade"></div>
                            <div class="widget-item-name">
                                <?= $arItem['NAME'] ?>
                            </div>
                        <?= Html::endTag('a') ?>
                    <?= Html::endTag('div') ?>
                <?php } ?>
            <?= Html::endTag('div') ?>
        </div>
    <?php if (!$arVisual['WIDE']) { ?>
            </div>
        </div>
    <?php } ?>
</div>
<?php if ($arVisual['TABS']['USE']) include(__DIR__.'/parts/script.php') ?>