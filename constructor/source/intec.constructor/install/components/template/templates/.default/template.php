<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\constructor\models\build\Area;
use intec\constructor\models\build\Template;
use intec\constructor\models\build\template\Block;
use intec\constructor\models\build\template\Container;
use intec\constructor\models\build\template\Component;
use intec\constructor\models\build\template\Variator;
use intec\constructor\models\build\template\Widget;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 */

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

/**
 * @param Container $container
 * @return bool
 */
$draw = function ($container) use (&$draw, &$flag, &$isHeader, &$isFooter, &$isRender) {
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

        if (!empty($child))
            if (!$draw($child))
                return false;
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