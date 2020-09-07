<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

global $APPLICATION, $template, $data, $regionProperties;

if (!$template)
    return;
?>
        <? $APPLICATION->IncludeComponent(
            'intec.constructor:template',
            '',
            array(
                'TEMPLATE_ID' => $template->id,
                'DISPLAY' => 'FOOTER',
                'DATA' => $data
            ),
            false,
            array('HIDE_ICONS' => 'Y')
        ) ?>
    </body>
</html>