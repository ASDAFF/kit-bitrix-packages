<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\FileHelper;
use intec\core\helpers\Type;
use intec\core\io\Path;
use intec\constructor\models\Font;

/**
 * @var array $arParams
 * @var array $arResult
 * @var boolean $bSave
 * @var IntecSystemSettingsComponent $this
 */

$arParams = ArrayHelper::merge([
    'VARIABLES_VARIANT' => 'variant'
], $arParams);

$arResult['VARIABLES']['VARIANT'] = $arParams['VARIABLES_VARIANT'];

foreach ($arResult['PROPERTIES'] as $sKey => &$arProperty) {
    $sType = ArrayHelper::getValue($arProperty, 'type');

    if ($sType === 'blocks') {
        $arBlocks = ArrayHelper::getValue($arProperty, 'blocks');
        $arValue = $arProperty['value'];

        if (!Type::isArray($arBlocks))
            $arBlocks = [];

        if (!Type::isArray($arValue))
            $arValue = [];

        foreach ($arBlocks as $sBlockKey => $arBlock) {
            $bActive = ArrayHelper::getValue($arValue, [$sBlockKey, 'active']);
            $arTemplates = ArrayHelper::getValue($arBlock, 'templates');
            $sTemplate = ArrayHelper::getValue($arValue, [$sBlockKey, 'template']);

            if ($bActive === null) {
                $bActive = true;
            } else {
                $bActive = Type::toBoolean($bActive);
            }

            if (empty($arTemplates) || !Type::isArray($arTemplates)) {
                $sTemplate = null;
            } else {
                $bFirst = true;
                $bSet = false;
                $sTemplateFirst = null;

                foreach ($arTemplates as $arTemplate) {
                    $sTemplateValue = ArrayHelper::getValue($arTemplate, 'value');

                    if ($sTemplateValue === null)
                        continue;

                    if ($bFirst)
                        $sTemplateFirst = $sTemplateValue;

                    if ($sTemplate == $sTemplateValue) {
                        $bSet = true;
                        $sTemplate = $sTemplateValue;

                        break;
                    }

                    $bFirst = false;
                }

                if (!$bSet)
                    $sTemplate = $sTemplateFirst;

                unset($arTemplate);
                unset($sTemplateFirst);
                unset($bSet);
                unset($bFirst);
            }

            $arValue[$sBlockKey] = [
                'active' => $bActive,
                'template' => $sTemplate
            ];

            unset($sTemplate);
            unset($arTemplates);
            unset($bActive);
        }

        $arProperty['value'] = $arValue;

        unset($arValue);
        unset($arBlocks);
    }

    unset($sType);
}

unset($arProperty);

if ($arResult['MODE'] === 'configure') {
    $oFonts = Font::findAvailable()->indexBy('code');
    $oFont = $arResult['PROPERTIES']['template-font']['value'];

    if (!empty($oFont)) {
        /** @var Font $oFont */
        $oFont = $oFonts->get($oFont);

        if (!empty($oFont))
            $oFont->register();
    }
}

/* Получение вариантов настроек */

$arResult['VARIANTS'] = [];
$sVariantsPath = __DIR__.'/variants';

if (FileHelper::isDirectory($sVariantsPath)) {
    $arVariants = FileHelper::getDirectoryEntries($sVariantsPath, false);

    foreach ($arVariants as $sVariantCode) {
        $sVariantPath = $sVariantsPath.'/'.$sVariantCode;

        if (
            !FileHelper::isFile($sVariantPath.'/preview.png') ||
            !FileHelper::isFile($sVariantPath.'/meta.php')
        ) continue;

        $arVariant = include($sVariantPath.'/meta.php');

        if (empty($arVariant['name']) || empty($arVariant['values']) || empty($arVariant['description']))
            continue;

        $arVariant['code'] = $sVariantCode;
        $arVariant['preview'] = Path::from($sVariantPath.'/preview.png')
            ->toRelative()
            ->asAbsolute();

        $arResult['VARIANTS'][$arVariant['code']] = $arVariant;
    }

    unset($arVariant);
    unset($sVariantPath);
    unset($sVariantCode);
    unset($arVariants);
}

unset($sVariantPath);

/* Применение варианта от GET-запроса */

if (!empty($arResult['VARIANTS'])) {
    $arVariant = Core::$app->request->get($arResult['VARIABLES']['VARIANT']);

    if (!empty($arVariant) && !empty($arResult['VARIANTS'][$arVariant])) {
        $arVariant = $arResult['VARIANTS'][$arVariant];

            $arResult['ACTION'] = null;

            foreach ($arVariant['values'] as $sKey => $mValue) {
                $arResult['PROPERTIES'][$sKey]['value'] = $mValue;
            }

            if ($USER->IsAdmin()) {
                $this->saveToFile($arVariant['values']);
            } else {
                $this->saveToSession($arVariant['values']);
            }
    }
}