<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)	die();

global $inWidgetConfig;
require_once 'config.php';
$inWidgetConfig = $CONFIG;

$inWidgetConfig["LOGIN"] = $arParams["LOGIN"];
$inWidgetConfig["imgCount"] = (int) $arParams["IMG_COUNT"];

require_once 'classes/InstagramScraper.php';
require_once 'classes/Unirest.php';
require_once 'classes/InWidget.php';

if($this->startResultCache())
{
    try
    {
        if(function_exists('curl_init'))
        {
            $inWidget = new \inWidget\Core;
            $data = $inWidget->getData();

            foreach($data->images as $key => $media)
            {
                $arResult["MEDIA"][$key]["LINK"] = $media->link;
                $arResult["MEDIA"][$key]["IMAGE"] = $media->large;
            }

            $arResult["LOGIN"] = $arParams["LOGIN"];
            $arResult["TITLE"] = $arParams["TITLE"];
            $arResult["SUBSCRIBE"] = "https://www.instagram.com/".$arParams["LOGIN"]."/";
        }else{
            echo 'Error curl_init()';
        }

    }
    catch(\Exception $e)
    {
        echo $e->getMessage();
    }

    $this->IncludeComponentTemplate();
}
?>