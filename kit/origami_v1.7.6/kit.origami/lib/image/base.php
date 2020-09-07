<?php
namespace Kit\Origami\Image;

/**
 * Class Base
 *
 * @package Kit\Origami\Image
 * 
 */
abstract class Base{

    protected $resize;
    /**
     * @var int
     */
    protected $smallWidth = 40;
    /**
     * @var int
     */
    protected $smallHeight = 40;
    /**
     * @var int
     */
    protected $mediumWidth = 225;
    /**
     * @var int
     */
    protected $mediumHeight = 180;
    /**
     * @var int
     */
    protected $bigWidth = 0;
    /**
     * @var int
     */
    protected $bigHeight = 0;

    /**
     * @var int
     */
    protected $resizeType = BX_RESIZE_IMAGE_PROPORTIONAL;

    /**
     * @var string
     */
    protected $noImageSmall = '/upload/kit.origami/no_photo_small.svg';

    /**
     * @var string
     */
    protected $noImageMedium = '/upload/kit.origami/no_photo_medium.svg';

    /**
     * @var string
     */
    protected $noImageBig = '/upload/kit.origami/no_photo_big.svg';

    protected $arImages = array();


    protected function collectDetailImages($el = [], $resize = true){
        if($resize){
            $images = $this->prepareDetailImagesWithResize($el);
        }
        else{
            $images = $this->prepareIDetailmagesWithoutResize($el);
        }

        if(!$images){
            $images = [
                0 => [
                    'SMALL' => [
                        'src' => $this->noImageSmall,
                        'SRC' => $this->noImageSmall,
                        'width' => $this->smallWidth,
                        'height' => $this->smallHeight,
                        'ID' => 'empty',
                    ],
                    'MEDIUM' => [
                        'src' => $this->noImageMedium,
                        'SRC' => $this->noImageMedium,
                        'width' => $this->mediumWidth,
                        'height' => $this->mediumHeight,
                        'ID' => 'empty',
                    ],
                    'BIG' => [
                        'src' => $this->noImageBig,
                        'SRC' => $this->noImageBig,
                        'width' => $this->bigWidth,
                        'height' => $this->bigHeight,
                        'ID' => 'empty',
                    ],
                    'ORIGINAL' => [
                        'src' => $this->noImageMedium,
                        'SRC' => $this->noImageMedium,
                        'width' => $this->mediumWidth,
                        'height' => $this->mediumHeight,
                        'ID' => 'empty',
                    ],
                ]
            ];
        }
        return $images;
    }

    protected function collectImages(&$el = [], $resize = true){
        if($resize){
            $images = $this->prepareImagesWithResize($el);
        }
        else{
            $images = $this->prepareImagesWithoutResize($el);
        }
        
        if(!$images){
            $images = [
                0 => [
                    'SMALL' => [
                        'src' => $this->noImageSmall,
                        'SRC' => $this->noImageSmall,
                        'width' => $this->smallWidth,
                        'height' => $this->smallHeight,
                        'ID' => 'empty',
                    ],
                    'MEDIUM' => [
                        'src' => $this->noImageMedium,
                        'SRC' => $this->noImageMedium,
                        'width' => $this->mediumWidth,
                        'height' => $this->mediumHeight,
                        'ID' => 'empty',
                    ],
                    'BIG' => [
                        'src' => $this->noImageBig,
                        'SRC' => $this->noImageBig,
                        'width' => $this->bigWidth,
                        'height' => $this->bigHeight,
                        'ID' => 'empty',
                    ],
                    'ORIGINAL' => [
                        'src' => $this->noImageMedium,
                        'SRC' => $this->noImageMedium,
                        'width' => $this->mediumWidth,
                        'height' => $this->mediumHeight,
                        'ID' => 'empty',
                    ],
                ]
            ];
        }
        return $images;
    }

    /**
     * @param array $el
     *
     * @return array
     */

