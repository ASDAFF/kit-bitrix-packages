<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;

/**
 * @var array $arResult
 */

$this->setFrameMode(true);
$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

if (!$arResult['FORM']['SHOW'])
    return;

?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'widget',
        'c-form',
        'c-form-template-5'
    ],
    'data' => [
        'lazyload-use' => !empty($arResult['BACKGROUND']['IMAGE']) && $arResult['LAZYLOAD']['USE'] ? 'true' : 'false',
        'original' => !empty($arResult['BACKGROUND']['IMAGE']) && $arResult['LAZYLOAD']['USE'] ?
                                $arResult['BACKGROUND']['IMAGE'] : null
    ],
    'style' => [
        'background-color' => !empty($arResult['BACKGROUND']['COLOR']) ? $arResult['BACKGROUND']['COLOR'] : null,
        'background-image' => !empty($arResult['BACKGROUND']['IMAGE']) ?
                                (!$arResult['LAZYLOAD']['USE'] ? 'url(\''.$arResult['BACKGROUND']['IMAGE'].'\')' : null)
                                : null,
        'color' => !empty($arResult['TEXT']['COLOR']) ? $arResult['TEXT']['COLOR'] : null,
    ]
]) ?>
    <div class="widget-wrapper intec-content intec-content-visible">
        <div class="widget-wrapper-2 intec-content-wrapper">
            <div class="intec-grid intec-grid-wrap intec-grid-a-h-center intec-grid-i-h-25 intec-grid-a-v-center">
                <?php if ($arResult['IMAGE']['SHOW']) { ?>
                    <div class="intec-grid-item-auto widget-image intec-grid-item-800-1">
                        <?= Html::img($arResult['LAZYLOAD']['USE'] ? $arResult['LAZYLOAD']['STUB'] : $arResult['IMAGE']['SRC'], [
                            'loading' => 'lazy',
                            'data' => [
                                'lazyload-use' => $arResult['LAZYLOAD']['USE'] ? 'true' : 'false',
                                'original' => $arResult['LAZYLOAD']['USE'] ? $arResult['IMAGE']['SRC'] : null
                            ]
                        ]) ?>
                    </div>
                <?php } ?>
                <div class="intec-grid-item intec-grid-item-800-1 widget-text">
                    <div class="widget-title">
                        <?= $arResult['TITLE']['TEXT'] ?>
                    </div>
                    <?php if ($arResult['DESCRIPTION']['SHOW']) { ?>
                        <div class="widget-description">
                            <?= $arResult['DESCRIPTION']['TEXT'] ?>
                        </div>
                    <?php } ?>
                </div>
                <div class="intec-grid-item-auto intec-grid-item-800-1">
                    <div class="widget-button-wrap">
                        <?= Html::tag('div', $arResult['FORM']['BUTTON'], [
                            'class' => 'widget-button intec-ui intec-ui-control-button intec-ui-scheme-current intec-ui-size-5',
                            'onclick' => '(function() {
                                universe.forms.show('.JavaScript::toObject([
                                    'id' => $arResult['FORM']['ID'],
                                    'template' => $sTemplate,
                                    'parameters' => [
                                        'AJAX_OPTION_ADDITIONAL' => $sTemplateId . '_FORM_ASK',
                                        'CONSENT_URL' => $arResult['CONSENT']
                                    ],
                                    'settings' => [
                                        'title' => $arResult['FORM']['NAME']
                                    ]
                                ]).');
                                
                                if (window.yandex && window.yandex.metrika) {
                                    window.yandex.metrika.reachGoal(\'forms.open\');
                                    window.yandex.metrika.reachGoal('.JavaScript::toObject('forms.'.$arResult['FORM']['ID'].'.open').');
                                }
                            })()'
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?= Html::endTag('div') ?>