<?php
namespace Sotbit\Origami\Image;

use Sotbit\Origami\Helper\Config;

/**
 * Class Element
 *
 * @package Sotbit\Origami\Image
 * @author  Sergey Danilkin <s.danilkin@kit.ru>
 */
class Element extends Base{
    /**
     * @var string
     */
    protected $noImage = '/upload/no_photo.jpg';

    /**
     * Element constructor.
     *
     * @param string $template
     */
    public function __construct($template = '') {

        if( Config::get('RESIZE_WIDTH_BIG_'.$template) > 0){
            $this->bigWidth = Config::get('RESIZE_WIDTH_BIG_'.$template);
        }
        else{
            $this->bigWidth = 1500;
        }
        if( Config::get('RESIZE_HEIGHT_BIG_'.$template) > 0){
            $this->bigHeight = Config::get('RESIZE_HEIGHT_BIG_'.$template);
        }
        else{
            $this->bigHeight = 1500;
        }
        if( Config::get('RESIZE_WIDTH_MEDIUM_'.$template) > 0){
            $this->mediumWidth = Config::get('RESIZE_WIDTH_MEDIUM_'.$template);
        }
        else{
            $this->mediumWidth = 525;
        }
        if( Config::get('RESIZE_HEIGHT_MEDIUM_'.$template) > 0){
            $this->mediumHeight = Config::get('RESIZE_HEIGHT_MEDIUM_'.$template);
        }
        else{
            $this->mediumHeight = 555;
        }
        if( Config::get('RESIZE_WIDTH_SMALL_'.$template) > 0){
            $this->smallWidth = Config::get('RESIZE_WIDTH_SMALL_'.$template);
        }
        else{
            $this->smallWidth = 80;
        }
        if( Config::get('RESIZE_HEIGHT_SMALL_'.$template) > 0){
            $this->smallHeight = Config::get('RESIZE_HEIGHT_SMALL_'.$template);
        }
        else{
            $this->smallHeight = 80;
        }
        if( Config::get('RESIZE_TYPE_'.$template) >= 0){
            $this->resizeType = Config::get('RESIZE_TYPE_'.$template);
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
        if(!$arResult['JS_OFFERS']){
            $arResult['MORE_PHOTO'] = $this->collectImages($arResult, $this->isResize());
        }
        else{
            foreach($arResult['JS_OFFERS'] as $i => $offer){
                $offer['PROPERTIES'] = $arResult['OFFERS'][$i]['PROPERTIES'];
                $images = $this->collectImages($offer, $this->isResize());
                $arResult['OFFERS'][$i]['MORE_PHOTO'] = $images;

                $arResult['JS_OFFERS'][$i]['MORE_PHOTO'] = $images;
                $arResult['JS_OFFERS'][$i]['SLIDER'] = $images;
            }
        }
        return $arResult;
    }

    /**
     * @param array $el
     *
     * @return array
     */
    protected function prepareImagesWithResize(&$el = [])
    {
        $images = [];
        $arID = [];

        if($el['DETAIL_PICTURE'])
        {
            if(isset($el['DETAIL_PICTURE']['ID']))
                $arID[$el['DETAIL_PICTURE']['ID']] = $el['DETAIL_PICTURE']['ID'];
            else
                $arID[$el['DETAIL_PICTURE']] = $el['DETAIL_PICTURE'];

            $images[] = [
                'MEDIUM' => $this->resize(
                    $el['DETAIL_PICTURE'],
                    [
                        'width' => $this->mediumWidth,
                        'height' => $this->mediumHeight,
                    ],
                    $this->resizeType
                ),
                'SMALL' => $this->resize(
                    $el['DETAIL_PICTURE'],
                    [
                        'width' => $this->smallWidth,
                        'height' => $this->smallHeight,
                    ],
                    $this->resizeType
                ),
                'BIG' => $this->resize(
                    $el['DETAIL_PICTURE'],
                    [
                        'width' => $this->bigWidth,
                        'height' => $this->bigHeight,
                    ],
                    $this->resizeType
                ),
                'ORIGINAL' => $el['DETAIL_PICTURE'],
            ];
        }elseif($el['PREVIEW_PICTURE'])
        {
            if(isset($el['PREVIEW_PICTURE']['ID']))
                $arID[$el['PREVIEW_PICTURE']['ID']] = $el['PREVIEW_PICTURE']['ID'];
            else
                $arID[$el['PREVIEW_PICTURE']] = $el['PREVIEW_PICTURE'];

            $images[] = [
                'MEDIUM' => $this->resize(
                    $el['PREVIEW_PICTURE'],
                    [
                        'width' => $this->mediumWidth,
                        'height' => $this->mediumHeight,
                    ],
                    $this->resizeType
                ),
                'SMALL' => $this->resize(
                    $el['PREVIEW_PICTURE'],
                    [
                        'width' => $this->smallWidth,
                        'height' => $this->smallHeight,
                    ],
                    $this->resizeType
                ),
                'BIG' => $this->resize(
                    $el['PREVIEW_PICTURE'],
                    [
                        'width' => $this->bigWidth,
                        'height' => $this->bigHeight,
                    ],
                    $this->resizeType
                ),
                'ORIGINAL' => $el['PREVIEW_PICTURE'],
            ];
        }

        if($el['PROPERTIES']['MORE_PHOTO']['VALUE'])
        {
            foreach($el['PROPERTIES']['MORE_PHOTO']['VALUE'] as $img)
            {
                if(isset($img['ID']) && isset($arID[$img['ID']]))
                    continue;
                elseif(is_int($img) && isset($arID[$img]))
                    continue;

                $images[] = [
                    'MEDIUM' => $this->resize(
                        $img,
                        [
                            'width' => $this->mediumWidth,
                            'height' => $this->mediumHeight,
                        ],
                        $this->resizeType
                    ),
                    'SMALL' => $this->resize(
                        $img,
                        [
                            'width' => $this->smallWidth,
                            'height' => $this->smallHeight,
                        ],
                        $this->resizeType
                    ),
                    'BIG' => $this->resize(
                        $img,
                        [
                            'width' => $this->bigWidth,
                            'height' => $this->bigHeight,
                        ],
                        $this->resizeType
                    ),
                    'ORIGINAL' => \CFile::GetFileArray($img),
                ];
            }
        }elseif($el['SLIDER'])
        {
            foreach($el['SLIDER'] as $img)
            {
                if(isset($img['ID']) && isset($arID[$img['ID']]))
                    continue;
                elseif(is_int($img) && isset($arID[$img]))
                    continue;

                $images[] = [
                    'MEDIUM' => $this->resize(
                        $img,
                        [
                            'width' => $this->mediumWidth,
                            'height' => $this->mediumHeight,
                        ],
                        $this->resizeType
                    ),
                    'SMALL' => $this->resize(
                        $img,
                        [
                            'width' => $this->smallWidth,
                            'height' => $this->smallHeight,
                        ],
                        $this->resizeType
                    ),
                    'BIG' => $this->resize(
                        $img,
                        [
                            'width' => $this->bigWidth,
                            'height' => $this->bigHeight,
                        ],
                        $this->resizeType
                    ),
                    'ORIGINAL' => $img,
                ];
            }
        }

        return $images;
    }

    /**
     * @param array $el
     *
     * @return array
     */
    protected function prepareImagesWithoutResize(&$el = [])
    {
        $images = [];
        $arID = [];

        if($el['DETAIL_PICTURE'])
        {
            $arID[$el['DETAIL_PICTURE']['ID']] = $el['DETAIL_PICTURE']['ID'];
            $images[] = [
                'MEDIUM' => $el['DETAIL_PICTURE'],
                'SMALL' => $el['DETAIL_PICTURE'],
                'BIG' => $el['DETAIL_PICTURE'],
                'ORIGINAL' => $el['DETAIL_PICTURE'],
            ];
        }elseif($el['PREVIEW_PICTURE']){
            $arID[$el['PREVIEW_PICTURE']['ID']] = $el['PREVIEW_PICTURE']['ID'];
            $images[] = [
                'MEDIUM' => $el['PREVIEW_PICTURE'],
                'SMALL' => $el['PREVIEW_PICTURE'],
                'BIG' => $el['PREVIEW_PICTURE'],
                'ORIGINAL' => $el['PREVIEW_PICTURE'],
            ];
        }

        if($el['PROPERTIES']['MORE_PHOTO']['VALUE'])
        {
            foreach($el['PROPERTIES']['MORE_PHOTO']['VALUE'] as $img)
            {
                if(isset($arID[$img]))
                    continue;

                $img1 = \CFile::GetFileArray($img);
                $images[] = [
                    'MEDIUM' => $img1,
                    'SMALL' => $img1,
                    'BIG' => $img1,
                    'ORIGINAL' => $img1,
                ];
            }
        }elseif($el['SLIDER'])
        {
            foreach($el['SLIDER'] as $img)
            {
                if(isset($img['ID']) && isset($arID[$img['ID']]))
                    continue;
                elseif(is_int($img) && isset($arID[$img]))
                    continue;

                $images[] = [
                    'MEDIUM' => $img,
                    'SMALL' => $img,
                    'BIG' => $img,
                    'ORIGINAL' => $img,
                ];
            }
        }

        return $images;
    }

    /**
     * @param string $template
     *
     * @return bool
     */
    protected function isResize($template = ''){
        if(Config::get('RESIZE_TYPE_'.$template) != ""){
            return true;
        }
        return false;
    }
}
?>