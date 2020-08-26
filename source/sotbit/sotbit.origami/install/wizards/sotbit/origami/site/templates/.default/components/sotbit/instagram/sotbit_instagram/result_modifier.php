<?php

if($arResult["MEDIA"] && $arParams["IMG_DEFAULT"] == 'Y')
{
    foreach($arResult["MEDIA"] as $key => $media) {
        $arResult["IMG"] = $media["IMAGE"];
        $arFileImg = CFile::MakeFileArray($arResult["IMG"]);
        $tempFile_370 = $_SERVER['DOCUMENT_ROOT'].'/upload/sotbit.instagram/370/' . $key . '/' . $arResult["LOGIN"]. '.jpg';
        $renderImage_370 = CFile::ResizeImageFile($arFileImg["tmp_name"], $tempFile_370, Array("width" => 370, "height" => 370), BX_RESIZE_IMAGE_PROPORTIONAL);
        $tempFile_540 = $_SERVER['DOCUMENT_ROOT'].'/upload/sotbit.instagram/540/' . $key . '/' . $arResult["LOGIN"]. '.jpg';
        $renderImage_540 = CFile::ResizeImageFile($arFileImg["tmp_name"], $tempFile_540, Array("width" => 540, "height" => 540), BX_RESIZE_IMAGE_PROPORTIONAL);

    }
}
?>