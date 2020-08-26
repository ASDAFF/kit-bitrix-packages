<?

namespace Kit\Origami\Sale\Basket;

use Bitrix\Catalog\GroupTable;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Result;
use Bitrix\Main\SystemException;
use Bitrix\Sale\Basket;
use Bitrix\Sale\BasketItem;
use Bitrix\Sale\Fuser;
use Bitrix\Main\Error;
use Kit\Origami\Config\Option;

abstract class Base
{
    /**
     * @var Basket
     */
    protected $basket;

    /**
     * @var int
     */
    protected $id = 0;

    /**
     * @var int
     */
    protected $qnt = 1;

    /**
     * @var array
     */
    protected $props = [];

    /**
     * @var array
     */
    protected $price = [];

    protected $lid = SITE_ID;

    /**
     * Base constructor.
     *
     * @param bool|string $lid
     */
    public function __construct($lid = SITE_ID)
    {
        try {
            Loader::includeModule('sale');
            $this->basket = Basket::loadItemsForFUser(Fuser::getId(), $lid);
        } catch (LoaderException $e) {
            print_r($e->getTraceAsString());
        }
        $this->lid = $lid;
    }

    public function add()
    {
        global $APPLICATION;
        $addSuccess = false;

        $result = new Result();
        //$basketItem = $this->basket->createItem('catalog', $this->getId());
        try {
            if (!$this->getId()) {
                throw new SystemException('Not id');
            }
        } catch (SystemException $e) {
            $result->addError(new Error($e->getTraceAsString()));
        }

        try {

            $el = $this->getElInfo();
            $props = $this->getPropsValues($el);
            $arProps = array();
            //$arRewriteFields["DETAIL_PAGE_URL"] = $el['fields']['DETAIL_PAGE_URL'];
            $arRewriteFields["NAME"] = $el['fields']['NAME'];

            $arRewriteFields["DELAY"] = "N";
            $arRewriteFields["CAN_BUY"] = "Y";

            $this->beforeAddItem($arRewriteFields);

            if ($props)
            {
                foreach ($props as $code => $prop)
                {
                    $arProps[] = array('NAME'  => $prop['NAME'],
                        'CODE'  => $code,
                        'VALUE' => $prop['VALUE'],
                        'SORT'  => $prop['SORT']);
                }
            }

            if(!Add2BasketByProductID($this->getId(), $this->getQnt(), $arRewriteFields, $arProps))
            {
                if ($ex = $APPLICATION->GetException())
                {
                    print_r($ex->GetString());
                }
                else
                {
                    print_r("Error basket");

                }
            } else $addSuccess = true;



            /*$el = $this->getElInfo();
            $props = $this->getPropsValues($el);
            $price = $this->getPrice();



            Add2BasketByProductID($productID, $QUANTITY, $arRewriteFields, $product_properties)


            $basketItem->setField('NAME', $el['fields']['NAME']);
            $basketItem->setField(
                'DETAIL_PAGE_URL',
                $el['fields']['DETAIL_PAGE_URL']
            );
            $basketItem->setField('PRODUCT_XML_ID', $el['fields']['XML_ID']);
            $basketItem->setField('QUANTITY', $this->getQnt());
            $basketItem->setField('CURRENCY', $price['CURRENCY']);
            $basketItem->setField('PRICE', $price['PRICE']);
            $basketItem->setField('BASE_PRICE', $price['BASE_PRICE']);
            $basketItem->setField('DISCOUNT_PRICE', $price['DISCOUNT']);
            $basketItem->setField('DISCOUNT_VALUE', $price['PERCENT'].'%');
            $basketItem->setField('PRODUCT_PRICE_ID', $price['ID']);
            $basketItem->setField('PRICE_TYPE_ID', $price['PRICE_TYPE_ID']);
            $basketItem->setField('NOTES', $this->getPriceName());
            $basketItem->setField('PRODUCT_PROVIDER_CLASS', '\Bitrix\Catalog\Product\CatalogProvider');
            $this->beforeAddItem($basketItem);
            $basketItem->save();
            //$basketItem->setField('PRODUCT_PROVIDER_CLASS', '\Bitrix\Catalog\Product\CatalogProvider');

            try {
                if ($props) {
                    $basketPropertyCollection
                        = $basketItem->getPropertyCollection();
                    foreach ($props as $code => $prop) {
                        $basketPropertyCollection->setProperty(
                            [
                                [
                                    'NAME'  => $prop['NAME'],
                                    'CODE'  => $code,
                                    'VALUE' => $prop['VALUE'],
                                    'SORT'  => 100,
                                ],
                            ]
                        );
                    }
                    $basketPropertyCollection->save();
                }


            } catch (ArgumentException $e) {
                echo $e->getTraceAsString();
            } catch (NotImplementedException $e) {
                echo $e->getTraceAsString();
            }*/
        } catch (ArgumentOutOfRangeException $e) {
            $result->addError(new Error($e->getTraceAsString()));
        }
        /*$rs = $this->basket->save();
        if (!$rs->isSuccess()) {
            print_r($rs->getErrorMessages());
        }*/
        return $addSuccess;
    }

