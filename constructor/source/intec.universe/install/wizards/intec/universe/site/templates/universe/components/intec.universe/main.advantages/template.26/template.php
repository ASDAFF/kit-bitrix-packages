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

$arVisual = $arResult['VISUAL'];
$sButtonText = $arVisual['BUTTON']['TEXT'];

if (empty($sButtonText))
    $sButtonText = Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_26_TEMPLATE_BUTTON_TEXT_DEFAULT');

/**
 * @var Closure $vImages()
 */
$vImages = include(__DIR__.'/parts/images.php');

?>
<div class="widget c-advantages c-advantages-template-26" id="<?= $sTemplateId ?>">
    <div class="widget-items">
        <?php foreach ($arResult['ITEMS'] as $arItem) {

            $sId = $sTemplateId.'_'.$arItem['ID'];
            $sAreaId = $this->GetEditAreaId($sId);
            $this->AddEditAction($sId, $arItem['EDIT_LINK']);
            $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

            $sPicture = $arItem['PREVIEW_PICTURE'];

            if (empty($sPicture))
                $sPicture = $arItem['DETAIL_PICTURE'];

            if (!empty($sPicture))
                $sPicture = $sPicture['SRC'];

            $bExpandable = !empty($arItem['PREVIEW_TEXT']) || !empty($sPicture) || !empty($arItem['DATA']['ADDITIONAL_TEXT']);

        ?>
            <?= Html::beginTag('div', [
                'id' => $sAreaId,
                'class' => 'widget-item',
                'data' => [
                    'role' => 'item',
                    'expandable' => $bExpandable ? 'true' : 'false',
                    'theme' => $arItem['DATA']['THEME'],
                    'view' => $arItem['DATA']['VIEW']
                ],
                'data-compact-position' => $arItem['DATA']['VIEW'] === 'compact' ? $arItem['DATA']['COMPACT']['POSITION'] : null
            ]) ?>
                <div class="intec-content intec-content-visible">
                    <div class="intec-content-wrapper">
                        <?php if ($arItem['DATA']['VIEW'] === 'default') {
                            include(__DIR__.'/parts/view.default.php');
                        } else if ($arItem['DATA']['VIEW'] === 'compact') {
                            include(__DIR__.'/parts/view.compact.php');
                        } ?>
                        <?php if (!empty($arItem['DETAIL_TEXT'])) { ?>
                            <?= Html::beginTag('div', [
                                'class' => 'widget-item-detail',
                                'data' => [
                                    'role' => 'item.content',
                                    'expanded' => $bExpandable ? 'false' : 'true'
                                ]
                            ]) ?>
                                <?= Html::tag('div', $arItem['DETAIL_TEXT'], [
                                    'class' => 'widget-item-detail-wrapper',
                                    'data' => [
                                        'narrow' => $arItem['DATA']['DETAIL']['NARROW'] ? 'true' : 'false'
                                    ]
                                ]) ?>
                            <?= Html::endTag('div') ?>
                            <?php if ($bExpandable) { ?>
                                <div class="widget-item-button-wrap">
                                    <?= Html::tag('div', $sButtonText, [
                                        'class' => Html::cssClassFromArray([
                                            'widget-item-button' => true,
                                            'intec-cl-border' => $arItem['DATA']['THEME'] !== 'black',
                                            'intec-cl-background-hover' => $arItem['DATA']['THEME'] !== 'black'
                                        ], true),
                                        'data' => [
                                            'role' => 'item.button'
                                        ]
                                    ]) ?>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            <?= Html::endTag('div') ?>
        <?php } ?>
    </div>
</div>
<?php include(__DIR__.'/parts/script.php') ?>