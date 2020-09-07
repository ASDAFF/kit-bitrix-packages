<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\FileHelper;
use intec\core\helpers\Html;

/**
 * @var array $arData
 */

$sIcon = FileHelper::getFileData(__DIR__.'/../svg/document.svg');

?>
<div class="catalog-element-section">
    <div class="catalog-element-section-name intec-template-part intec-template-part-title">
        <?= Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_TEMPLATE_DOCUMENTS_NAME') ?>
    </div>
    <div class="catalog-element-section-content">
        <div class="catalog-element-documents intec-grid intec-grid-wrap intec-grid-i-12">
            <?php foreach ($arData['DOCUMENTS'] as $arDocument) { ?>
                <?= Html::beginTag('div', [
                    'class' => [
                        'catalog-element-documents-item',
                        'intec-grid-item' => [
                            '4',
                            '1024-3',
                            '768-2',
                            '500-1'
                        ]
                    ]
                ]) ?>
                    <div class="catalog-element-documents-item-wrapper">
                        <div class="intec-grid intec-grid-i-8">
                            <div class="intec-grid-item-auto">
                                <?= Html::beginTag('a', [
                                    'class' => 'catalog-element-documents-item-icon',
                                    'href' => $arDocument['SRC'],
                                    'target' => '_blank'
                                ]) ?>
                                    <?= $sIcon ?>
                                    <?= Html::tag('span', $arDocument['TYPE']) ?>
                                <?= Html::endTag('a') ?>
                            </div>
                            <div class="intec-grid-item">
                                <div class="catalog-element-documents-item-name">
                                    <?= Html::beginTag('a', [
                                        'href' => $arDocument['SRC'],
                                        'target' => '_blank'
                                    ]) ?>
                                        <?= $arDocument['NAME'] ?>
                                    <?= Html::endTag('a') ?>
                                </div>
                                <div class="catalog-element-documents-item-size">
                                    <?= $arDocument['SIZE'] ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?= Html::endTag('div') ?>
            <?php } ?>
            <?php unset($arDocument) ?>
        </div>
    </div>
</div>
<?php unset($sIcon) ?>