    public function remove()
    {
    }

    protected function getPropsValues($el = [])
    {
        Loader::includeModule('iblock');
        $return = [];
        $props = $this->getProps();
        if ($el['props']) {
            foreach ($el['props'] as $code => $prop) {
                if (!in_array($code, $props)) {
                    continue;
                }

                $dbProp = \CIBlockElement::GetProperty($el["fields"]["IBLOCK_ID"], $this->getId(), array("sort" => "asc"), Array("CODE"=>$code));

                if($arProp = $dbProp->Fetch())
                    $sort = $arProp["SORT"];

                $return[$code] = [
                    'NAME'  => $prop['NAME'],
                    'VALUE' => $prop['VALUE'],
                    'SORT' => $sort
                ];


                if ($prop['USER_TYPE_SETTINGS']['TABLE_NAME']) {
                    $HL = HL\HighloadBlockTable::getList(
                        [
                            "filter" => [
                                'TABLE_NAME' => $prop['USER_TYPE_SETTINGS']['TABLE_NAME'],
                            ],
                        ]
                    )->Fetch();

                    $HLEntity = HL\HighloadBlockTable::compileEntity($HL)
                        ->getDataClass();
                    $HLProp = $HLEntity::getList(
                        [
                            'select' => [
                                'UF_NAME',
                            ],
                            'filter' => ['UF_XML_ID' => $prop['VALUE']],
                            'order'  => [],
                            'limit'  => 1,
                        ]
                    )->fetch();

                    $return[$code] = [
                        'NAME'  => $prop['NAME'],
                        'VALUE' => $HLProp['UF_NAME'],
                        'SORT' => $sort
                    ];
                }
            }
        }

        return $return;
    }

    protected function getElInfo()
    {
        $return = [
            'fields' => [],
            'props'  => [],
        ];
        try {
            Loader::includeModule('iblock');
            Loader::includeModule('catalog');
        } catch (LoaderException $e) {
            print_r($e->getTraceAsString());
        }

        try {
            if (!$this->getId()) {
                throw new SystemException('Bad id');
            }

            $select = [
                "ID",
                "IBLOCK_ID",
                "XML_ID",
                "NAME",
                "DETAIL_PAGE_URL",
            ];

            $props = $this->getProps();
            if ($props) {
                foreach ($props as $prop) {
                    $select[] = 'PROPERTY_'.$prop;
                }
            }

            $Offer = new \Kit\Origami\Helper\Offer($this->lid);

            $rs = \CIBlockElement::GetList(
                [],
                ['ID' => $this->getId()],
                false,
                false,
                $select
            );
            while ($elem = $rs->GetNextElement()) {
                $return['fields'] = $elem->GetFields();
                $return['props'] = $elem->GetProperties();
                //if(!$return['fields']['DETAIL_PAGE_URL'])
                {
                    $sku = \CCatalogSku::getProductList($return['fields']['ID']);
                    if($sku[$return['fields']['ID']]['ID']){//offer
                        $rsP = \CIBlockElement::GetList(
                            [],
                            ['ID' => $sku[$return['fields']['ID']]['ID']],
                            false,
                            false,
                            [
                                "ID",
                                "IBLOCK_ID",
                                "NAME",
                                "DETAIL_PAGE_URL",
                            ]
                        );
                        if($elemP = $rsP->GetNextElement()) {
                            $fields = $elemP->GetFields();
                            $return['fields']['DETAIL_PAGE_URL'] = $fields['DETAIL_PAGE_URL'];

                            $tmpResult = [
                                'ID' => $fields['ID'],
                                'NAME' => $fields['NAME'],
                                'OFFERS' => [
                                    0 => [
                                        'ID' => $return['fields']['ID'],
                                        'NAME' => $return['fields']['NAME'],
                                    ]
                                ]
                            ];
                            $return['fields']['NAME'] = $Offer->changeText($tmpResult,$this->getProps());

                        }
                    }
                }
            }

            return $return;
        } catch (SystemException $e) {
            print_r($e->getTraceAsString());
        }
    }

