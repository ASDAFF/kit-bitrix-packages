<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\helpers\Type;

/**
 * @var array $arResult
 * @var array $arVisual
 * @var string $sTemplateId
 */

?>
<?php $vItems = function ($items) use (&$arVisual, &$arResult, &$sTemplateId) { ?>
    <?php if (!empty($items)) {

        $arForm = $arResult['FORM'];

        $arForm['PARAMETERS'] = [
            'id' => $arForm['ID'],
            'template' => $arForm['TEMPLATE'],
            'parameters' => [
                'AJAX_OPTION_ADDITIONAL' => $sTemplateId.'_FORM_ORDER',
                'CONSENT_URL' => $arForm['CONSENT']
            ],
            'settings' => [
                'title' => $arForm['TITLE']
            ],
            'fields' => [
                $arForm['FIELD'] => null
            ]
        ];

        if (empty($arForm['BUTTON']))
            $arForm['BUTTON'] = Loc::getMessage('C_MAIN_RATES_TEMPLATE_2_TEMPLATE_ODER_BUTTON_DEFAULT')

    ?>
        <?= Html::beginTag('div', [
            'class' => [
                'widget-items',
                'intec-grid' => [
                    '',
                    'wrap',
                    'a-v-stretch'
                ]
            ],
            'data' => [
                'order' => $arForm['USE'] ? 'true' : 'false'
            ]
        ]) ?>
            <?php foreach ($items as $arItem) {

                $sId = $sTemplateId.'_'.$arItem['ID'];
                $sAreaId = $this->GetEditAreaId($sId);
                $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                $arData = $arItem['DATA'];

                $sPicture = $arItem['PREVIEW_PICTURE'];

                if (empty($sPicture))
                    $sPicture = $arItem['DETAIL_PICTURE'];

                if (!empty($sPicture)) {
                    $sPicture = CFile::ResizeImageGet(
                        $sPicture, [
                            'width' => 650,
                            'height' => 650
                        ],
                        BX_RESIZE_IMAGE_PROPORTIONAL_ALT
                    );

                    if (!empty($sPicture))
                        $sPicture = $sPicture['src'];
                }

                if (empty($sPicture))
                    $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

                $arForm['PARAMETERS']['fields'][$arForm['FIELD']] = $arItem['NAME'];

            ?>
                <?= Html::beginTag('div', [
                    'class' => Html::cssClassFromArray([
                        'widget-item' => true,
                        'intec-grid-item' => [
                            $arVisual['COLUMNS'] => true,
                            '1200-3' => $arVisual['COLUMNS'] >= 3,
                            '1024-2' => true,
                            '650-1' => true
                        ]
                    ], true),
                    'data' => [
                        'price' => $arVisual['PRICE']['SHOW'] && !empty($arData['PRICE']) ? 'true' : 'false'
                    ]
                ]) ?>
                    <div class="widget-item-wrapper" id="<?= $sAreaId ?>">
                        <div class="widget-item-picture-wrap">
                            <?= Html::tag('div', '', [
                                'class' => 'widget-item-picture',
                                'data' => [
                                    'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                    'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                                ],
                                'style' => [
                                    'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null
                                ]
                            ]) ?>
                            <div class="widget-item-fade"></div>
                            <div class="widget-item-name">
                                <?= $arItem['NAME'] ?>
                            </div>
                        </div>
                        <?php if ($arData['PROPERTIES']) { ?>
                            <div class="widget-item-properties">
                                <?php foreach ($arItem['DISPLAY_PROPERTIES'] as $arProperty) {

                                    if (empty($arProperty['VALUE']) || !empty($arProperty['USER_TYPE']))
                                        continue;

                                ?>
                                    <div class="widget-item-property">
                                        <div class="widget-item-property-name">
                                            <?= $arProperty['NAME'] ?>
                                        </div>
                                        <div class="widget-item-property-value">
                                            <?= !Type::isArray($arProperty['DISPLAY_VALUE']) ?
                                                $arProperty['DISPLAY_VALUE'] :
                                                implode(', ', $arProperty['DISPLAY_VALUE'])
                                            ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                        <?php if ($arVisual['PRICE']['SHOW'] && !empty($arData['PRICE'])) { ?>
                            <div class="widget-item-price">
                                <span>
                                    <?= $arData['PRICE'] ?>
                                </span>
                                <?php if (!empty($arData['CURRENCY'])) { ?>
                                    <span>
                                        <?= $arData['CURRENCY'] ?>
                                    </span>
                                <?php } ?>
                            </div>
                        <?php } ?>
                        <?php if ($arForm['USE']) { ?>
                            <?= Html::tag('div', Html::stripTags($arForm['BUTTON']), [
                                'class' => [
                                    'widget-item-button',
                                    'intec-cl-background-hover',
                                ],
                                'onclick' => '(function() {
                                    universe.forms.show('.JavaScript::toObject($arForm['PARAMETERS']).');
                                    if (window.yandex && window.yandex.metrika) {
                                       window.yandex.metrika.reachGoal(\'forms.open\');
                                       window.yandex.metrika.reachGoal('.JavaScript::toObject('forms.'.$arForm['ID'].'.open').');
                                   }
                                })()'
                            ]) ?>
                        <?php } ?>
                    </div>
                <?= Html::endTag('div') ?>
            <?php } ?>
        <?= Html::endTag('div') ?>
    <?php } ?>
<?php } ?>