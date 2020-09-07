<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\FileHelper;
use intec\core\helpers\Html;

?>
<div class="basket-filter-wrap intec-grid-item">
    <?= Html::beginTag('div', [
        'class' => 'basket-filter',
        'data-entity' => 'basket-items-list-header'
    ]) ?>
        <div class="intec-grid intec-grid-a-v-center">
            <div class="basket-filter-control-wrap intec-grid-item-auto">
                <?= Html::beginTag('div', [
                    'class' => 'basket-filter-control',
                    'data-entity' => 'basket-filter'
                ]) ?>
                    <?= Html::input('text', null, null, [
                        'class' => [
                            'basket-filter-control-input',
                            'intec-ui' => [
                                '',
                                'control-input',
                                'view-1',
                                'size-2',
                                'mod-block'
                            ]
                        ],
                        'placeholder' => Loc::getMessage('C_BASKET_DEFAULT_1_TEMPLATE_FILTER_PLACEHOLDER'),
                        'data-entity' => 'basket-filter-input'
                    ]) ?>
                    <?= Html::beginTag('div', [
                        'class' => 'basket-filter-control-reset',
                        'data-entity' => 'basket-filter-clear-btn'
                    ]) ?>
                        <div class="intec-aligner"></div>
                        <?= FileHelper::getFileData(__DIR__.'/../svg/filter.reset.svg') ?>
                    <?= Html::endTag('div') ?>
                <?= Html::endTag('div') ?>
            </div>
            <div class="intec-grid-item">
                <div class="basket-filter-statuses">
                    <?= Html::beginTag('div', [
                        'class' => [
                            'basket-filter-statuses-wrapper',
                            'intec-grid' => [
                                '',
                                'wrap',
                                'a-h-end'
                            ]
                        ]
                    ]) ?>
                        <?= Html::tag('div', null, [
                            'class' => [
                                'basket-filter-status',
                                'intec-grid-item-auto',
                                'intec-cl-border-hover',
                                'intec-cl-border',
                                'active'
                            ],
                            'data' => [
                                'entity' => 'basket-items-count',
                                'filter' => 'all'
                            ],
                            'style' => [
                                'display' => 'none'
                            ]
                        ]) ?>
                        <?= Html::tag('div', null, [
                            'class' => [
                                'basket-filter-status',
                                'intec-grid-item-auto',
                                'intec-cl-border-hover'
                            ],
                            'data' => [
                                'entity' => 'basket-items-count',
                                'filter' => 'similar'
                            ],
                            'style' => [
                                'display' => 'none'
                            ]
                        ]) ?>
                        <?= Html::tag('div', null, [
                            'class' => [
                                'basket-filter-status',
                                'intec-grid-item-auto',
                                'intec-cl-border-hover'
                            ],
                            'data' => [
                                'entity' => 'basket-items-count',
                                'filter' => 'warning'
                            ],
                            'style' => [
                                'display' => 'none'
                            ]
                        ]) ?>
                        <?= Html::tag('div', null, [
                            'class' => [
                                'basket-filter-status',
                                'intec-grid-item-auto',
                                'intec-cl-border-hover'
                            ],
                            'data' => [
                                'entity' => 'basket-items-count',
                                'filter' => 'delayed'
                            ],
                            'style' => [
                                'display' => 'none'
                            ]
                        ]) ?>
                        <?= Html::tag('div', null, [
                            'class' => [
                                'basket-filter-status',
                                'intec-grid-item-auto',
                                'intec-cl-border-hover'
                            ],
                            'data' => [
                                'entity' => 'basket-items-count',
                                'filter' => 'not-available'
                            ],
                            'style' => [
                                'display' => 'none'
                            ]
                        ]) ?>
                    <?= Html::endTag('div') ?>
                </div>
            </div>
        </div>
    <?= Html::endTag('div') ?>
</div>