    protected function getPriceName()
    {
        $price = $this->getPrice();
        $group = GroupTable::getList(
            [
                'select' => [
                    'NAME',
                    'LANG.NAME',
                ],
                'filter' => [
                    'ID'        => $price['PRICE_TYPE_ID'],
                    'LANG.LANG' => LANGUAGE_ID,
                ],
            ]
        )->fetch();
        if ($group['CATALOG_GROUP_LANG_NAME']) {
            return $group['CATALOG_GROUP_LANG_NAME'];
        } elseif ($group['NAME']) {
            return $group['NAME'];
        }

        return '';
    }


    /**
     * @return bool|mixed
     */
    protected function isExist()
    {
        $return = false;

        foreach ($this->basket as $basketItem) {
            if ($basketItem->getField('PRODUCT_ID') == $this->getId()) {
                $return = $basketItem;
                break;
            }
        }

        return $return;
    }

    /**
     * @param BasketItem $basketItem
     */
    protected function beforeAddItem(&$basketItem)
    {

    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getQnt()
    {
        return $this->qnt;
    }

    /**
     * @param int $qnt
     */
    public function setQnt($qnt)
    {
        $ratio = \Bitrix\Catalog\MeasureRatioTable::getList(
            array(
                'select' => array('RATIO', 'PRODUCT_ID'),
                'filter' => array('PRODUCT_ID' => $this->getId())
            )
        )->Fetch();
        $val = $ratio["RATIO"];
        if($qnt<=$val)
            $qnt = $val;
        else{
            $ost = fmod($qnt, $val);
            if($ost != 0)
            {
                $qnt = $qnt - $ost;
            }
        }

        $this->qnt = $qnt;
    }

    /**
     * @return int
     */
    public function getIblockId()
    {
        return $this->iblockId;
    }

    /**
     * @param int $iblockId
     */
    public function setIblockId($iblockId = 0)
    {
        if ($iblockId > 0) {
            $this->iblockId = $iblockId;
        } else {
            try {
                Loader::includeModule('catalog');
            } catch (LoaderException $e) {
                print_r($e->getTraceAsString());
            }
            if ($this->getId() > 0) {
                $mxCatalog = \CCatalogSKU::GetInfoByProductIBlock(
                    $this->getId()
                );
                if (is_array($mxCatalog)) {
                    $this->iblockId = $mxCatalog['PRODUCT_IBLOCK_ID'];
                } else {
                    $mxOffer = \CCatalogSKU::GetInfoByOfferIBlock(
                        $this->getId()
                    );
                    $this->iblockId = $mxOffer['IBLOCK_ID'];
                }
            }
        }
    }

    /**
     * @return array
     */
    public function getProps()
    {
        return $this->props;
    }

    /**
     * @param array $props
     */
    public function setProps($props)
    {
        $this->props = $props;
    }

    /**
     * @return array
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param array $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }
}

?>