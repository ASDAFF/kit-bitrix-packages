<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$cp = $this->__component;
if (is_object($cp)) {
    CModule::IncludeModule('iblock');

    if (empty($arResult['ERRORS']['FATAL'])) {

        $hasDiscount = false;
        $hasProps = false;
        $productSum = 0;
        $basketRefs = array();
        $all_quantity = 0;
        $totaprice = 0;

        $noPict = array(
            'SRC' => $this->GetFolder() . '/images/no_photo.png'
        );

        if (is_readable($nPictFile = $_SERVER['DOCUMENT_ROOT'] . $noPict['SRC'])) {
            $noPictSize = getimagesize($nPictFile);
            $noPict['WIDTH'] = $noPictSize[0];
            $noPict['HEIGHT'] = $noPictSize[1];
        }

        foreach ($arResult["BASKET"] as $k => &$prod) {
            $img = CFile::GetFileArray($prod['DETAIL_PICTURE']);
            $prod['DETAIL_IMG'] = CFile::ResizeImageGet(
                $img,
                array("width" => 75, "height" => 75),
                BX_RESIZE_IMAGE_EXACT);
            if (floatval($prod['DISCOUNT_PRICE']))
                $hasDiscount = true;

            // move iblock props (if any) to basket props to have some kind of consistency
            if (isset($prod['IBLOCK_ID'])) {
                $iblock = $prod['IBLOCK_ID'];
                if (isset($prod['PARENT']))
                    $parentIblock = $prod['PARENT']['IBLOCK_ID'];

                foreach ($arParams['CUSTOM_SELECT_PROPS'] as $prop) {
                    $key = $prop . '_VALUE';
                    if (isset($prod[$key])) {
                        // in the different iblocks we can have different properties under the same code
                        if (isset($arResult['PROPERTY_DESCRIPTION'][$iblock][$prop]))
                            $realProp = $arResult['PROPERTY_DESCRIPTION'][$iblock][$prop];
                        elseif (isset($arResult['PROPERTY_DESCRIPTION'][$parentIblock][$prop]))
                            $realProp = $arResult['PROPERTY_DESCRIPTION'][$parentIblock][$prop];

                        if (!empty($realProp))
                            $prod['PROPS'][] = array(
                                'NAME' => $realProp['NAME'],
                                'VALUE' => htmlspecialcharsEx($prod[$key])
                            );
                    }
                }
            }

            // if we have props, show "properties" column
            if (!empty($prod['PROPS']))
                $hasProps = true;

            $productSum += $prod['PRICE'] * $prod['QUANTITY'];

            $basketRefs[$prod['PRODUCT_ID']][] =& $arResult["BASKET"][$k];


            if (strpos($prod['NAME'], 'Занесение на внутренний счет') === 0) {
                $schet = array(
                    'SRC' => $this->GetFolder() . '/images/monetki.png'
                );

                if (is_readable($schetFile = $_SERVER['DOCUMENT_ROOT'] . $schet['SRC'])) {
                    $schetSize = getimagesize($schetFile);
                    $schet['WIDTH'] = $schetSize[0];
                    $schet['HEIGHT'] = $schetSize[1];
                }
                $prod['PICTURE'] = $schet;
            }

            $all_quantity += $prod['QUANTITY'];
            $totaprice += $prod['PRICE'] * $prod['QUANTITY'];


            if (!isset($prod['PICTURE']))
                $prod['PICTURE'] = $noPict;
        }

        $arResult['ALL_QUANTITY'] = $all_quantity;
        $arResult['TOTAL_PRICE'] = $totaprice;

        $arResult['HAS_DISCOUNT'] = $hasDiscount;
        $arResult['HAS_PROPS'] = $hasProps;

        $arResult['PRODUCT_SUM_FORMATTED'] = SaleFormatCurrency($productSum, $arResult['CURRENCY']);

        if ($img = intval($arResult["DELIVERY"]["STORE_LIST"][$arResult['STORE_ID']]['IMAGE_ID'])) {

            $pict = CFile::ResizeImageGet($img, array(
                'width' => 75,
                'height' => 75
            ), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);

            if (strlen($pict['src']))
                $pict = array_change_key_case($pict, CASE_UPPER);

            $arResult["DELIVERY"]["STORE_LIST"][$arResult['STORE_ID']]['IMAGE'] = $pict;
        }

    }
}

foreach ($arResult["ORDER_PROPS"] as $k => $v) {
    $arResult["ORDER_PROPS"][$v['CODE']] = $v;
    unset($arResult["ORDER_PROPS"][$k]);
}

$arResult['DISCOUNTS'] = array();

\Bitrix\Main\Loader::includeModule("sale");
if ($arParams['ID'] > 0) {
    $order = \Bitrix\Sale\Order::load($arParams['ID']);
    if ($order instanceof \Bitrix\Sale\Order) {
        $discountData = $order->getDiscount()->getApplyResult();
        foreach ($discountData['DISCOUNT_LIST'] as $discount) {
            $arResult['DISCOUNTS'][] = $discount['NAME'];
        }

        array_unique($arResult['DISCOUNTS']);
    }
}

$params['LIST_ITEM_ID_NEW'] = array();
$i = 1;
/*foreach ($params['ALL_PRODUCT_COUNT'] as $k => $v) {
    $params['LIST_ITEM_ID_NEW'][] = $k;
    if ($params['COUNT_PRODUCT'] > 0 && $i > $params['COUNT_PRODUCT'] && $i < 4) {
        break;
    }
    $i++;
}*/
?>