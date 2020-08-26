<?php

namespace Kit\Origami\Helper;

use Bitrix\Iblock\PropertyTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\FileTable;
use Bitrix\Main\Loader;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;

class Color
{
    /**
     * @var \Bitrix\Main\ORM\Data\DataManager|null
     */
    private static $entity = null;
    /**
     * @var null
     */
    private static $instance = null;
    private static $propId = null;

    public static function getInstance($lid)
    {
        if (null === self::$instance) {
            self::$instance = new self($lid);
        }

        return self::$instance;
    }

    private function __clone()
    {
    }

    private function __construct($lid)
    {
        Loader::includeModule('highloadblock');
        $iblock = Config::get('IBLOCK_ID', $lid);
        $oIblock = \CCatalogSku::GetInfoByIBlock($iblock)['IBLOCK_ID'];
        $propCode = Config::get('COLOR', $lid);

        $prop = PropertyTable::getList([
            'filter' => [
                'IBLOCK_ID' => $oIblock,
                'CODE'      => $propCode,
            ],
            'select' => ['ID','USER_TYPE_SETTINGS'],
            'limit'  => 1,
        ])->fetch();

        $settings = unserialize($prop['USER_TYPE_SETTINGS']);

        if (!$settings['TABLE_NAME'])
        {
            return false;
        }
        self::setPropId($prop['ID']);

        $HL = \Bitrix\Highloadblock\HighloadBlockTable::getList([
            "filter" => [
                'TABLE_NAME' => $settings['TABLE_NAME'],
            ],
            'limit'  => 1,
        ])->Fetch();

        if ($HL['ID'] > 0)
        {
            $HLEntity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($HL)->getDataClass();
            self::$entity = $HLEntity;
        }
    }

    /**
     * @param array $photos
     *
     * @return array
     */
    public function findColors($photos = [], $slider = true)
    {
        $return = [];

        try {
            $descriptions = $this->getDescriptions($photos, $slider);
            $colors = $this->findColorsHL(array_keys($descriptions));

            foreach ($descriptions as $color => $files)
            {
                if(isset($colors[$color]['UF_XML_ID']))
                    $return[$colors[$color]['UF_XML_ID']] = $files;            }
        } catch (ObjectPropertyException $e) {
        } catch (ArgumentException $e) {
        } catch (SystemException $e) {
        }

        return $return;
    }

    /**
     * @param array $photos
     *
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    protected function getDescriptions($photos = [], $slider = true)
    {
        $return = array();
        $files = $filesTmp =  array();

        if ($photos)
        {
            foreach ($photos as $photo)
            {
                if(isset($photo['ID']))
                {
                    $files[$photo['ID']] = $photo;
                    if($photo["DESCRIPTION"] && $photo['ID'] > 0)
                    {
                        $desc = explode('_', $photo['DESCRIPTION']);

                        if (!$desc[1])
                        {
                            $desc[1] = 0;
                        }

                        $return[strtolower($desc[0])][$desc[1]] = $files[$photo['ID']];

                    }elseif ($photo['ID'] > 0)
                    {
                        $filesTmp[$photo['ID']] = $photo;
                    }
                }

            }
        }

        if ($filesTmp)
        {
            $rs = FileTable::getList([
                'filter' => ['ID' => array_keys($filesTmp)],
                'select' => ['ID', 'DESCRIPTION'],
            ]);

            while ($file = $rs->fetch())
            {
                if($file['DESCRIPTION'])
                {
                    $desc = explode('_', $file['DESCRIPTION']);

                    if (!$desc[1])
                    {
                        $desc[1] = 0;
                    }
                    $return[strtolower($desc[0])][$desc[1]] = $files[$file['ID']];
                }
            }
        }



        $files = array_merge($files, $filesTmp);

        foreach ($return as $color => $files)
        {
            ksort($files);
            $return[$color] = array_values($files);
        }

        if(!$slider)
        {
            foreach ($return as $color => $files)
            {
                array_splice($files, 1);
                $return[$color] = array_values($files);
            }
        }

        return $return;
    }

    /**
     * @param array $descriptions
     *
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    protected function findColorsHL($descriptions = [])
    {
        $return = [];
        $colorsNames = $this->findColorsNames($descriptions);
        $entity = self::getEntity();
        if (!is_null($entity))
        {
            $rs = $entity::getList(
                [
                    'filter' => [
                        'UF_NAME' => $colorsNames,
                    ],
                ]
            );
            while ($color = $rs->fetch())
            {
                $return[strtolower($color['UF_NAME'])] = $color;
            }
        }

        return $return;
    }

    /**
     * @param array $descriptions
     *
     * @return array
     */
    protected function findColorsNames($descriptions = [])
    {
        $return = [];
        foreach ($descriptions as $name) {
            $return[] = $name;
        }
        $return = array_unique($return);

        return $return;
    }