    protected function prepareImagesWithResize(&$el = []){
        $images = [];
        if($el['PREVIEW_PICTURE'])
        {
            $images[] = [
                'MEDIUM' => $this->resize(
                    $el['PREVIEW_PICTURE']['ID'],
                    [
                        'width' => $this->mediumWidth,
                        'height' => $this->mediumHeight,
                    ],
                    $this->resizeType
                ),
                'SMALL' => $this->resize(
                    $el['PREVIEW_PICTURE']['ID'],
                    [
                        'width' => $this->smallWidth,
                        'height' => $this->smallHeight,
                    ],
                    $this->resizeType
                ),
                'BIG' => $this->resize(
                    $el['PREVIEW_PICTURE']['ID'],
                    [
                        'width' => $this->bigWidth,
                        'height' => $this->bigHeight,
                    ],
                    $this->resizeType
                ),
                'ORIGINAL' => $el['PREVIEW_PICTURE'],
            ];
        }
        elseif($el['DETAIL_PICTURE'])
        {
            $images[] = [
                'MEDIUM' => $this->resize(
                    $el['DETAIL_PICTURE']['ID'],
                    [
                        'width' => $this->mediumWidth,
                        'height' => $this->mediumHeight,
                    ],
                    $this->resizeType
                ),
                'SMALL' => $this->resize(
                    $el['DETAIL_PICTURE']['ID'],
                    [
                        'width' => $this->smallWidth,
                        'height' => $this->smallHeight,
                    ],
                    $this->resizeType
                ),
                'BIG' => $this->resize(
                    $el['DETAIL_PICTURE']['ID'],
                    [
                        'width' => $this->bigWidth,
                        'height' => $this->bigHeight,
                    ],
                    $this->resizeType
                ),
                'ORIGINAL' => $el['DETAIL_PICTURE'],
            ];
        }
        elseif($el['PROPERTIES']['MORE_PHOTO']['VALUE'])
        {
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
        }
        elseif($el['SLIDER'])
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
        
        if($el['PREVIEW_PICTURE'])
        {
            $images[] = [
                'ORIGINAL' => $el['PREVIEW_PICTURE'],
                'SMALL' => $el['PREVIEW_PICTURE'],
                'MEDIUM' => $el['PREVIEW_PICTURE'],
                'BIG' => $el['PREVIEW_PICTURE'],
            ];
        }
        elseif($el['DETAIL_PICTURE'])
        {
            $images[] = [
                'MEDIUM' => $this->resize(
                    $el['DETAIL_PICTURE']['ID'],
                    [
                        'width' => $this->mediumWidth,
                        'height' => $this->mediumHeight,
                    ],
                    $this->resizeType
                ),
                'SMALL' => $this->resize(
                    $el['DETAIL_PICTURE']['ID'],
                    [
                        'width' => $this->smallWidth,
                        'height' => $this->smallHeight,
                    ],
                    $this->resizeType
                ),
                'BIG' => $this->resize(
                    $el['DETAIL_PICTURE']['ID'],
                    [
                        'width' => $this->bigWidth,
                        'height' => $this->bigHeight,
                    ],
                    $this->resizeType
                ),
                'ORIGINAL' => $el['DETAIL_PICTURE'],
            ];
        }
        elseif($el['PROPERTIES']['MORE_PHOTO']['VALUE'])
        {
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
        elseif($el['SLIDER'])
        {
            foreach($el['SLIDER'] as $img){
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
     * @param int   $img
     * @param array $params
     * @param int   $type
     *
     * @return array|mixed
     */
    public function resize($img = 0,$params = ['width' => 100, 'height' => 100], $type = BX_RESIZE_IMAGE_PROPORTIONAL)
    {
        $return = [];
        /*if(is_array($img))
        {
            $img = $img['ID'];
        }*/

        $isArray = is_array($img);

        if($img)
        {
            if($isArray && !isset($img["ORIGINAL_NAME"]))
                $img = $img["ID"];

            if($type === 0 || $type == BX_RESIZE_IMAGE_PROPORTIONAL_ALT)
            {
                $return = \CFile::ResizeImageGet(
                    $img,
                    $params,
                    BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
                    true
                );
            }elseif($type == 1 || $type == BX_RESIZE_IMAGE_PROPORTIONAL)
            {
                $return = \CFile::ResizeImageGet(
                    $img,
                    $params,
                    BX_RESIZE_IMAGE_PROPORTIONAL,
                    true
                );
            }elseif($type == 2 || $type == BX_RESIZE_IMAGE_EXACT)
            {
                $return = \CFile::ResizeImageGet(
                    $img,
                    $params,
                    BX_RESIZE_IMAGE_EXACT,
                    true
                );
            }

            $return['ID'] = $isArray ? $img["ID"] : $img;
            $return['SRC'] = $return['src'];
            $return['WIDTH'] = $return['width'];
            $return['HEIGHT'] = $return['height'];
        }

        return $return;
    }

    /**
     * @return int
     */
    public function getSmallWidth()
    {
        return $this->smallWidth;
    }

    /**
     * @param int $smallWidth
     */
    public function setSmallWidth($smallWidth)
    {
        $this->smallWidth = $smallWidth;
    }

    /**
     * @return int
     */
    public function getSmallHeight()
    {
        return $this->smallHeight;
    }

    /**
     * @param int $smallHeight
     */
    public function setSmallHeight($smallHeight)
    {
        $this->smallHeight = $smallHeight;
    }

    /**
     * @return int
     */
    public function getMediumWidth()
    {
        return $this->mediumWidth;
    }

    /**
     * @param int $mediumWidth
     */
    public function setMediumWidth($mediumWidth)
    {
        $this->mediumWidth = $mediumWidth;
    }

    /**
     * @return int
     */
    public function getMediumHeight()
    {
        return $this->mediumHeight;
    }

    /**
     * @param int $mediumHeight
     */
    public function setMediumHeight($mediumHeight)
    {
        $this->mediumHeight = $mediumHeight;
    }

    /**
     * @return int
     */
    public function getBigWidth()
    {
        return $this->bigWidth;
    }

    /**
     * @param int $bigWidth
     */
    public function setBigWidth($bigWidth)
    {
        $this->bigWidth = $bigWidth;
    }

    /**
     * @return int
     */
    public function getBigHeight()
    {
        return $this->bigHeight;
    }

    /**
     * @param int $bigHeight
     */
    public function setBigHeight($bigHeight)
    {
        $this->bigHeight = $bigHeight;
    }

    /**
     * @return int
     */
    public function getResizeType()
    {
        return $this->resizeType;
    }

    /**
     * @param int $resizeType
     */
    public function setResizeType($resizeType)
    {
        $this->resizeType = $resizeType;
    }

    /**
     * @return string
     */
    public function getNoImageSmall()
    {
        return $this->noImageSmall;
    }

    /**
     * @param string $noImageSmall
     */
    public function setNoImageSmall($noImageSmall)
    {
        $this->noImageSmall = $noImageSmall;
    }

    /**
     * @return string
     */
    public function getNoImageMedium()
    {
        return $this->noImageMedium;
    }

    /**
     * @param string $noImageMedium
     */
    public function setNoImageMedium($noImageMedium)
    {
        $this->noImageMedium = $noImageMedium;
    }

    /**
     * @return string
     */
    public function getNoImageBig()
    {
        return $this->noImageBig;
    }

    /**
     * @param string $noImageBig
     */
    public function setNoImageBig($noImageBig)
    {
        $this->noImageBig = $noImageBig;
    }
}
?>