<?php
namespace intec\template;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use CMain;
use intec\Core;

class Marking
{
    public static function openGraph()
    {
        global $APPLICATION;

        /**
         * @global CMain $APPLICATION
         */

        if (!$APPLICATION->GetPageProperty('og:type'))
            $APPLICATION->SetPageProperty('og:type', 'website');

        if (!$APPLICATION->GetPageProperty('og:title'))
            $APPLICATION->SetPageProperty('og:title', $APPLICATION->GetTitle());

        if (!$APPLICATION->GetPageProperty('og:description'))
            if (!$APPLICATION->GetPageProperty('description'))
                $APPLICATION->SetPageProperty('og:description', $APPLICATION->GetTitle());
            else
                $APPLICATION->SetPageProperty('og:description', $APPLICATION->GetPageProperty('description'));

        if (!$APPLICATION->GetPageProperty('og:image'))
            $APPLICATION->SetPageProperty('og:image', Core::$app->request->getHostInfo().SITE_DIR.'include/logotype.png');

        if (!$APPLICATION->GetPageProperty('og:url'))
            $APPLICATION->SetPageProperty('og:url', Core::$app->request->getHostInfo().$APPLICATION->GetCurUri());
    }
}