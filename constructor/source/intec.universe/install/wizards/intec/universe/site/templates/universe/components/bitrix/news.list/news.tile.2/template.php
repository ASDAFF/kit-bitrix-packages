<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use intec\core\bitrix\Component;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 */

$this->setFrameMode(true);

if (empty($arResult['ITEMS']))
    return;

if (!Loader::includeModule('intec.core'))
    return;

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arVisual = $arResult['VISUAL'];

/**
 * @var Closure $tagsRender($arData)
 * @var Closure $$viewDefaultRender($arItem)
 * @var Closure $$viewBigRender($arItem)
 */
$tagsRender = include(__DIR__.'/parts/tags.php');
$viewDefaultRender = include(__DIR__.'/parts/view.default.php');
$viewBigRender = include(__DIR__.'/parts/view.big.php');

$bFirst = $arVisual['VIEW'] === 'big';

?>
<div class="ns-bitrix c-news-list c-news-list-tile-2" id="<?= $sTemplateId ?>">
    <div class="intec-content intec-content-visible">
        <div class="intec-content-wrapper">
            <?php if ($arVisual['NAVIGATION']['SHOW']['TOP']) { ?>
                <div data-pagination-num="<?= $arResult['NAVIGATION']['NUMBER'] ?>">
                    <!-- pagination-container -->
                    <?= $arResult['NAV_STRING'] ?>
                    <!-- pagination-container -->
                </div>
            <?php } ?>
            <div class="news-list-content">
                <?= Html::beginTag('div', [
                    'class' => [
                        'intec-grid' => [
                            '',
                            'wrap',
                            'a-v-stretch',
                            'i-15'
                        ]
                    ],
                    'data' => [
                        'grid' => $arVisual['COLUMNS'],
                        'role' => 'items'
                    ]
                ]) ?>
                    <?php foreach ($arResult['ITEMS'] as $arItem) {

                        $sId = $sTemplateId.'_'.$arItem['ID'];
                        $sAreaId = $this->GetEditAreaId($sId);
                        $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                        $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);


                        if (!$bFirst) {
                            $viewDefaultRender($arItem);
                        } else {
                            $viewBigRender($arItem);
                            $bFirst = false;
                        }

                    } ?>
                <?= Html::endTag('div') ?>
            </div>
            <?php if ($arVisual['NAVIGATION']['SHOW']['BOTTOM']) { ?>
                <div data-pagination-num="<?= $arResult['NAVIGATION']['NUMBER'] ?>">
                    <!-- pagination-container -->
                    <?= $arResult['NAV_STRING'] ?>
                    <!-- pagination-container -->
                </div>
            <?php } ?>
        </div>
    </div>
    <?php if ($arResult['TAGS']['SHOW'] && $arResult['TAGS']['MODE'] === 'active')
        include(__DIR__.'/parts/form.php');
    ?>
</div>