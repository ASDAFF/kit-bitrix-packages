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
        'c-catalog-section-list-services-list-3'
    ],
    'data' => [
        'wide' => $arVisual['WIDE'] ? 'true' : 'false'
    ]
]) ?>
    <div class="catalog-section-list-items">
        <?php foreach ($arResult['SECTIONS'] as $arSection) {

            $sId = $sTemplateId.'_'.$arSection['ID'];
            $sAreaId = $this->GetEditAreaId($sId);
            $this->AddEditAction($sId, $arSection['EDIT_LINK']);
            $this->AddDeleteAction($sId, $arSection['DELETE_LINK']);

            $arSection['DESCRIPTION'] = Html::stripTags($arSection['DESCRIPTION']);
            $arPicture = $arSection['PICTURE'];

            if (!empty($arPicture)) {
                $arPicture = CFile::ResizeImageGet($arPicture, [
                    'width' => 450,
                    'height' => 450
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
            <div class="catalog-section-list-item">
                <div id="<?= $sAreaId ?>" class="catalog-section-list-item-wrapper">
                    <div class="catalog-section-list-item-picture">
                        <?= Html::tag('a', null, [
                            'href' => $arSection['SECTION_PAGE_URL'],
                            'class' => 'intec-image-effect',
                            'data' => [
                                'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                'original' => $arVisual['LAZYLOAD']['USE'] ? $arPicture['SRC'] : null
                            ],
                            'style' => [
                                'background-image' => $arVisual['LAZYLOAD']['USE'] ? null : 'url(\''.$arPicture['SRC'].'\')'
                            ]
                        ]) ?>
                    </div>
                    <div class="catalog-section-list-item-information">
                        <?= Html::tag('a', $arSection['NAME'], [
                            'class' => 'catalog-section-list-item-name',
                            'href' => $arSection['SECTION_PAGE_URL']
                        ]) ?>
                        <?php if (!empty($arSection['DESCRIPTION'])) { ?>
                            <div class="catalog-section-list-item-description">
                                <?= StringHelper::truncate($arSection['DESCRIPTION'], 300) ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="intec-ui-clear"></div>
                </div>
            </div>
        <?php } ?>
    </div>
<?= Html::endTag('div') ?>