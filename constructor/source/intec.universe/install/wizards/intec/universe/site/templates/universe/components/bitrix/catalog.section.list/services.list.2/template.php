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
        'c-catalog-section-list-services-list-2'
    ],
    'data' => [
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
                'a-v-center',
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
                    'width' => 350,
                    'height' => 350
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
                        '1050-1' => !$arVisual['WIDE'],
                        '1150-2' => $arVisual['WIDE'] && $arVisual['COLUMNS'] >= 3,
                        '900-1' => $arVisual['WIDE'],
                    ]
                ], true)
            ]) ?>
                <div id="<?= $sAreaId ?>" class="catalog-section-list-item-wrapper">
                    <div class="catalog-section-list-item-wrapper-2 intec-grid intec-grid-nowrap intec-grid-a-v-center intec-grid-a-h-start intec-grid-i-h-10">
                        <div class="catalog-section-list-item-picture intec-grid-item-auto">
                            <?= Html::tag('a', null, [
                                'href' => $arSection['SECTION_PAGE_URL'],
                                'class' => 'intec-image-effect',
                                'data' => [
                                    'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                    'original' => $arVisual['LAZYLOAD']['USE'] ? $arPicture['SRC'] : null
                                ],
                                'style' => [
                                    'background-image' => $arVisual['LAZYLOAD']['USE'] ? null : 'url(\''.$arPicture['SRC'].'\')',
                                    'border-radius' => $arVisual['ROUNDING']['USE'] ? ($arVisual['ROUNDING']['VALUE'] / 2).'%' : null
                                ]
                            ]) ?>
                        </div>
                        <div class="catalog-section-list-item-information intec-grid-item">
                            <?= Html::tag('a', $arSection['NAME'], [
                                'class' => 'catalog-section-list-item-name',
                                'href' => $arSection['SECTION_PAGE_URL']
                            ]) ?>
                            <?php if (!empty($arSection['DESCRIPTION'])) { ?>
                                <div class="catalog-section-list-item-description">
                                    <?= StringHelper::truncate($arSection['DESCRIPTION'], 120) ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?= Html::endTag('div') ?>
        <?php } ?>
    <?= Html::endTag('div') ?>
<?= Html::endTag('div') ?>