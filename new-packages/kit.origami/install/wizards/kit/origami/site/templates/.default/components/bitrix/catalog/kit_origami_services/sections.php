<?php
use Kit\Origami\Helper\Config;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$arParams["SECTION_ROOT_TEMPLATE"] = $template = Config::get('SECTION_ROOT_TEMPLATE_SERVICES');

if(\KitOrigami::getOfferUrlComponentPath($this, $arResult, $arParams))
{
    include($_SERVER["DOCUMENT_ROOT"]."/".$this->GetFolder()."/element.php");
    return;
}

\KitOrigami::process404($this, $arResult, $arParams);

$this->setFrameMode(true);

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

if($request->get('ajaxFilter') == 'Y')
{
    $GLOBALS['APPLICATION']->RestartBuffer();
}

include($_SERVER["DOCUMENT_ROOT"]."/".$this->GetFolder()."/sections_root.php");
if($request->get('ajaxFilter') == 'Y')
{
    echo \KitOrigami::prepareJSData($this, $arParams);
    die();
}
?>
