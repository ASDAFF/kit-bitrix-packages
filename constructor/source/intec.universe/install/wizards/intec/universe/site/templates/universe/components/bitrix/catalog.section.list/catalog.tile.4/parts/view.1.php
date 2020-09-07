<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;
use intec\core\helpers\Type;

/**
 * @var array $arVisual
 */

?>
<?php return function (&$arSections) use (&$arVisual) { ?>
    <?php if (empty($arSections) || !Type::isArray($arSections)) return ?>
    <?php $iCount = 0 ?>
    <?php foreach ($arSections as $arSection) { ?>
        <?php $iCount++ ?>
        <?= Html::beginTag('div', [
            'class' => 'catalog-section-list-item-child',
            'data-role' => $arVisual['CHILDREN']['COUNT']['USE'] && $iCount > $arVisual['CHILDREN']['COUNT']['VALUE'] ? 'hidden' : null,
            'data-children-count' => $arVisual['CHILDREN']['ELEMENTS'] ? 'true' : 'false',
            'style' => [
                'display' => $arVisual['CHILDREN']['COUNT']['USE'] && $iCount > $arVisual['CHILDREN']['COUNT']['VALUE'] ? 'none' : null
            ]
        ]) ?>
            <div class="intec-grid intec-grid-nowrap">
                <div class="catalog-section-list-item-child-name-wrap intec-grid-item-auto">
                    <?= Html::tag('a', $arSection['NAME'], [
                        'class' => [
                            'catalog-section-list-item-child-name',
                            'intec-cl-text-hover'
                        ],
                        'href' => $arSection['SECTION_PAGE_URL'],
                        'target' => $arVisual['LINK']['BLANK'] ? '_blank' : null
                    ]) ?>
                </div>
                <?php if ($arVisual['CHILDREN']['ELEMENTS']) { ?>
                    <div class="catalog-section-list-item-child-count-wrap intec-grid-item-auto">
                        <div class="catalog-section-list-item-child-count">
                            <?= $arSection['ELEMENT_CNT'] ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?= Html::endTag('div') ?>
    <?php } ?>
<?php } ?>