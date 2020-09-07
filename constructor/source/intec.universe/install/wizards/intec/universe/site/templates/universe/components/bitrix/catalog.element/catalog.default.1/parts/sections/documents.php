<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use intec\core\helpers\FileHelper;
use intec\core\helpers\Html;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arVisual
 */

?>
<?= Html::beginTag('div', [
    'class' => [
        'catalog-element-section-documents',
        'intec-grid' => [
            '',
            'wrap',
            'i-5',
            'a-h-start',
            'a-v-stretch'
        ]
    ]
]) ?>
    <?php foreach ($arResult['DOCUMENTS'] as $arDocument) { ?>
    <?php
        $sDocumentName = FileHelper::getFileNameWithoutExtension($arDocument['ORIGINAL_NAME']);
        $sDocumentExtension = FileHelper::getFileExtension($arDocument['ORIGINAL_NAME']);
        $sDocumentSize = CFile::FormatSize($arDocument['FILE_SIZE']);
    ?>
        <?= Html::beginTag('a', [
            'href' => $arDocument['SRC'],
            'class' => [
                'catalog-element-section-document',
                'intec-grid-item' => !(
                    $arVisual['VIEW']['VALUE'] === 'tabs' &&
                    $arVisual['VIEW']['POSITION'] === 'right' &&
                    $arVisual['GALLERY']['SHOW']
                ) ? [
                    '4',
                    '1000-3',
                    '750-2',
                    '500-1'
                ] : [
                    '2',
                    '1000-1',
                    '720-2',
                    '500-1'
                ]
            ],
            'target' => '_blank'
        ]) ?>
            <div class="catalog-element-section-document-wrapper">
                <div class="catalog-element-section-document-wrapper-2">
                    <div class="catalog-element-section-document-name">
                        <?= Html::encode($sDocumentName) ?>
                    </div>
                    <div class="catalog-element-section-document-size">
                        <?= Html::encode($sDocumentSize) ?>
                    </div>
                    <div class="catalog-element-section-document-extension">
                        <?= !empty($sDocumentExtension) ? Html::encode('.'.$sDocumentExtension) : null ?>
                    </div>
                </div>
            </div>
        <?= Html::endTag('a') ?>
    <?php } ?>
<?= Html::endTag('div') ?>