<?php
namespace Kit\Origami\Image;
use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Loader;
use Kit\Origami\Config\Option;
use Kit\Origami\Helper\Config;

class Basket extends Base {
    public function __construct() {
        $this->mediumWidth = 160;
        $this->mediumHeight = 160;
        $this->resizeType = BX_RESIZE_IMAGE_PROPORTIONAL;
    }

    public function getImages($ids = []){

        Loader::includeModule('iblock');
        Loader::includeModule('catalog');

        $return = [];


        $productsIds = [];
        $offersIds = [];
        $colorProps = [];
        $morePhotoProps = [];
        $previewPicture = [];
        $detailPicture = [];

        if($ids)
        {
            $rs = \CIBlockElement::GetList([], ['=ID' => $ids, '!IBLOCK_ID'=>Option::get('IBLOCK_ID')],
                false,
                false, [
                    'ID',
                    'IBLOCK_ID',
                    //'PREVIEW_PICTURE',
                    //'DETAIL_PICTURE',
                    'PROPERTY_'.Option::get('COLOR'),
                    //'PROPERTY_MORE_PHOTO'
                ]);

            while ($el = $rs->fetch())
            {
                $offersIds[] = $el['ID'];
                $colorProps[$el['ID']] = $el['PROPERTY_'.Option::get('COLOR').'_VALUE'];
                //$morePhotoProps[$el['ID']][] = $el['PROPERTY_MORE_PHOTO_VALUE'];
                //$previewPicture[$el['ID']] = $el['PREVIEW_PICTURE'];
                //$detailPicture[$el['ID']] = $el['DETAIL_PICTURE'];
            }

            if ($offersIds)
            {
                $arSku = \CCatalogSku::getProductList($offersIds);

                foreach($arSku as $key=> $sku)
                {
                    $productsIds[$key] = $sku["ID"];
                }
            }
        }

        if ($productsIds)
        {
            $images = [];
            $rs = \CIBlockElement::GetList([], ['=ID' => $productsIds],
                false,
                false, [
                    'ID',
                    'IBLOCK_ID',
                    'PREVIEW_PICTURE',
                    'DETAIL_PICTURE',
                    'PROPERTY_MORE_PHOTO',
                ]);

            while($el = $rs->Fetch())
            {
                if (!$images[$el['ID']])
                {
                    $images[$el['ID']] = [
                        'PREVIEW_PICTURE' => [],
                        'DETAIL_PICTURE'  => [],
                        'MORE_PHOTO'      => [],
                    ];
                }
                if (!$images[$el['ID']]['PREVIEW_PICTURE'] && $el['PREVIEW_PICTURE'])
                {
                    //$file = \CFile::GetByID($el['PREVIEW_PICTURE'])->Fetch();
                    $file = \CFile::GetFileArray($el['PREVIEW_PICTURE']);
                    $images[$el['ID']]['PREVIEW_PICTURE'] = $file;
                }elseif(!$images[$el['ID']]['DETAIL_PICTURE'] && $el['DETAIL_PICTURE'])
                {
                    //$file = \CFile::GetByID($el['DETAIL_PICTURE'])->Fetch();
                    $file = \CFile::GetFileArray($el['DETAIL_PICTURE']);
                    $images[$el['ID']]['DETAIL_PICTURE'] = $file;
                }

                if ($el['PROPERTY_MORE_PHOTO_VALUE'])
                {
                    //$file = \CFile::GetByID($el['PROPERTY_MORE_PHOTO_VALUE'])->Fetch();
                    $file = \CFile::GetFileArray($el['PROPERTY_MORE_PHOTO_VALUE']);
                    $images[$el['ID']]['MORE_PHOTO'][] = $file;
                }
            }

            foreach ($productsIds as $offerId => $productsId)
            {
                if ($images[$productsId])
                {
                    $return[$offerId] = $images[$productsId];
                    $return[$offerId]['PROP_COLOR'] = $colorProps[$offerId];
                    $return[$offerId]['PROP_MORE_PHOTO'] = $morePhotoProps[$offerId];
                    $return[$offerId]['PROP_PREVIEW_PICTURE'] = $previewPicture[$offerId];
                    $return[$offerId]['PROP_DETAIL_PICTURE'] = $detailPicture[$offerId];
                }
            }
        }

        return $return;
    }

    public function changeImages($row = [],$images = []){
        if($images['PROP_PREVIEW_PICTURE'])
        {
            $img = $this->resize(
                $images['PROP_PREVIEW_PICTURE'],['width' => $this->getMediumWidth(),'height' => $this->getMediumHeight()],$this->getResizeType()
            );
            if($img['SRC']){
                $row['PREVIEW_PICTURE_SRC'] = $img['SRC'];
            }
        }
        elseif($images['PROP_DETAIL_PICTURE'])
        {
            $img = $this->resize(
                $images['PROP_DETAIL_PICTURE'],['width' => $this->getMediumWidth(),'height' => $this->getMediumHeight()],$this->getResizeType()
            );
            if($img['SRC']){
                $row['PREVIEW_PICTURE_SRC'] = $img['SRC'];
            }
        }
        elseif($images['PROP_MORE_PHOTO'][0])
        {
            $img = $this->resize(
                $images['PROP_MORE_PHOTO'][0],['width' => $this->getMediumWidth(),'height' => $this->getMediumHeight()],$this->getResizeType()
            );
            if($img['SRC'])
            {
                $row['PREVIEW_PICTURE_SRC'] = $img['SRC'];
            }
        }
        else{
            $rowTmp = $row;
            $rowTmp['PREVIEW_PICTURE'] = $images['PREVIEW_PICTURE'];
            $rowTmp['DETAIL_PICTURE'] = $images['DETAIL_PICTURE'];
            $rowTmp['MORE_PHOTO'] = $images['MORE_PHOTO'];
            if($images['PROP_COLOR']){
                /*$rowTmp['OFFERS'] = [
                    0 => [
                        'MORE_PHOTO' => 1
                    ]
                ];*/
                $rowTmp['OFFERS'][0]['PROPERTIES'][Config::get('COLOR')]['VALUE'] = $images['PROP_COLOR'];
            }

            $rowTmp = \KitOrigami::changeColorImages($rowTmp, 'preview');


            if($rowTmp['OFFERS'][0]['MORE_PHOTO'][0])
            {
                $rowTmp['OFFERS'][0]['MORE_PHOTO'][0] = $this->resize(
                    $rowTmp['OFFERS'][0]['MORE_PHOTO'][0],['width' => $this->getMediumWidth(),'height' => $this->getMediumHeight()],$this->getResizeType()
                );
            }
            if($rowTmp['OFFERS'][0]['MORE_PHOTO'][0]['SRC'])
            {
                $row['PREVIEW_PICTURE_SRC'] = $rowTmp['OFFERS'][0]['MORE_PHOTO'][0]['SRC'];
            }
        }

        return $row;
    }
}
?>