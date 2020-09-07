<?php
namespace intec\template\ajax;

use Bitrix\Main\Loader;
use Bitrix\Sale\Basket;
use Bitrix\Sale\BasketItem;
use Bitrix\Sale\Fuser;
use Bitrix\Currency\CurrencyManager;
use Bitrix\Main\Context;
use intec\Core;
use intec\core\handling\Actions;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;
use CCatalogMeasureRatio;
use CStartShopBasket;
use CStartShopPrice;

class BasketActions extends Actions
{
    /**
     * @var Basket
     */
    protected $basket = null;
    /**
     * @var boolean
     */
    protected $base = true;

    /**
     * @inheritdoc
     */
    public function beforeAction ($action)
    {
        if (parent::beforeAction($action)) {
            if (
                !Loader::includeModule('intec.core') &&
                !Loader::includeModule('iblock')
            ) return false;

            if (
                !Loader::includeModule('catalog') ||
                !Loader::includeModule('sale')
            ) {
                if (!Loader::includeModule('intec.startshop')) {
                    return false;
                } else {
                    $this->base = false;
                }
            }

            return true;
        }

        return false;
    }

    /**
     * Возвращает данные запроса.
     * @return array|mixed
     */
    protected function getData()
    {
        if (!Type::isArray($this->data))
            $this->data = [];

        return $this->data;
    }

    /**
     * Возвращает экземпляр корзины текущего пользователя.
     * @return Basket
     */
    protected function getBasket()
    {
        if ($this->basket === null)
            if ($this->base)
                $this->basket = Basket::loadItemsForFUser(
                    Fuser::getId(),
                    Context::getCurrent()->getSite()
                );

        return $this->basket;
    }

    /**
     * Вовзращает элемент корзины.
     * @param string $module
     * @param integer $id
     * @param Basket|null $basket
     * @return BasketItem|null
     */
    protected function getBasketItem($module, $id, $basket = null)
    {
        if ($basket === null)
            $basket = $this->getBasket();

        if (empty($basket))
            return null;

        /** @var BasketItem $item */
        foreach ($basket as $item)
            if ($item->getField('MODULE') == $module && $item->getProductId() == $id)
                return $item;

        return null;
    }

    /**
     * Добавление товара в корзину.
     * @post int $id Идентификатор элемента инфоблока.
     * @post int $quantity Количество. Необязательно.
     * @post array $properties Свойства, добавляемые в корзину. Необязательны.
     * @post string $currency Код валюты. Необязателен.
     * @post string $delay Добавить в отложенные. (Y/N).
     * @return bool
     */
    public function actionAdd()
    {
        $data = $this->getData();
        $id = ArrayHelper::getValue($data, 'id');
        $id = Type::toInteger($id);
        $price = ArrayHelper::getValue($data, 'price');
        $quantity = ArrayHelper::getValue($data, 'quantity');

        if ($this->base) {
            $quantity = Type::toFloat($quantity);
            $ratio = CCatalogMeasureRatio::getList([], ['PRODUCT_ID' => $id]);
            $ratio = $ratio->Fetch();
            $ratio = !empty($ratio) ? Type::toFloat($ratio['RATIO']) : 1;
            $quantity = $quantity < $ratio ? $ratio : $quantity;
            $properties = ArrayHelper::getValue($data, 'properties');
            $currency = ArrayHelper::getValue($data, 'currency');
            $delay = ArrayHelper::getValue($data, 'delay');
            $delay = $delay == 'Y' ? 'Y' : 'N';

            if (empty($id))
                return false;

            if (empty($currency))
                $currency = CurrencyManager::getBaseCurrency();

            $arElement = \CIBlockElement::GetByID($id)->GetNext();

            if (empty($arElement))
                return false;

            $arProduct = \CCatalogSku::GetProductInfo($id);

            $basket = $this->getBasket();

            if ($item = $this->getBasketItem('catalog', $id)) {
                $item->setFields(['DELAY' => $delay]);
            } else {
                /** @var BasketItem $item */
                $item = $basket->createItem('catalog', $id);
                $item->setFields([
                    'PRODUCT_ID' => $id,
                    'QUANTITY' => $quantity,
                    'CURRENCY' => $currency,
                    'DELAY' => $delay,
                    'LID' => Context::getCurrent()->getSite(),
                    'PRODUCT_PROVIDER_CLASS' => class_exists('\Bitrix\Catalog\Product\CatalogProvider') ?
                        '\Bitrix\Catalog\Product\CatalogProvider' :
                        'CCatalogProductProvider',
                    'CATALOG_XML_ID' => $arElement['IBLOCK_EXTERNAL_ID'],
                    'PRODUCT_XML_ID' => $arElement['EXTERNAL_ID']
                ]);
            }

            $collection = $item->getPropertyCollection();

            if (!empty($arProduct) && Type::isArray($properties)) {
                $properties = \CIBlockPriceTools::GetOfferProperties(
                    $id,
                    $arElement['IBLOCK_ID'],
                    $properties
                );

                if (!empty($properties))
                    $collection->setProperty($properties);
            }

            $required = [];

            if (!empty($arElement['IBLOCK_EXTERNAL_ID']))
                $required[] = [
                    'NAME' => 'Catalog XML_ID',
                    'CODE' => 'CATALOG.XML_ID',
                    'VALUE' => $arElement['IBLOCK_EXTERNAL_ID'],
                    'SORT' => 100
                ];

            if (!empty($arElement['EXTERNAL_ID']))
                $required[] = [
                    'NAME' => 'Product XML_ID',
                    'CODE' => 'PRODUCT.XML_ID',
                    'VALUE' => $arElement['EXTERNAL_ID'],
                    'SORT' => 100
                ];

            if (!empty($required))
                $collection->setProperty($required);

            $basket->save();
        } else {
            $quantity = Type::toFloat($quantity);
            $quantity = $quantity < 0 ? 0 : $quantity;

            if (empty($id))
                return false;

            if (CStartShopBasket::InBasket($id))
                return true;

            if (!empty($price)) {
                $price = CStartShopPrice::GetByID($price)->Fetch();
                $price = !empty($price) ? $price['CODE'] : null;
            } else {
                $price = false;
            }

            if (CStartShopBasket::Add($id, $quantity, $price) === false)
                return false;
        }

        return true;
    }

