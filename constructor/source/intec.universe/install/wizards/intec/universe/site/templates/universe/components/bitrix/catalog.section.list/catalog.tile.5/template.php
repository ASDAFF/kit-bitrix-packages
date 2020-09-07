<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 */

$this->setFrameMode(true);

if (empty($arResult['SECTIONS']))
    return;

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arVisual = $arResult['VISUAL'];

$vChildrenRender = include(__DIR__.'/parts/view.'.$arVisual['CHILDREN']['VIEW'].'.php');

?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'ns-bitrix',
        'c-catalog-section-list',
        'c-catalog-section-list-catalog-tile-5'
    ]
]) ?>
    <div class="catalog-section-list-items intec-grid intec-grid-wrap intec-grid-a-v-sretch">
        <?php foreach ($arResult['SECTIONS'] as $arSection) {

            $arPicture = $arSection['PICTURE'];

            if ($arVisual['PICTURE']['SHOW']) {
                if (!empty($arPicture)) {
                    $arPicture = CFile::ResizeImageGet($arPicture, [
                        'width' => 160,
                        'height' => 160
                    ], BX_RESIZE_IMAGE_PROPORTIONAL_ALT);

                    if (!empty($arPicture))
                        $arPicture = [
                            'SRC' => $arPicture['src'],
                            'TITLE' => $arSection['PICTURE']['TITLE']
                        ];
                }

                if (empty($arPicture)) {
                    $arPicture = [
                        'SRC' => SITE_TEMPLATE_PATH . '/images/picture.missing.png',
                        'TITLE' => null
                    ];
                }
            }

        ?>
            <?= Html::beginTag('div', [
                'class' => Html::cssClassFromArray([
                    'catalog-section-list-item' => true,
                    'intec-grid-item' => [
                        $arVisual['COLUMNS'] => true,
                        '1200-2' => $arVisual['COLUMNS'] >= 3,
                        '768-1' => true
                    ]
                ], true),
            ]) ?>
                <?= Html::beginTag('div', [
                    'class' => Html::cssClassFromArray([
                        'catalog-section-list-item-wrapper' => true,
                        'intec-grid' => [
                                '' => true,
                                'a-v-center' => !$arVisual['CHILDREN']['SHOW'] || empty($arSection['SECTIONS']),
                                '400-wrap' => true
                        ]
                    ], true)
                ]) ?>
                    <?php if ($arVisual['PICTURE']['SHOW']) { ?>
                        <div class="intec-grid-item-auto intec-grid-item-400-1">
                            <?= Html::tag('a', null, [
                                'class' => [
                                    'catalog-section-list-item-picture',
                                    'intec-image-effect'
                                ],
                                'href' => $arSection['SECTION_PAGE_URL'],
                                'target' => $arVisual['LINK']['BLANK'] ? '_blank' : null,
                                'data' => [
                                    'picture-size' => $arVisual['PICTURE']['SIZE'],
                                    'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                    'original' => $arVisual['LAZYLOAD']['USE'] ? $arPicture['SRC'] : null
                                ],
                                'style' => [
                                    'background-image' => $arVisual['LAZYLOAD']['USE'] ? null : 'url(\''.$arPicture['SRC'].'\')'
                                ]
                            ]) ?>
                        </div>
                    <?php } ?>
                    <div class="intec-grid-item intec-grid-item-400-1">
                        <?= Html::tag('a', $arSection['NAME'], [
                            'class' => [
                                'catalog-section-list-item-name',
                                'intec-cl-text-hover'
                            ],
                            'href' => $arSection['SECTION_PAGE_URL'],
                            'target' => $arVisual['LINK']['BLANK'] ? '_blank' : null
                        ]) ?>
                        <?php if ($arVisual['CHILDREN']['SHOW'] && !empty($arSection['SECTIONS'])) {

                            $bExpandable = false;
                            $iCountSections = count($arSection['SECTIONS']);

                            if ($arVisual['CHILDREN']['COUNT']['USE'] && $iCountSections > $arVisual['CHILDREN']['COUNT']['VALUE'])
                                $bExpandable = true;

                        ?>
                            <?= Html::beginTag('div', [
                                'class' => 'catalog-section-list-item-children',
                                'data-role' => $bExpandable ? 'children' : null,
                                'data-expanded' => $bExpandable ? 'false' : null,
                                'data-children-view' => $arVisual['CHILDREN']['VIEW']
                            ]) ?>
                                <?php $vChildrenRender($arSection['SECTIONS']) ?>
                            <?= Html::endTag('div') ?>
                            <?php if ($bExpandable) { ?>
                                <?= Html::beginTag('div', [
                                    'class' => [
                                        'catalog-section-list-item-button',
                                        'intec-cl-border-hover'
                                    ],
                                    'data' => [
                                        'role' => 'button',
                                        'expanded' => 'false'
                                    ]
                                ]) ?>
                                    <div class="catalog-section-list-item-button-decoration"></div>
                                    <div class="catalog-section-list-item-button-text" data-role="button.text">
                                        <?= Loc::getMessage('C_CATALOG_SECTION_LIST_CATALOG_TILE_5_TEMPLATE_BUTTON_SHOW') ?>
                                    </div>
                                    <div class="catalog-section-list-item-button-count">
                                        <?= $iCountSections - $arVisual['CHILDREN']['COUNT']['VALUE'] ?>
                                    </div>
                                <?= Html::endTag('div') ?>
                            <?php } ?>
                        <?php } ?>
                    </div>
                <?= Html::endTag('div') ?>
            <?= Html::endTag('div') ?>
        <?php } ?>
    </div>
    <?php if ($arVisual['CHILDREN']['SHOW'])
        include(__DIR__.'/parts/script.php');
    ?>
<?= Html::endTag('div') ?>