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

/**
 * @param $arSections
 */
$vChildrenRender = include(__DIR__ . '/parts/view.'.$arVisual['CHILDREN']['VIEW'].'.php');

?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'ns-bitrix',
        'c-catalog-section-list',
        'c-catalog-section-list-catalog-tile-4'
    ],
    'data' => [
        'children-view' => $arVisual['CHILDREN']['VIEW']
    ]
]) ?>
    <div class="catalog-section-list-items intec-grid intec-grid-wrap intec-grid-a-v-stretch">
        <?php foreach ($arResult['SECTIONS'] as $arSection) {

            $sId = $sTemplateId.'_'.$arSection['ID'];
            $sAreaId = $this->GetEditAreaId($sId);
            $this->AddEditAction($sId, $arSection['EDIT_LINK']);
            $this->AddDeleteAction($sId, $arSection['DELETE_LINK']);

            $arPicture = $arSection['PICTURE'];

            if ($arVisual['PICTURE']['SHOW']) {
                if (!empty($arPicture)) {
                    $arPicture = CFile::ResizeImageGet($arPicture, [
                        'width' => '900',
                        'height' => '900'
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
                        '1200-3' => $arVisual['COLUMNS'] >= 4,
                        '1024-2' => $arVisual['COLUMNS'] >= 3,
                        '600-1' => true
                    ]
                ], true)
            ]) ?>
                <div class="catalog-section-list-item-wrapper" id="<?= $sAreaId ?>">
                    <?php if ($arVisual['PICTURE']['SHOW'] && !empty($arPicture)) { ?>
                        <?= Html::tag('a', null, [
                            'class' => [
                                'catalog-section-list-item-picture',
                                'intec-image-effect'
                            ],
                            'href' => $arSection['SECTION_PAGE_URL'],
                            'target' => $arVisual['LINK']['BLANK'] ? '_blank' : null,
                            'title' => !empty($arPicture['TITLE']) ? $arPicture['TITLE'] : null,
                            'data' => [
                                'picture-size' => $arVisual['PICTURE']['SIZE'],
                                'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                'original' => $arVisual['LAZYLOAD']['USE'] ? $arPicture['SRC'] : null
                            ],
                            'style' => [
                                'background-image' => $arVisual['LAZYLOAD']['USE'] ? null : 'url(\''.$arPicture['SRC'].'\')'
                            ]
                        ]) ?>
                    <?php } ?>
                    <div class="catalog-section-list-item-text">
                        <div class="catalog-section-list-item-name">
                            <?= Html::tag('a', $arSection['NAME'], [
                                'class' => 'intec-cl-text-hover',
                                'href' => $arSection['SECTION_PAGE_URL'],
                                'target' => $arVisual['LINK']['BLANK'] ? '_blank' : null
                            ]) ?>
                        </div>
                        <?php if ($arVisual['CHILDREN']['SHOW'] && !empty($arSection['SECTIONS'])) {

                            $bExpandable = false;
                            $iCountSections = count($arSection['SECTIONS']);

                            if ($arVisual['CHILDREN']['COUNT']['USE'] && $iCountSections > $arVisual['CHILDREN']['COUNT']['VALUE'])
                                $bExpandable = true;

                        ?>
                            <?= Html::beginTag('div', [
                                'class' => 'catalog-section-list-item-children',
                                'data-role' => $bExpandable ? 'children' : null,
                                'data-expanded' => $bExpandable ? 'false' : null
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
                                        <?= Loc::getMessage('C_CATALOG_SECTION_LIST_CATALOG_TILE_4_TEMPLATE_BUTTON_SHOW') ?>
                                    </div>
                                    <div class="catalog-section-list-item-button-count">
                                        <?= $iCountSections - $arVisual['CHILDREN']['COUNT']['VALUE'] ?>
                                    </div>
                                <?= Html::endTag('div') ?>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            <?= Html::endTag('div') ?>
        <?php } ?>
    </div>
    <?php if ($arVisual['CHILDREN']['SHOW'])
        include(__DIR__.'/parts/script.php');
    ?>
<?= Html::endTag('div') ?>
