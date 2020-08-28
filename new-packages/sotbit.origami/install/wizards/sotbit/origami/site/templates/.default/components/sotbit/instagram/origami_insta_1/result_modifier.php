<?php

if(0 && $arResult["MEDIA"] && $arParams["IMG_DEFAULT"] == 'Y')
{
    foreach($arResult["MEDIA"] as $key => $media)
    {
        $arResult["IMG"] = $media["IMAGE"];
        $arFileImg = CFile::MakeFileArray($arResult["IMG"]);
        if($key == 2)
        {
            $tempFile_430 = $_SERVER['DOCUMENT_ROOT'].'/upload/sotbit.instagram/430/' . $key . '/' . $arResult["LOGIN"]. '.jpg';
            $renderImage_430 = CFile::ResizeImageFile($arFileImg["tmp_name"], $tempFile_430, Array("width" => 430, "height" => 430), BX_RESIZE_IMAGE_PROPORTIONAL);
        }else{
            $tempFile_200 = $_SERVER['DOCUMENT_ROOT'].'/upload/sotbit.instagram/200/' . $key . '/' . $arResult["LOGIN"]. '.jpg';
            $renderImage_200 = CFile::ResizeImageFile($arFileImg["tmp_name"], $tempFile_200, Array("width" => 200, "height" => 200), BX_RESIZE_IMAGE_PROPORTIONAL);
        }

    }
}
?>