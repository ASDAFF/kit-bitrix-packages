<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
    <?php

use intec\core\helpers\FileHelper;
use intec\core\helpers\Html;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arBlock
 */

?>
<div class="catalog-element-documents widget">
    <div class="catalog-element-documents-wrapper intec-content intec-content-visible">
        <div class="catalog-element-documents-wrapper-2 intec-content-wrapper">
            <?php if (!empty($arBlock['HEADER']['VALUE'])) { ?>
                <div class="catalog-element-documents-header widget-header">
                    <?= Html::tag('div', $arBlock['HEADER']['VALUE'], [
                        'class' => [
                            'widget-title',
                            'align-'.$arBlock['HEADER']['POSITION']
                        ]
                    ]) ?>
                </div>
            <?php } ?>
            <?= Html::beginTag('div', [
                'class' => [
                    'catalog-element-documents-content widget-content',
                    'intec-grid' => [
                        '',
                        'wrap',
                        'i-5',
                        'a-h-start',
                        'a-v-stretch'
                    ]
                ]
            ]) ?>
            <?php foreach ($arBlock['DOCUMENTS'] as $arDocument) { ?>
                <?php
                $sDocumentName = FileHelper::getFileNameWithoutExtension($arDocument['ORIGINAL_NAME']);
                $sDocumentExtension = FileHelper::getFileExtension($arDocument['ORIGINAL_NAME']);
                $sDocumentSize = CFile::FormatSize($arDocument['FILE_SIZE']);
                ?>
                <?= Html::beginTag('a', [
                    'href' => $arDocument['SRC'],
                    'class' => [
                        'catalog-element-document',
                        'intec-grid-item' => [
                            '4',
                            '1000-3',
                            '750-2',
                            '500-1'
                        ]
                    ],
                    'target' => '_blank'
                ]) ?>
                <div class="catalog-element-document-wrapper">
                    <div class="catalog-element-document-wrapper-2">
                        <div class="catalog-element-document-name">
                            <?= Html::encode($sDocumentName) ?>
                        </div>
                        <div class="catalog-element-document-size">
                            <?= Html::encode($sDocumentSize) ?>
                        </div>
                        <div class="catalog-element-document-extension">
                            <?= !empty($sDocumentExtension) ? Html::encode('.'.$sDocumentExtension) : null ?>
                        </div>
                    </div>
                </div>
                <?= Html::endTag('a') ?>
            <?php } ?>
            <?= Html::endTag('div') ?>
        </div>
    </div>
</div>

