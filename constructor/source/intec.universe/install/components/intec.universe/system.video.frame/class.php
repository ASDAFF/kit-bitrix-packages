<?php

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;
use intec\core\net\Url;

class IntecSystemVideoFrameComponent extends CBitrixComponent
{
    public function parseUrl($url)
    {
        $url = new Url($url);
        $video = null;

        if ($url->getQuery()->exists('v'))
            $video = $url->getQuery()->get('v');

        if (empty($video))
            $video = $url->getPath()->getLast();

        if (empty($video))
            return null;

        return [
            'ID' => $video,
            'LINKS' => [
                'embed' => 'https://www.youtube.com/embed/'.$video,
                'page' => 'https://www.youtube.com/watch?v='.$video
            ],
            'PICTURES' => [
                'sd' => 'https://img.youtube.com/vi/'.$video.'/sddefault.jpg',
                'mq' => 'https://img.youtube.com/vi/'.$video.'/mqdefault.jpg',
                'hq' => 'https://img.youtube.com/vi/'.$video.'/hqdefault.jpg',
                'max' => 'https://img.youtube.com/vi/'.$video.'/maxresdefault.jpg'
            ]
        ];
    }

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
            'URL' => null,
            'AUTOPLAY' => 'Y',
            'ANNOTATIONS' => 'N',
            'CONTROLS' => 0,
            'KEYBOARD' => 'N',
            'MUTE' => 'Y',
            'LOOP' => 'Y',
            'FULLSCREEN' => 'N',
            'INFORMATION' => 'N',
            'START' => null,
            'END' => null
        ], $arParams);

        $arParams['CONTROLS'] = Type::toInteger($arParams['CONTROLS']);
        $arParams['CONTROLS'] = ArrayHelper::fromRange([0, 1, 2], $arParams['CONTROLS']);

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

            if (empty($arParams['URL'])) {
                $this->endResultCache();

                return null;
            }

            $arResult = $this->parseUrl($arParams['URL']);

            if (empty($arResult)) {
                $this->endResultCache();

                return null;
            }

            $arResult['CONTROLLED'] = $arParams['CONTROLS'] !== 0 || $arParams['LOOP'] !== 'Y' || $arParams['MUTE'] !== 'Y';

            $oUrl = new Url($arResult['LINKS']['embed']);
            $oUrl->getQuery()->setRange([
                'autoplay' => $arParams['AUTOPLAY'] === 'Y' ? 1 : 0,
                'controls' => $arParams['CONTROLS'],
                'disablekb' => $arParams['KEYBOARD'] === 'Y' ? 0 : 1,
                'mute' => $arParams['MUTE'] === 'Y' ? 1 : 0,
                'loop' => $arParams['LOOP'] === 'Y' ? 1 : 0,
                'fs' => $arParams['FULLSCREEN'] === 'Y' ? 1 : 0,
                'iv_load_policy' => $arParams['ANNOTATIONS'] === 'Y' ? 1 : 3,
                'rel' => 0,
                'showinfo' => $arParams['INFORMATION'] === 'Y' ? 1 : 0,
                'playlist' => $arParams['LOOP'] === 'Y' ? $arResult['ID'] : null
            ]);

            if (Type::isNumeric($arParams['START']))
                $oUrl->getQuery()->set('start', Type::toInteger($arParams['START']));

            if (Type::isNumeric($arParams['END']))
                $oUrl->getQuery()->set('end', Type::toInteger($arParams['END']));

            $arResult['LINKS']['embed'] = $oUrl->build();
            $this->arResult = $arResult;

            unset($oUrl);
            unset($arResult);

            $this->includeComponentTemplate();
        }

        return $this->arResult;
    }
}