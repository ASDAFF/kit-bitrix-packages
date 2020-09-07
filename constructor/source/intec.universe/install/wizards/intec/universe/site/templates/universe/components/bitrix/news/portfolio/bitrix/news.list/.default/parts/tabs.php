<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arVisual
 */

?>
<div class="widget-tabs">
    <?php if ($arVisual['WIDE']) { ?>
        <div class="intec-content intec-content-visible">
            <div class="intec-content-wrapper">
    <?php } ?>
    <?= Html::beginTag('ul', [
        'class' => [
            'intec-ui' => [
                '',
                'control-tabs',
                'mod-block',
                'mod-position-'.$arVisual['TABS']['POSITION'],
                'scheme-current',
                'view-1'
            ]
        ]
    ]) ?>
        <?= Html::beginTag('li', [
            'class' => [
                'intec-ui-part-tab',
                'active'
            ],
            'data' => [
                'role' => 'tab',
                'type' => 'all',
                'active' => 'true'
            ]
        ]) ?>
            <div>
                <?= Loc::getMessage('C_NEWS_LIST_PORTFOLIO_TEMPLATE_TABS_ALL') ?>
            </div>
        <?= Html::endTag('li') ?>
        <?php foreach ($arResult['TABS'] as $sKey => $arTab) { ?>
            <?= Html::beginTag('li', [
                'class' => 'intec-ui-part-tab',
                'data' => [
                    'role' => 'tab',
                    'type' => $sKey,
                    'active' => 'false'
                ]
            ]) ?>
                <div>
                    <?= $arTab['NAME'] ?>
                </div>
            <?= Html::endTag('li') ?>
        <?php } ?>
    <?= Html::endTag('ul') ?>
    <?php if ($arVisual['WIDE']) { ?>
            </div>
        </div>
    <?php } ?>
</div>