    /**
     * Изменение количества товара в корзине.
     * @post int $id Идентификатор элемента инфоблока.
     * @post int $quantity Количество. Необязательно.
     * @return bool
     */
    public function actionSetQuantity()
    {
        $data = $this->getData();
        $id = ArrayHelper::getValue($data, 'id');
        $id = Type::toInteger($id);

        if ($this->base) {
            $quantity = ArrayHelper::getValue($data, 'quantity');
            $quantity = Type::toFloat($quantity);
            $ratio = CCatalogMeasureRatio::getList([], ['PRODUCT_ID' => $id]);
            $ratio = $ratio->Fetch();
            $ratio = !empty($ratio) ? Type::toFloat($ratio['RATIO']) : 1;
            $quantity = $quantity < $ratio ? $ratio : $quantity;

            $basket = $this->getBasket();

            if ($item = $this->getBasketItem('catalog', $id)) {
                $item->setFields(['QUANTITY' => $quantity]);
                $basket->save();
            }
        } else {
            $quantity = ArrayHelper::getValue($data, 'quantity');
            $quantity = Type::toFloat($quantity);
            $quantity = $quantity < 0 ? 0 : $quantity;

            if (CStartShopBasket::InBasket($id)) {
                CStartShopBasket::SetQuantity($id, $quantity);
                return true;
            }

            return false;
        }

        return true;
    }

    /**
     * Удаление товара из корзины.
     * @post int $id Идентификатор элемента инфоблока.
     * @return bool
     */
    public function actionRemove()
    {
        $data = $this->getData();
        $id = ArrayHelper::getValue($data, 'id');
        $id = Type::toInteger($id);

        if (empty($id))
            return false;

        if ($this->base) {
            $basket = $this->getBasket();

            if ($item = $this->getBasketItem('catalog', $id)) {
                $item->delete();
                $basket->save();
            }
        } else {
            CStartShopBasket::Delete($id);
        }

        return true;
    }

    /**
     * Очистка корзины.
     * @post string $basket Очищать ли корзину. (Y/N).
     * @post string $delay Очищать ли отложенные. (Y/N).
     * @return bool
     */
    public function actionClear()
    {
        $data = $this->getData();

        if ($this->base) {
            $basket = ArrayHelper::getValue($data, 'basket');
            $basket = $basket == 'Y';
            $delay = ArrayHelper::getValue($data, 'delay');
            $delay = $delay == 'Y';

            if (!$basket && !$delay) {
                $basket = true;
                $delay = true;
            }

            $items = $this->getBasket();

            foreach ($items as $item) {
                if (!$item->isDelay() && $basket)
                    $item->delete();

                if ($item->isDelay() && $delay)
                    $item->delete();
            }

            $items->save();
        } else {
            CStartShopBasket::Clear();
        }

        return true;
    }
}