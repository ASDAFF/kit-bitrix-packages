<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;
use intec\constructor\models\build\Area;
use intec\constructor\models\build\Template;
use intec\constructor\models\build\template\Block;
use intec\constructor\models\build\template\Container;
use intec\constructor\models\build\template\Component;
use intec\constructor\models\build\template\Variator;
use intec\constructor\models\build\template\Widget;
use intec\template\Properties;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 */

global $USER;

$this->setFrameMode(true);

/** @var Template $template */
$template = $arResult['TEMPLATE'];
/** @var Container $container */
$container = $arResult['CONTAINER'];

$isHeader = $arResult['DISPLAY'] == 'HEADER';
$isFooter = $arResult['DISPLAY'] == 'FOOTER';
$flag = false;
$isRender = function () use (&$isHeader, &$isFooter, &$flag) {
    return $isHeader || ($isFooter && $flag);
};

/** @var Area $storageArea */
$storageArea = null;
$mobileBlocks = Arrays::from(Properties::get('mobile-blocks'));
$pagesMainBlocks = Arrays::from(Properties::get('pages-main-blocks'));
$footerBlocks = Arrays::from(Properties::get('footer-blocks'));

/**
 * @param Container $container
 * @param string $variant
 */
$containerVariantSet = function ($container, $code, $save = false) {
    $variator = $container->getVariator(true);

    if (!empty($variator)) {
        $variants = $variator->getVariants(true, true, true);
        $variant = null;
        $index = 0;

        foreach ($variants as $variant) {
            if ($variant->code == $code)
                break;

            $variant = null;
            $index++;
        }

        if (!empty($variant)) {
            $variator->variant = $index;
        } else {
            $container->display = false;
        }

        if ($save)
            $variator->save(true, ['variant']);
    }

    if ($save)
        $container->save(true, ['display']);
};

/**
 * @param Container $container
 * @return bool
 */
$draw = function ($container) use (&$draw, &$flag, &$isHeader, &$isFooter, &$isRender, &$containerVariantSet, &$storageArea, &$mobileBlocks, &$pagesMainBlocks, &$footerBlocks, &$USER, &$arResult) {
    if ($isRender() && !empty($container->code)) {
        $save = $USER->IsAdmin() && (
            $arResult['ACTION'] === 'apply' ||
            $arResult['ACTION'] === 'reset'
        );

        if (!empty($storageArea)) {
            if ($storageArea->code === 'header') {
                switch ($container->code) {
                    case 'basket-fixed': {
                        $container->display =
                            Properties::get('basket-use') &&
                            Properties::get('basket-position') === 'fixed.right';

                        if ($container->display) {
                            $containerVariantSet($container, Properties::get('basket-fixed-template'), $save);
                        } else if ($save) {
                            $container->save(true, ['display']);
                        }

                        break;
                    }
                    case 'basket-notifications': {
                        $show = Properties::get('basket-use');
                        $show = $show && Properties::get('basket-notifications-use');
                        $container->display = $show;

                        if ($save)
                            $container->save(true, ['display']);

                        unset($show);
                        break;
                    }
                }
            } else if ($storageArea->code === 'footer') {
                if (StringHelper::startsWith($container->code, 'footer-blocks.')) {
                    $block = StringHelper::cut($container->code, 14);
                    $block = $footerBlocks->get($block);

                    if (!empty($block)) {
                        $container->display = $block['active'];
                        $containerVariantSet($container, $block['template'], $save);
                    }
                }
            }
        } else {
            if ($container->code === 'template-content') {
                if (Properties::get('template-background-show'))
                    $container->setClassAttribute($container->getClassAttribute().' intec-content intec-content-visible');
            } else if ($container->code === 'template-content-wrapper') {
                if (Properties::get('template-background-show'))
                    $container->setClassAttribute($container->getClassAttribute().' intec-content-wrapper');
            } else if ($container->code === 'template-breadcrumb') {
                $container->display = Properties::get('template-breadcrumb-show');
            } else if ($container->code === 'template-title') {
                $container->display = Properties::get('template-title-show');
            } else if ($container->code === 'template-page') {
                $type = Properties::get('template-page-type');
                $class = $container->getClassAttribute();

                if ($type !== null && !empty($class))
                    $container->setClassAttribute($class.' intec-template-page-'.$type);

                unset($class);
                unset($type);
            } else if (StringHelper::startsWith($container->code, 'pages-main-blocks.')) {
                $block = StringHelper::cut($container->code, 18);
                $block = $pagesMainBlocks->get($block);

                if (!empty($block)) {
                    $container->display = $block['active'];
                    $containerVariantSet($container, $block['template'], $save);
                }
            }
        }

        if (StringHelper::startsWith($container->code, 'mobile-blocks.')) {
            $block = StringHelper::cut($container->code, 14);
            $block = $mobileBlocks->get($block);

            if (!empty($block)) {
                $container->display = $block['active'];
                $containerVariantSet($container, $block['template'], $save);
            }
        }

        unset($save);
    }

    if (!$container->isDisplayed())
        return true;

    $id = $container->getIdAttribute();
    $class = $container->getClassAttribute();
    $style = $container->getStyleAttribute();

    if ($isRender()) {
        echo Html::beginTag('div', array(
            'id' => $id,
            'class' => 'container-'.$container->id.($class ? ' ' . $class : null),
            'style' => $style
        ));
    }

    if ($container->hasArea()) {
        /** @var Area $area */
        $area = $container->getArea(true);
        $child = $area->getContainer();

        if (!empty($child)) {
            $storageArea = $area;

            if (!$draw($child)) {
                $storageArea = null;
                return false;
            }

            $storageArea = null;
        }
    } else if ($container->hasComponent()) {
        if ($isRender()) {
            /** @var Component $component */
            $component = $container->getComponent(true);
            $component->render(true);
        }
    } else if ($container->hasWidget()) {
        /** @var Widget $widget */
        $widget = $container->getWidget(true);

        if ($widget->code == 'intec.constructor:content') {
            if ($isHeader)
                return false;

            if (!$flag)
                $flag = true;
        } else if ($isRender()) {
            $widget->render(true, true);
        }
    } else if ($container->hasBlock()) {
        if ($isRender()) {
            /** @var Block $block */
            $block = $container->getBlock(true);
            $block->render(true, true);
        }
    } else if ($container->hasVariator()) {
        /** @var Variator $variator */
        $variator = $container->getVariator(true);
        $variant = $variator->getVariant();

        if (!empty($variant)) {
            $child = $variant->getContainer(true);

            if (!empty($child))
                if (!$draw($child))
                    return false;
        }
    } else {
        $children = $container->getContainers(true, false);
        ArrayHelper::multisort($children, 'order');

        foreach ($children as $child)
            if (!$draw($child))
                return false;
    }

    if ($isRender()) {
        echo Html::endTag('div');
    }

    return true;
};

$draw($container);