<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use intec\template\Properties;
use intec\core\bitrix\Component;
use intec\core\helpers\Html;

/**
 * @var $arResult
 */

$this->setFrameMode(true);

if (!Loader::includeModule('intec.core'))
    return;

if (empty($arResult['SECTIONS']))
    return;

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));
$sStub = Properties::get('template-images-lazyload-stub');

$arVisual = $arResult['VISUAL'];

?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'ns-bitrix',
        'c-catalog-section-list',
        'c-catalog-section-list-catalog-tile-2'
    ],
    'data' => [
        'borders' => $arVisual['BORDERS'] ? 'true' : 'false',
        'columns' => $arVisual['COLUMNS'],
        'wide' => $arVisual['WIDE'] ? 'true' : 'false'
    ]
]) ?>
    <?= Html::beginTag('div', [
        'class' => [
            'catalog-section-list-items',
            'intec-grid' => [
                '',
                'wrap',
                'a-h-start',
                'a-v-start'
            ]
        ]
    ]) ?>
        <?php foreach ($arResult['SECTIONS'] as $arSection) {

            $sId = $sTemplateId.'_'.$arSection['ID'];
            $sAreaId = $this->GetEditAreaId($sId);
            $this->AddEditAction($sId, $arSection['EDIT_LINK']);
            $this->AddDeleteAction($sId, $arSection['DELETE_LINK']);

            $arPicture = $arSection['PICTURE'];

            if (!empty($arPicture)) {
                $arPicture = CFile::ResizeImageGet($arPicture, [
                    'width' => 200,
                    'height' => 200
                ], BX_RESIZE_IMAGE_PROPORTIONAL);

                if (!empty($arPicture))
                    $arPicture = [
                        'ALT' => $arSection['PICTURE']['ALT'],
                        'SRC' => $arPicture['src'],
                        'TITLE' => $arSection['PICTURE']['TITLE']
                    ];
            }

            if (empty($arPicture)) {
                $arPicture = [
                    'ALT' => null,
                    'SRC' => SITE_TEMPLATE_PATH.'/images/picture.missing.png',
                    'TITLE' => null
                ];
            }
        ?>
            <?= Html::beginTag('div', [
                'class' => Html::cssClassFromArray([
                    'catalog-section-list-item' => true,
                    'intec-grid-item' => [
                        $arVisual['COLUMNS'] => true,
                        '700-2' => $arVisual['WIDE'] && $arVisual['COLUMNS'] > 2,
                        '900-3' => $arVisual['WIDE'] && $arVisual['COLUMNS'] > 3,
                        '950-2' => !$arVisual['WIDE'] && $arVisual['COLUMNS'] > 2,
                        '1050-4' => $arVisual['WIDE'] && $arVisual['COLUMNS'] > 4,
                        '1200-3' => !$arVisual['WIDE'] && $arVisual['COLUMNS'] > 3
                    ]
                ], true)
            ]) ?>
                <div id="<?= $sAreaId ?>" class="catalog-section-list-item-wrapper">
                    <div class="catalog-section-list-item-wrapper-2">
                        <?= Html::beginTag('a', [
                            'class' => 'catalog-section-list-item-image',
                            'href' => $arSection['SECTION_PAGE_URL']
                        ]) ?>
                            <div class="catalog-section-list-item-image-wrapper intec-image intec-image-effect">
                                <div class="intec-aligner"></div>
                                <?= Html::img($arVisual['LAZYLOAD']['USE'] ? $sStub : $arPicture['SRC'], [
                                    'alt' => $arPicture['ALT'],
                                    'title' => $arPicture['TITLE'],
                                    'loading' => 'lazy',
                                    'data' => [
                                        'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                        'original' => $arVisual['LAZYLOAD']['USE'] ? $arPicture['SRC'] : null
                                    ]
                                ]) ?>
                            </div>
                        <?= Html::endTag('a') ?>
                        <div class="catalog-section-list-item-information">
                            <?= Html::beginTag('a', [
                                'class' => [
                                    'catalog-section-list-item-title',
                                    'intec-cl-text-hover'
                                ],
                                'href' => $arSection['SECTION_PAGE_URL']
                            ]) ?>
                                <span class="catalog-section-list-item-title-text">
                                    <?= $arSection['NAME'] ?>
                                </span>
                                <?php if ($arVisual['ELEMENTS']['QUANTITY']) { ?>
                                    <span class="catalog-section-list-item-title-elements">
                                        (<?= $arSection['ELEMENT_CNT'] ?>)
                                    </span>
                                <?php } ?>
                            <?= Html::endTag('a') ?>
                        </div>
                    </div>
                </div>
            <?= Html::endTag('div') ?>
        <?php } ?>
    <?= Html::endTag('div') ?>
<?= Html::endTag('div') ?>