    /**
     * @return \Bitrix\Main\ORM\Data\DataManager|null
     */
    public static function getEntity()
    {
        return self::$entity;
    }

    /**
     * @param array $arResult
     * @param array $arParams
     *
     * @return array
     */
    public static function changePropColorView($arResult = [], $arParams = [])
    {
        $return = ['RESULT' => $arResult,'PARAMS' => $arParams];
        if($arResult['ID'] > 0){
            $typeEl = Config::get('PROP_COLOR_TYPE_ELEMENT_'.$arResult['TEMPLATE']);
        }
        else{
            $typeSect = Config::get('PROP_COLOR_TYPE_SECTION');
        }
        if ($typeEl == 'offer_image') {
            $return = self::changePropColorViewElement($arResult, $arParams);
        }
        if ($typeSect == 'offer_image') {
            $return = self::changePropColorViewSection($arResult, $arParams);
        }

        return $return;
    }

    /**
     * @param array $arResult
     * @param array $arParams
     *
     * @return array
     */
    private static function changePropColorViewElement($arResult = [], $arParams = [])
    {
        if ($arResult['JS_OFFERS']) {
            $codePropColor = Config::get('COLOR');
            $idPropColor = self::getPropId();
            foreach ($arResult['JS_OFFERS'] as $offer) {
                $img = $offer['SLIDER'][0];
                $colorValue = $offer['TREE']['PROP_'.$idPropColor];
                if (
                    $colorValue
                    && $arResult['SKU_PROPS'][$codePropColor]['VALUES'][$colorValue]['PICT']
                ) {
                    if($img['SMALL']['SRC'])
                        $arResult['SKU_PROPS'][$codePropColor]['VALUES'][$colorValue]['PICT'] = $img['SMALL'];
                }
            }
        }

        return ['RESULT' => $arResult,'PARAMS' => $arParams];
    }

    /**
     * @param array $arResult
     * @param array $arParams
     *
     * @return array
     */
    private static function changePropColorViewSection($arResult = [], $arParams = [])
    {
        if ($arResult['ITEM']['OFFERS'])
        {
            $codePropColor = Config::get('COLOR');
            $idPropColor = self::getPropId();
            $Img = new \Kit\Origami\Image\Item();
            foreach ($arResult['ITEM']['OFFERS'] as $offer)
            {
                $img = $Img->resize(
                    $offer['MORE_PHOTO'][0]['ID'],['width' => $Img->getSmallWidth(),'height' => $Img->getSmallHeight()],$Img->getResizeType()
                );
                $colorValue = $offer['TREE']['PROP_'.$idPropColor];
                if (
                    $colorValue
                    && $arParams['SKU_PROPS'][$codePropColor]['VALUES'][$colorValue]['PICT']
                ) {
                    if($img['SRC'])
                        $arParams['SKU_PROPS'][$codePropColor]['VALUES'][$colorValue]['PICT'] = $offer['MORE_PHOTO'][0];
                }
            }
        }
        return ['RESULT' => $arResult,'PARAMS' => $arParams];
    }
    /**
     * @return null|int
     */
    public static function getPropId()
    {
        return self::$propId;
    }

    /**
     * @param null|int $propId
     */
    public static function setPropId($propId)
    {
        self::$propId = $propId;
    }

}