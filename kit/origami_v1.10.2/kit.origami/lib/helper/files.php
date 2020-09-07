<?php

namespace Kit\Origami\Helper;

use Bitrix\Main\Page\Asset;
use Kit\Origami\Config\Option;

class Files
{
    public function addHeadFiles()
    {
        $files = json_decode(Option::get('FILES'));
        if ($files) {
            foreach ($files as $file) {
                if (strpos($file, '.css') !== false) {
                    Asset::getInstance()->addCss($file);
                } elseif (strpos($file, '.js') !== false) {
                    Asset::getInstance()->addJs($file);
                }
            }
        }
    }

    public function showCustomCss()
    {
        Asset::getInstance()->addCss(SITE_DIR
            .'include/kit_origami/files/custom.css');
    }

    public function showCustomJs()
    {
        Asset::getInstance()->addJs(SITE_DIR
            .'include/kit_origami/files/custom.js');
    }

    public function showMetrics()
    {
        //Asset::getInstance()->addJs(SITE_DIR .'include/kit_origami/files/metric.js');
        //Asset::getInstance()->addString('1234567890');
//        $content =
//            file_get_contents($_SERVER['DOCUMENT_ROOT'].
//                SITE_DIR.
//                'include/kit_origami/files/metric.php');
//        Asset::getInstance()->addString($content);
    }
}
