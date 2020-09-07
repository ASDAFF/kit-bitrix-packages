<?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

$templateBlocksLoad = function ($prefix, $blocks) {
    $result = [];
    $blocksCurrent = 0;

    if (!Type::isArray($blocks))
        return $result;

    foreach ($blocks as $blockCode => $block) {
        if (empty($block['type']))
            continue;

        $container = [
            'code' => ArrayHelper::keyExists('code', $block) ? $block['code']  : $prefix.$blockCode,
            'order' => ArrayHelper::keyExists('order', $block) ? $block['order'] : $blocksCurrent
        ];

        if (!empty($block['properties']))
            $container['properties'] = $block['properties'];

        if ($block['type'] === 'simple') {
            if (!empty($block['component'])) {
                $container['component'] = $block['component'];
            } else if (!empty($block['widget'])) {
                $container['widget'] = $block['widget'];
            } else if (!empty($block['block'])) {
                $container['block'] = $block['block'];
            } else {
                continue;
            }
        } else if ($block['type'] === 'variable') {
            if (empty($block['variants']))
                continue;

            $variantsCurrent = 0;
            $container['variator'] = [
                'variants' => []
            ];

            foreach ($block['variants'] as $variantCode => $variant) {
                $variantContainer = [];

                if (!empty($variant['properties']))
                    $variantContainer['properties'] = $variant['properties'];

                unset($variant['properties']);

                if (!empty($variant['component'])) {
                    $variantContainer['component'] = $variant['component'];
                } else if (!empty($variant['widget'])) {
                    $variantContainer['widget'] = $variant['widget'];
                } else if (!empty($variant['block'])) {
                    $variantContainer['block'] = $variant['block'];
                } else {
                    continue;
                }

                unset($variant['component']);
                unset($variant['widget']);
                unset($variant['block']);

                $variant['code'] = $variantCode;
                $variant['order'] = $variantsCurrent;
                $variant['container'] = [
                    'containers' => [$variantContainer]
                ];

                $container['variator']['variants'][] = $variant;
                $variantsCurrent++;
            }

            if (empty($container['variator']['variants']))
                continue;

            unset($variant);
            unset($variantCode);
            unset($variantsCurrent);
        } else {
            continue;
        }

        $result[] = $container;
        $blocksCurrent++;
    }

    return $result;
};