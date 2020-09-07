<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\FileHelper;
use intec\core\helpers\RegExp;
use intec\core\helpers\Html;
use intec\core\helpers\Type;
use intec\core\io\Path;
use intec\template\Properties;

/**
 * @var string $directory
 */

(function ($directory) { ?>
<?php
    global $APPLICATION;

    $properties = Properties::getCollection();
    $page['path'] = $APPLICATION->GetCurPage(false);
    $page['main'] = $page['path'] === SITE_DIR;
    $page['background'] = $properties->get('template-background-show');
    $page['type'] = $properties->get('template-page-type');
    $page['blocks'] = [
        'basket' => [
            'fixed' => [
                'use' => $properties->get('basket-use') && $properties->get('basket-position') === 'fixed.right',
                'template' => $properties->get('basket-fixed-template')
            ],
            'notifications' => [
                'use' =>
                    $properties->get('basket-use') &&
                    $properties->get('basket-notifications-use'),
                'template' => 'template.1'
            ]
        ],
        'blocks' => $page['main'],
        'breadcrumb' => !$page['main'] && $properties->get('template-breadcrumb-show'),
        'title' => !$page['main'] && $properties->get('template-title-show'),
        'menu' => $properties->get('template-menu-show'),
        'panel' => true,
        'mobile' => [
            'panel' => [
                'use' => false,
                'template' => null
            ]
        ]
    ];

    $template = $properties->get('pages-main-template');
    $blocks = $properties->get('pages-main-blocks');
    $mobileBlocks = $properties->get('mobile-blocks');

    if (!empty($mobileBlocks)) {
        if (!empty($mobileBlocks['panel'])) {
            $page['blocks']['mobile']['panel']['use'] = $mobileBlocks['panel']['active'];
            $page['blocks']['mobile']['panel']['template'] = $mobileBlocks['panel']['template'];
        }
    }

    unset($mobileBlocks);

    if (empty($template))
        $template = 'wide';

    foreach ($blocks as $code => &$block)
        $block['code'] = $code;

    unset($block);

    if (FileHelper::isFile($directory.'/parts/custom/header.php'))
        include($directory.'/parts/custom/header.php');
?>
    <div class="intec-template">
        <div class="<?= Html::cssClassFromArray([
            'intec-template-content' => true,
            'intec-content' => $page['background'],
            'intec-content-visible' => $page['background']
        ], true) ?>">
            <?= Html::beginTag('div', [
                'class' => Html::cssClassFromArray([
                    'intec-template-content-wrapper' => true,
                    'intec-content-wrapper' => $page['background']
                ], true)
            ]) ?>
                <div class="intec-template-header">
                    <?php if ($page['blocks']['panel']) { ?>
                        <?php $APPLICATION->ShowPanel(); ?>
                    <?php } ?>
                    <?php if ($page['blocks']['basket']['fixed']['use']) { ?>
                        <?php $APPLICATION->IncludeComponent(
                            'bitrix:main.include',
                            '.default',
                            [
                                'AREA_FILE_SHOW' => 'file',
                                'PATH' => SITE_DIR.'include/header/basket/fixed/'.$page['blocks']['basket']['fixed']['template'].'.php'
                            ],
                            false,
                            ['HIDE_ICONS' => 'Y']
                        ) ?>
                    <?php } ?>
                    <?php if ($page['blocks']['basket']['notifications']['use']) { ?>
                        <?php $APPLICATION->IncludeComponent(
                            'bitrix:main.include',
                            '.default',
                            [
                                'AREA_FILE_SHOW' => 'file',
                                'PATH' => SITE_DIR.'include/header/basket/notifications/'.$page['blocks']['basket']['notifications']['template'].'.php'
                            ],
                            false,
                            ['HIDE_ICONS' => 'Y']
                        ) ?>
                    <?php } ?>
                    <?php if ($page['blocks']['mobile']['panel']['use']) { ?>
                        <?php $APPLICATION->IncludeComponent(
                            'bitrix:main.include',
                            '.default',
                            [
                                'AREA_FILE_SHOW' => 'file',
                                'PATH' => SITE_DIR.'include/header/mobile/panel/'.$page['blocks']['mobile']['panel']['template'].'.php'
                            ],
                            false,
                            ['HIDE_ICONS' => 'Y']
                        ) ?>
                    <?php } ?>
                    <?php $APPLICATION->IncludeComponent(
                        'bitrix:main.include',
                        '.default',
                        [
                            'AREA_FILE_SHOW' => 'file',
                            'PATH' => SITE_DIR.'include/header/base.php'
                        ],
                        false,
                        ['HIDE_ICONS' => 'Y']
                    ) ?>
                </div>
                <?php if ($page['blocks']['breadcrumb']) { ?>
                    <div class="intec-template-breadcrumb">
                        <?php $APPLICATION->IncludeComponent(
                            'bitrix:main.include',
                            '.default',
                            [
                                'AREA_FILE_SHOW' => 'file',
                                'PATH' => SITE_DIR.'include/header/breadcrumb.php'
                            ],
                            false,
                            ['HIDE_ICONS' => 'Y']
                        ) ?>
                    </div>
                <?php } ?>
                <?php if ($page['blocks']['title']) { ?>
                    <div class="intec-template-title">
                        <?php $APPLICATION->IncludeComponent(
                            'bitrix:main.include',
                            '.default',
                            [
                                'AREA_FILE_SHOW' => 'file',
                                'PATH' => SITE_DIR.'include/header/title.php'
                            ],
                            false,
                            ['HIDE_ICONS' => 'Y']
                        ) ?>
                    </div>
                <?php } ?>
                <?= Html::beginTag('div', [
                    'class' => Html::cssClassFromArray([
                        'intec-template-page' => true,
                        'intec-template-page-'.$page['type'] => !empty($page['type'])
                    ], true)
                ]) ?>
                    <?php if ($page['blocks']['menu']) { ?>
                        <div class="intec-content intec-content-visible">
                            <div class="intec-content-wrapper">
                                <div class="intec-content-left">
                                    <?php $APPLICATION->IncludeComponent(
                                        'bitrix:main.include',
                                        '.default',
                                        [
                                            'AREA_FILE_SHOW' => 'file',
                                            'PATH' => SITE_DIR.'include/header/menu.php'
                                        ],
                                        false,
                                        ['HIDE_ICONS' => 'Y']
                                    ) ?>
                                </div>
                                <div class="intec-content-right">
                                    <div class="intec-content-right-wrapper">
                    <?php } ?>
                    <?php if ($page['main']) {
                        $blocks = Arrays::from($blocks);
                        $render = function ($block, $data = []) use(&$blocks, &$template) {
                            global $APPLICATION;

                            if (!Type::isArray($block))
                                return;

                            if (!$block['active'])
                                return;

                            if (!Type::isArray($data))
                                $data = [];

                            $path = Path::from('@root'.SITE_DIR.'include/index/'.$template);

                            if (empty($block['template'])) {
                                $path = $path->add($block['code'].'.php');
                            } else {
                                $path = $path->add($block['code'])->add($block['template'].'.php');
                            }

                            if (FileHelper::isFile($path->value))
                                include($path->value);
                        };

                        if (FileHelper::isFile($directory.'/parts/custom/blocks.php'))
                            include($directory.'/blocks/custom/blocks.php');

                        $path = Path::from('@root'.SITE_DIR.'include/index/'.$template.'.php');

                        if (FileHelper::isFile($path->value))
                            include($path->value);
                    } ?>
<?php })($directory);