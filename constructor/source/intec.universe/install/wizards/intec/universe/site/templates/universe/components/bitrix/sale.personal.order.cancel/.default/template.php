<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

/**
 * @var array $arParams
 * @var array $arResult
 */

if (!Loader::includeModule('intec.core'))
    return;

?>
<div class="ns-bitrix c-sale-personal-order-cancel c-sale-personal-order-cancel-default">
    <div class="sale-personal-order-cancel-wrapper intec-content">
        <div class="sale-personal-order-cancel-wrapper-2 intec-content-wrapper">
            <?php if (!empty($arResult['URL_TO_LIST'])) { ?>
                <div class="intec-ui-m-b-20">
                    <?= Html::beginTag('a', [
                        'href' => $arResult['URL_TO_LIST'],
                        'class' => [
                            'intec-ui' => [
                                '',
                                'control-button',
                                'mod-transparent',
                                'mod-round-3',
                                'size-2'
                            ]
                        ]
                    ]) ?>
                        <span class="intec-ui-part-icon">
                            <i class="far fa-angle-left"></i>
                        </span>
                        <span class="intec-ui-part-content">
                            <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_CANCEL_DEFAULT_BUTTONS_BACK') ?>
                        </span>
                    <?= Html::endTag('a') ?>
                </div>
            <?php } ?>
            <div class="sale-personal-order-cancel-block">
                <?php if (!empty($arResult['ERROR_MESSAGE'])) { ?>
                    <div class="intec-ui intec-ui-control-alert intec-ui-scheme-red">
                        <?= $arResult['ERROR_MESSAGE'] ?>
                    </div>
                <?php } else { ?>
                    <div class="sale-personal-order-cancel-form intec-ui-form">
                        <form method="POST">
                            <?= bitrix_sessid_post() ?>
                            <?= Html::hiddenInput('CANCEL', 'Y') ?>
                            <?= Html::hiddenInput('ID', $arResult['ID']) ?>
                            <div class="sale-personal-order-cancel-form-description intec-ui-markup-text">
                            <?php
                                echo Loc::getMessage('C_SALE_PERSONAL_ORDER_CANCEL_DEFAULT_DESCRIPTION_1').' ';

                                if (!empty($arResult['URL_TO_DETAIL'])) {
                                    echo Html::a(Loc::getMessage('C_SALE_PERSONAL_ORDER_CANCEL_DEFAULT_DESCRIPTION_2', [
                                        '#ID#' => $arResult['ID']
                                    ]), $arResult['URL_TO_DETAIL']);
                                } else {
                                    echo Loc::getMessage('C_SALE_PERSONAL_ORDER_CANCEL_DEFAULT_DESCRIPTION_2', [
                                        '#ID#' => $arResult['ID']
                                    ]);
                                }

                                echo Loc::getMessage('C_SALE_PERSONAL_ORDER_CANCEL_DEFAULT_DESCRIPTION_3');
                            ?>
                            </div>
                            <div class="sale-personal-order-cancel-form-fields intec-ui-form-fields">
                                <div class="sale-personal-order-cancel-form-field intec-ui-form-field">
                                    <div class="intec-ui-form-field-title">
                                        <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_CANCEL_DEFAULT_FIELDS_REASON_TITLE') ?>
                                    </div>
                                    <div class="intec-ui-form-field-content">
                                        <?= Html::textarea('REASON_CANCELED', null, [
                                            'class' => [
                                                'intec-ui' => [
                                                    '',
                                                    'control-input',
                                                    'mod-block',
                                                    'mod-resize-vertical',
                                                    'mod-round-3',
                                                    'size-2'
                                                ]
                                            ],
                                            'style' => [
                                                'min-height' => '100px'
                                            ]
                                        ]) ?>
                                    </div>
                                    <div class="intec-ui-form-field-description">
                                        <?= Loc::getMessage('C_SALE_PERSONAL_ORDER_CANCEL_DEFAULT_FIELDS_REASON_DESCRIPTION') ?>
                                    </div>
                                </div>
                            </div>
                            <div class="sale-personal-order-cancel-form-buttons">
                                <?= Html::submitInput(Loc::getMessage('C_SALE_PERSONAL_ORDER_CANCEL_DEFAULT_BUTTONS_SUBMIT'), [
                                    'name' => 'action',
                                    'class' => [
                                        'sale-personal-order-cancel-form-button',
                                        'intec-ui' => [
                                            '',
                                            'control-button',
                                            'mod-round-3',
                                            'scheme-current',
                                            'size-2'
                                        ]
                                    ]
                                ]) ?>
                            </div>
                        </form>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>