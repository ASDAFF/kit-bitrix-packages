<?php

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;

class IntecSystemVideoTagComponent extends CBitrixComponent
{
    /**
     * @inheritdoc
     */
    public function onPrepareComponentParams($arParams)
    {
        if (!Loader::includeModule('intec.core'))
            return $arParams;

        if (!Type::isArray($arParams))
            $arParams = [];

        $arParams = ArrayHelper::merge([
            'FILES_MP4' => null,
            'FILES_WEBM' => null,
            'FILES_OGV' => null,
            'PICTURE' => null,
            'AUTOPLAY' => 'Y',
            'CONTROLS' => 'N',
            'MUTE' => 'Y',
            'LOOP' => 'Y',
            'PRELOAD' => 'metadata'
        ], $arParams);

        $arParams['PRELOAD'] = ArrayHelper::fromRange([
            'none',
            'metadata',
            'auto'
        ], $arParams['PRELOAD']);

        return parent::onPrepareComponentParams($arParams);
    }

    /**
     * @inheritdoc
     */
    public function executeComponent()
    {
        global $USER;

        if (!Loader::includeModule('intec.core'))
            return null;

        if ($this->startResultCache(false, $USER->GetGroups())) {
            $arParams = $this->arParams;
            $arMacros = [
                'SITE_DIR' => SITE_DIR
            ];

            $arResult = [
                'FILES' => [],
                'PICTURE' => null,
                'AUTOPLAY' => $arParams['AUTOPLAY'] === 'Y',
                'CONTROLS' => $arParams['CONTROLS'] === 'Y',
                'MUTE' => $arParams['MUTE'] === 'Y',
                'LOOP' => $arParams['LOOP'] === 'Y',
                'PRELOAD' => $arParams['PRELOAD']
            ];

            if (!empty($arParams['PICTURE']))
                $arResult['PICTURE'] = StringHelper::replaceMacros(
                    $arParams['PICTURE'],
                    $arMacros
                );

            if (!empty($arParams['FILES_MP4']))
                $arResult['FILES']['MP4'] = $arParams['FILES_MP4'];

            if (!empty($arParams['FILES_WEBM']))
                $arResult['FILES']['WEBM'] = $arParams['FILES_WEBM'];

            if (!empty($arParams['FILES_OGV']))
                $arResult['FILES']['OGV'] = $arParams['FILES_OGV'];

            foreach ($arResult['FILES'] as $sKey => $sLink)
                $arResult['FILES'][$sKey] = StringHelper::replaceMacros(
                    $sLink,
                    $arMacros
                );

            $arResult['CONTROLLED'] = $arResult['CONTROLS'] || !$arResult['LOOP'] || !$arResult['MUTE'];
            $this->arResult = $arResult;

            unset($arResult);

            $this->includeComponentTemplate();
        }

        return $this->arResult;
    }
}