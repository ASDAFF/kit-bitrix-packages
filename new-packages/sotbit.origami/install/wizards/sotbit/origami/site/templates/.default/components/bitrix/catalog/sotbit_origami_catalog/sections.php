<?php
use Sotbit\Origami\Helper\Config;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

if(\SotbitOrigami::getOfferUrlComponentPath($this, $arResult, $arParams))
{
    include($_SERVER["DOCUMENT_ROOT"]."/".$this->GetFolder()."/element.php");
    return;
}

\SotbitOrigami::process404($this, $arResult, $arParams);

$this->setFrameMode(true);

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

if($request->get('ajaxFilter') == 'Y')
{
    $GLOBALS['APPLICATION']->RestartBuffer();
}

$arParams["SECTION_ROOT_TEMPLATE"] = $template = Config::get('SECTION_ROOT_TEMPLATE');

switch ($template)
{
    case 'sections':
        include($_SERVER["DOCUMENT_ROOT"]."/".$this->GetFolder()."/sections_root.php");
        break;
    case 'products':
        include($_SERVER["DOCUMENT_ROOT"]."/".$this->GetFolder()."/sections_root_combine.php");
        break;
    case 'combine':
        include($_SERVER["DOCUMENT_ROOT"]."/".$this->GetFolder()."/sections_root_combine.php");
        break;
    default:
        include($_SERVER["DOCUMENT_ROOT"]."/".$this->GetFolder()."/sections_root.php");
}
if($request->get('ajaxFilter') == 'Y')
{
    echo \SotbitOrigami::prepareJSData($this, $arParams);
    die();
}
?>