<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use intec\core\bitrix\Component;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;

/**
 * @var $arResult
 */

$this->setFrameMode(true);

if (!Loader::includeModule('intec.core'))
    return;

if (empty($arResult['SECTIONS']))
    return;

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arVisual = $arResult['VISUAL'];

?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'ns-bitrix',
        'c-catalog-section-list',
        'c-catalog-section-list-services-tile-1'
    ],
    'data' => [
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
                'a-v-start',
                'i-10'
            ]
        ]
    ]) ?>
        <?php foreach ($arResult['SECTIONS'] as $arSection) {

            $sId = $sTemplateId.'_'.$arSection['ID'];
            $sAreaId = $this->GetEditAreaId($sId);
            $this->AddEditAction($sId, $arSection['EDIT_LINK']);
            $this->AddDeleteAction($sId, $arSection['DELETE_LINK']);

            $arSection['DESCRIPTION'] = Html::stripTags($arSection['DESCRIPTION']);
            $arPicture = $arSection['PICTURE'];

            if (!empty($arPicture)) {
                $arPicture = CFile::ResizeImageGet($arPicture, [
                    'width' => 600,
                    'height' => 600
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
                        '1200-3' => $arVisual['WIDE'] && $arVisual['COLUMNS'] >= 4,
                        '900-2' => $arVisual['WIDE'] && $arVisual['COLUMNS'] >= 3,
                        '1200-2' => !$arVisual['WIDE'] && $arVisual['COLUMNS'] >= 3,
                        '850-1' => !$arVisual['WIDE'],
                        '720-2' => !$arVisual['WIDE'],
                        '650-1' => true
                    ]
                ], true)
            ]) ?>
                <?= Html::beginTag('a', [
                    'id' => $sAreaId,
                    'href' => $arSection['SECTION_PAGE_URL'],
                    'class' => 'catalog-section-list-item-wrapper',
                    'data' => [
                        'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                        'original' => $arVisual['LAZYLOAD']['USE'] ? $arPicture['SRC'] : null
                    ],
                    'style' => [
                        'background-image' => $arVisual['LAZYLOAD']['USE'] ? null : 'url(\''.$arPicture['SRC'].'\')'
                    ]
                ]) ?>
                    <div class="catalog-section-list-item-overlay"></div>
                    <div class="catalog-section-list-item-content">
                        <div class="catalog-section-list-item-information">
                            <div class="catalog-section-list-item-name intec-cl-background">
                                <?= $arSection['NAME'] ?>
                            </div>
                            <?php if (!empty($arSection['DESCRIPTION'])) { ?>
                                <div class="catalog-section-list-item-description">
                                    <?= StringHelper::truncate($arSection['DESCRIPTION'], 75) ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?= Html::endTag('a') ?>
            <?= Html::endTag('div') ?>
        <?php } ?>
    <?= Html::endTag('div') ?>
<?= Html::endTag('div') ?>