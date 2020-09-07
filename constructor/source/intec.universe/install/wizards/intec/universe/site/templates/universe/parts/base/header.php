<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\constructor\models\build\Template;
use intec\template\Properties;

global $data;

/**
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @global array $settings
 * @global Template $template
 */

?>
<?php $data = $APPLICATION->IncludeComponent(
    'intec.constructor:template',
    '',
    array(
        'TEMPLATE_ID' => $template->id,
        'DISPLAY' => 'HEADER',
        'DATA' => [
            'template' => $template
        ],
        'CACHE_USE' => Properties::get('template-cache') ? 'Y' : 'N',
        'CACHE_TIME' => 360000000
    ),
    false,
    array('HIDE_ICONS' => 'Y')
); ?>