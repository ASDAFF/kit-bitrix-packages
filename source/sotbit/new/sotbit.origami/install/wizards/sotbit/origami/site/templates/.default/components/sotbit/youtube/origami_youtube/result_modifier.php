<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
if($arResult["VIDEOS"])
{
    foreach($arResult["VIDEOS"] as $video) {
        $arResult["IMG"] = 'https://i.ytimg.com/vi/' . $video["ID"] . '/maxresdefault.jpg';
        $arFileImg = CFile::MakeFileArray($arResult["IMG"]);
        $tempFile_365 = $_SERVER['DOCUMENT_ROOT'].'/upload/sotbit.youtube/365/' . $video["ID"] . '/maxresdefault.jpg';
        $renderImage_365 = CFile::ResizeImageFile($arFileImg["tmp_name"], $tempFile_365, Array("width" => 365, "height" => 205), BX_RESIZE_IMAGE_PROPORTIONAL);
        $tempFile_535 = $_SERVER['DOCUMENT_ROOT'].'/upload/youtube/535/' . $video["ID"] . '/maxresdefault.jpg';
        $renderImage_535 = CFile::ResizeImageFile($arFileImg["tmp_name"], $tempFile_535, Array("width" => 535, "height" => 300), BX_RESIZE_IMAGE_PROPORTIONAL);

    }
}
?>