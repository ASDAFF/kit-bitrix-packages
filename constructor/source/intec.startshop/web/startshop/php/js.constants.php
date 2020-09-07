<?
    global $APPLICATION;

    $arJSConstants = array(
        'BX_PERSONAL_ROOT' => defined('BX_PERSONAL_ROOT') ? BX_PERSONAL_ROOT : null,
        'SITE_ID' => defined('SITE_ID') ? SITE_ID : null,
        'SITE_DIR' => defined('SITE_DIR') ? SITE_DIR : null,
        'SITE_CHARSET' => defined('SITE_CHARSET') ? SITE_CHARSET : null,
        'SITE_TEMPLATE_ID' => defined('SITE_TEMPLATE_ID') ? SITE_TEMPLATE_ID : null,
        'SITE_TEMPLATE_PATH' => defined('SITE_TEMPLATE_PATH') ? SITE_TEMPLATE_PATH : null,
        'MODULE_DIR' => defined('BX_PERSONAL_ROOT') ? BX_PERSONAL_ROOT.'/modules/intec.startshop' : null
    );

    $APPLICATION->AddHeadString('<script type="text/javascript">var StartshopConstants = '.CUtil::PhpToJSObject($arJSConstants).'; if (typeof Startshop != "undefined") { Startshop.Constants = StartshopConstants; StartshopConstants = undefined; }</script>');
    unset($arJSConstants);
?>