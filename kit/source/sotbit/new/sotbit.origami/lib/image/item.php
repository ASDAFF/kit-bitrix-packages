<?php
namespace Sotbit\Origami\Image;

use Sotbit\Origami\Helper\Config;

/**
 * Class Item
 *
 * @package Sotbit\Origami\Image
 * @author  Sergey Danilkin <s.danilkin@sotbit.ru>
 */
class Item extends Base{
    /**
     * @var string
     */
    protected $noImage = '/upload/no_photo.jpg';

    /**
     * Item constructor.
     */
    public function __construct()
    {
        if( Config::get('RESIZE_WIDTH_MEDIUM') > 0){
            $this->mediumWidth = Config::get('RESIZE_WIDTH_MEDIUM');
        }
        else{
            $this->mediumWidth = 180;
        }
        if( Config::get('RESIZE_HEIGHT_MEDIUM') > 0){
            $this->mediumHeight = Config::get('RESIZE_HEIGHT_MEDIUM');
        }
        else{
            $this->mediumHeight = 235;
        }
        if( Config::get('RESIZE_WIDTH_SMALL') > 0){
            $this->smallWidth = Config::get('RESIZE_WIDTH_SMALL');
        }
        else{
            $this->smallWidth = 40;
        }
        if( Config::get('RESIZE_HEIGHT_SMALL') > 0){
            $this->smallHeight = Config::get('RESIZE_HEIGHT_SMALL');
        }
        else{
            $this->smallHeight = 40;
        }
        if( Config::get('RESIZE_TYPE') >= 0){
            $this->resizeType = Config::get('RESIZE_TYPE');
        }
        else{
            $this->resizeType = BX_RESIZE_IMAGE_PROPORTIONAL;
        }
    }

    /**
     * @param array $arResult
     *
     * @return array
     */
    public function prepareImages(&$arResult = [])
    {
        if(!$arResult['JS_OFFERS'])
        {
            $mImages = [];
            $images = $this->collectImages($arResult, $this->isResize());
            foreach ($images as $img)
            {
                $mImages[] = $img['MEDIUM'];
            }
            $arResult['MORE_PHOTO'] = $mImages;
        }
        else{
            foreach($arResult['JS_OFFERS'] as $i => &$offer)
            {
                $offer['PROPERTIES'] = $arResult['OFFERS'][$i]['PROPERTIES'];
                $images = $this->collectImages($offer, $this->isResize());
                $mImages = [];
                foreach ($images as $img)
                {
                    $mImages[] = $img['MEDIUM'];
                }
                $arResult['OFFERS'][$i]['MORE_PHOTO'] = $mImages;
                $arResult['JS_OFFERS'][$i]['MORE_PHOTO'] = $mImages;
            }
        }
        return $arResult;
    }

    protected function collectImages(&$el = [], $resize = true)
    {

        if($resize){
            $images = $this->prepareImagesWithResize($el);
        }
        else{
            $images = $this->prepareImagesWithoutResize($el);
        }

        if(!$images){
            $images = [
                0 => [
                    'MEDIUM' => [
                        'src' => $this->noImageMedium,
                        'SRC' => $this->noImageMedium,
                        'width' => $this->mediumWidth,
                        'height' => $this->mediumHeight,
                        'ID' => 'empty',
                    ],
                    /*'ORIGINAL' => [
                        'src' => $this->noImageMedium,
                        'SRC' => $this->noImageMedium,
                        'width' => $this->mediumWidth,
                        'height' => $this->mediumHeight,
                        'ID' => 'empty',
                    ],*/
                ]
            ];
        }
        return $images;
    }

    protected function prepareImagesWithResize(&$el = [])
    {
        $images = [];

        if($el['PREVIEW_PICTURE'])
        {
            $images[] = [
                'MEDIUM' => $this->resize(
                    $el['PREVIEW_PICTURE'],
                    [
                        'width' => $this->mediumWidth,
                        'height' => $this->mediumHeight,
                    ],
                    $this->resizeType
                ),
                //'ORIGINAL' => $el['PREVIEW_PICTURE'],
            ];
        }elseif($el['DETAIL_PICTURE'])
        {
            $images[] = [
                'MEDIUM' => $this->resize(
                    $el['DETAIL_PICTURE'],
                    [
                        'width' => $this->mediumWidth,
                        'height' => $this->mediumHeight,
                    ],
                    $this->resizeType
                ),
                //'ORIGINAL' => $el['DETAIL_PICTURE'],
            ];
        }

        if($el['PROPERTIES']['MORE_PHOTO']['VALUE']){
            foreach($el['PROPERTIES']['MORE_PHOTO']['VALUE'] as $img)
            {
                $images[] = [
                    'MEDIUM' => $this->resize(
                        $img,
                        [
                            'width' => $this->mediumWidth,
                            'height' => $this->mediumHeight,
                        ],
                        $this->resizeType
                    ),
                    //'ORIGINAL' => \CFile::GetByID($img)->Fetch(),
                ];
            }
        }elseif($el['SLIDER'])
        {
            foreach($el['SLIDER'] as $img)
            {
                $images[] = [
                    'MEDIUM' => $this->resize(
                        $img,
                        [
                            'width' => $this->mediumWidth,
                            'height' => $this->mediumHeight,
                        ],
                        $this->resizeType
                    ),
                    //'ORIGINAL' => $img,
                ];
            }
            unset($el['SLIDER']);
        }

        return $images;
    }


    protected function prepareImagesWithoutResize(&$el = [])
    {
        $images = [];

        if($el['PREVIEW_PICTURE'])
        {
            $images[] = [
                //'ORIGINAL' => $el['PREVIEW_PICTURE'],
                'MEDIUM' => $el['PREVIEW_PICTURE'],
            ];
        }
        elseif($el['DETAIL_PICTURE'])
        {
            $images[] = [
                //'ORIGINAL' => $el['DETAIL_PICTURE'],
                'MEDIUM' => $el['DETAIL_PICTURE'],
            ];
        }

        if($el['PROPERTIES']['MORE_PHOTO']['VALUE'])
        {
            foreach($el['PROPERTIES']['MORE_PHOTO']['VALUE'] as $img)
            {
                $img = \CFile::GetFileArray($img);
                $images[] = [
                    'MEDIUM' => $img,
                    //'ORIGINAL' => $img,
                ];
            }
        }
        elseif($el['SLIDER'])
        {
            foreach($el['SLIDER'] as $img)
            {
                $images[] = [
                    'MEDIUM' => $img,
                    //'ORIGINAL' => $img,
                ];
            }
            unset($el['SLIDER']);
        }

        return $images;
    }

    /**
     * @return bool
     */
    protected function isResize()
    {
        if(Config::get('RESIZE_TYPE') != ""){
            return true;
        }
        return false;
    }
}
?>