<?
class CStartShopCurrency extends CStartShop implements CStartShopInterface
{
    private static $arFieldsEditable = array(
        'CODE',
        'SORT',
        'ACTIVE',
        'BASE',
        'RATE',
        'RATING'
    );

    private static $arFieldsFiltering = array(
        'ID',
        'CODE',
        'SORT',
        'ACTIVE',
        'BASE',
        'RATE',
        'RATING'
    );

    private static $Language;
    private static $Format;

    /**
     * @return CStartShopDBTableModule
     */
    private static function Language() {
        if (!(static::$Language instanceof CStartShopDBTableModule)) {
            static::$Language = new CStartShopDBTableModule(
                'startshop_currency_language',
                'CURRENCY',
                'LID',
                array('NAME')
            );
        }

        return static::$Language;
    }

    /**
     * @return CStartShopDBTableModule
     */
    private static function Format() {
        if (!(static::$Format instanceof CStartShopDBTableModule)) {
            static::$Format = new CStartShopDBTableModule(
                'startshop_currency_format',
                'CURRENCY',
                'LID',
                array(
                    'FORMAT',
                    'DELIMITER_DECIMAL',
                    'DELIMITER_THOUSANDS',
                    'DECIMALS_COUNT',
                    'DECIMALS_DISPLAY_ZERO'
                )
            );
        }

        return static::$Format;
    }

    public static function SetBase($iCurrencyID) {
        $iCurrencyID = intval($iCurrencyID);

        $arCurrencyBase = static::GetBase()->Fetch();
        $arCurrency = static::GetByID($iCurrencyID)->Fetch();

        if ($arCurrency) {
            CStartShopDBQueryBX::Update()
                ->Tables('startshop_currency')
                ->Values(array('BASE' => 'N'))
                ->Execute();

            CStartShopDBQueryBX::Update()
                ->Tables('startshop_currency')
                ->Values(array(
                    'BASE' => 'Y',
                    'RATE' => '1',
                    'RATING' => '1'
                ))
                ->Where(array('ID' => $iCurrencyID))
                ->Execute();

            static::ResetCacheByUnique($arCurrencyBase['ID']);
            static::ResetCacheByUnique($arCurrency['ID']);
            static::ResetCacheBase();
        }
    }

    public static function Add($arFields)
    {
        if (empty($arFields['CODE']))
            return false;

        $arFormats = $arFields['FORMAT'];
        $arLanguages = $arFields['LANG'];

        $arFields = CStartShopUtil::ArrayFilter($arFields, function ($sKey) {
            return in_array($sKey, static::$arFieldsEditable);
        }, STARTSHOP_UTIL_ARRAY_FILTER_USE_KEY);

        if (!is_array($arFormats))
            $arFormats = array();

        if (!is_array($arLanguages))
            $arLanguages = array();

        $bExists = (bool)static::GetList(array(), array(
            'CODE' => $arFields['CODE']
        ))->Fetch();

        if ($bExists)
            return false;

        $iInsertedID = CStartShopDBQueryBX::Insert()
            ->Into('startshop_currency')
            ->Values($arFields)
            ->Execute();

        if ($iInsertedID) {
            if ($arFields['BASE'] == 'Y')
                static::SetBase($iInsertedID);

            static::Language()->Set($iInsertedID, $arLanguages);
            static::Format()->Set($iInsertedID, $arFormats);

            CStartShopToolsIBlock::UpdatePropertiesAll();
            return $iInsertedID;
        }

        return false;
    }

    public static function Update($iCurrencyID, $arFields)
    {
        $iCurrencyID = intval($iCurrencyID);
        $arFormats = $arFields['FORMAT'];
        $arLanguages = $arFields['LANG'];
        $bExists = false;

        $arCurrency = static::GetByID($iCurrencyID)->Fetch();

        if (empty($arCurrency))
            return false;

        if (!is_array($arFormats))
            $arFormats = false;

        if (!is_array($arLanguages))
            $arLanguages = false;

        if (isset($arFields['CODE']) && empty($arFields['CODE']))
            unset($arFields['CODE']);

        if (isset($arFields['CODE']))
            if ($arFields['CODE'] != $arCurrency['CODE'])
                $bExists = (bool)static::GetList(array(), array(
                    'CODE' => $arFields['CODE']
                ))->Fetch();

        if ($bExists)
            return false;

        $arFields = CStartShopUtil::ArrayFilter($arFields, function ($sKey) {
            return in_array($sKey, static::$arFieldsEditable);
        }, STARTSHOP_UTIL_ARRAY_FILTER_USE_KEY);

        if (!empty($arFields)) {
            if ($arFields['BASE'] == 'Y') {
                static::SetBase($iCurrencyID);
                unset($arFields['BASE']);
                unset($arFields['RATE']);
                unset($arFields['RATING']);
            }

            if ($arFields['BASE'] != 'N')
                if ($arCurrency['BASE'] == 'Y') {
                    unset($arFields['RATE']);
                    unset($arFields['RATING']);
                }

            CStartShopDBQueryBX::Update()
                ->Tables('startshop_currency')
                ->Values($arFields)
                ->Where(array('ID' => $iCurrencyID))
                ->Execute();
        }

        if (is_array($arLanguages))
            static::Language()->Set($iCurrencyID, $arLanguages);

        if (is_array($arFormats))
            static::Format()->Set($iCurrencyID, $arFormats);

        static::ResetCacheByUnique($iCurrencyID);
        CStartShopToolsIBlock::UpdatePropertiesAll();

        return true;
    }

    public static function Delete($iCurrencyID)
    {
        $iCurrencyID = intval($iCurrencyID);

        CStartShopDBQueryBX::Delete()
            ->From('startshop_currency')
            ->Where(array('ID' => $iCurrencyID))
            ->Execute();

        static::Language()->Set($iCurrencyID);
        static::Format()->Set($iCurrencyID);

        static::ResetCacheByUnique($iCurrencyID);
        CStartShopToolsIBlock::UpdatePropertiesAll();

        return true;
    }

    public static function DeleteAll()
    {
        CStartShopDBQueryBX::Delete()
            ->From('startshop_currency')
            ->Execute();

        static::Language()->Set();
        static::Format()->Set();

        static::ResetCache();
        CStartShopToolsIBlock::UpdatePropertiesAll();

        return true;
    }

    public static function GetList($arSort = array(), $arFilter = array())
    {
        $arCurrencies = array();

        $arSort = CStartShopUtil::ArrayFilter($arSort, function ($sKey) {
            return in_array($sKey, static::$arFieldsFiltering);
        }, STARTSHOP_UTIL_ARRAY_FILTER_USE_KEY);

        $arFilter = CStartShopUtil::ArrayFilter($arFilter, function ($sKey) {
            return in_array(
                preg_replace('/^(>=|<=|=|!|>|<)/', '', $sKey),
                static::$arFieldsFiltering
            );
        }, STARTSHOP_UTIL_ARRAY_FILTER_USE_KEY);

        $dbResult = CStartShopDBQueryBX::Select()
            ->From('startshop_currency')
            ->Where($arFilter)
            ->OrderBy($arSort)
            ->Execute();

        while ($arCurrency = $dbResult->Fetch()) {
            $arCurrency['LANG'] = array();
            $arCurrency['FORMAT'] = array();
            $arCurrencies[$arCurrency['ID']] = $arCurrency;
        }

        if (!empty($arCurrencies)) {
            $arCurrenciesID = array_keys($arCurrencies);

            $dbLanguages = static::Language()->Get(array('LID' => 'ASC'), $arCurrenciesID);
            $dbFormats = static::Format()->Get(array('LID' => 'ASC'), $arCurrenciesID);

            while ($arLanguage = $dbLanguages->Fetch())
                $arCurrencies[$arLanguage['CURRENCY']]['LANG'][$arLanguage['LID']] = array(
                    "NAME" => $arLanguage['NAME']
                );

            while ($arFormat = $dbFormats->Fetch())
                $arCurrencies[$arFormat['CURRENCY']]['FORMAT'][$arFormat['LID']] = array(
                    "FORMAT" => $arFormat["FORMAT"],
                    'DELIMITER_DECIMAL' => $arFormat["DELIMITER_DECIMAL"],
                    'DELIMITER_THOUSANDS' => $arFormat["DELIMITER_THOUSANDS"],
                    'DECIMALS_COUNT' => $arFormat["DECIMALS_COUNT"],
                    'DECIMALS_DISPLAY_ZERO' => $arFormat["DECIMALS_DISPLAY_ZERO"]
                );
        }

        return CStartShopUtil::ArrayToDBResult($arCurrencies);
    }

    public static function GetBase()
    {
        if (!CStartShopCache::IsExists('CStartShopCurrency.Common', 'BASE')) {
            $dbResult = static::GetList(array(), array('BASE' => 'Y'));
            $dbResult = CStartShopCache::CreateFromResult('CStartShopCurrency.Common', 'BASE', $dbResult);
        } else {
            $dbResult = CStartShopCache::GetAsResult('CStartShopCurrency.Common', 'BASE');
        }

        return $dbResult;
    }

    public static function GetByID($iCurrencyID)
    {
        if (!CStartShopCache::IsExists('CStartShopCurrency.Items', $iCurrencyID)) {
            $dbResult = self::GetList(array(), array("ID" => $iCurrencyID));
            $dbResult = CStartShopCache::CreateFromResult('CStartShopCurrency.Items', array('ID', 'CODE'), $dbResult);
        } else {
            $dbResult = CStartShopCache::GetAsResult('CStartShopCurrency.Items', $iCurrencyID);
        }

        return $dbResult;
    }

    public static function GetByCode($sCurrencyCode)
    {
        if (!CStartShopCache::IsExists('CStartShopCurrency.Items', $sCurrencyCode)) {
            $dbResult = self::GetList(array(), array("CODE" => $sCurrencyCode));
            $dbResult = CStartShopCache::CreateFromResult('CStartShopCurrency.Items', array('ID', 'CODE'), $dbResult);
        } else {
            $dbResult = CStartShopCache::GetAsResult('CStartShopCurrency.Items', $sCurrencyCode);
        }

        return $dbResult;
    }

    public static function ResetCacheBase() {
        CStartShopCache::Clear('CStartShopCurrency.Common', 'BASE');
    }

    public static function ResetCacheByUnique($cUnique) {
        if (CStartShopCache::IsExists('CStartShopCurrency.Items', $cUnique)) {
            $arUnique = CStartShopCache::Get('CStartShopCurrency.Items', $cUnique);
            CStartShopCache::Clear('CStartShopCurrency.Items', $arUnique['ID']);
            CStartShopCache::Clear('CStartShopCurrency.Items', $arUnique['CODE']);
        }
    }

    public static function ResetCache() {
        CStartShopCache::ClearPath('CStartShopCurrency.Items');
        CStartShopCache::ClearPath('CStartShopCurrency.Common');
    }

    public static function Convert($fPrice, $sFromCurrency = null, $sToCurrency = null) {
        if ($sFromCurrency == $sToCurrency) return $fPrice;

        if (!empty($sFromCurrency)) {
            $arFromCurrency = static::GetByCode($sFromCurrency)->Fetch();
        } else {
            $arFromCurrency = static::GetBase()->Fetch();
        }

        if (!empty($sToCurrency)) {
            $arToCurrency = static::GetByCode($sToCurrency)->Fetch();
        } else {
            $arToCurrency = static::GetBase()->Fetch();
        }

        if (empty($arToCurrency)) {
            $arToCurrency = array();
            $arToCurrency['RATE'] = 1;
            $arToCurrency['RATING'] = 1;
        }

        if (!empty($arFromCurrency) && !empty($arToCurrency)) {
            $fDivider = intval($arToCurrency['RATING']);
            $fDividend = intval($arFromCurrency['RATING']);

            if ($fDivider == 0 || $fDividend == 0)
                return 0;

            $fDivider = floatval($arToCurrency['RATE']) / $fDivider;
            $fDividend = floatval($arFromCurrency['RATE']) / $fDividend;

            if ($fDivider == 0)
                return 0;

            return (($fPrice * $fDividend) / $fDivider);
        }

        return $fPrice;
    }

    public static function FormatAsString($fPrice, $sCurrencyCode = null, $sLanguageID = LANGUAGE_ID) {
        $arPrice = static::FormatAsArray($fPrice, $sCurrencyCode, $sLanguageID);
        return $arPrice['PRINT_VALUE'];
    }

    public static function FormatAsArray($fPrice, $sCurrencyCode = null, $sLanguageID = LANGUAGE_ID) {
        $arPrice = array();

        $arCurrency = null;

        if (!empty($sCurrencyCode)) {
            $arCurrency = static::GetByCode($sCurrencyCode)->Fetch();
        } else {
            $arCurrency = static::GetBase()->Fetch();
        }

        $arFormat = $arCurrency['FORMAT'][$sLanguageID];
        $arPrice['VALUE'] = floatval($fPrice);

        if (!empty($arFormat)) {
            $bUseDecimals = fmod($arPrice['VALUE'], 1) != 0 || $arFormat['DECIMALS_DISPLAY_ZERO'] == 'Y';

            $arPrice['CURRENCY'] = $arCurrency['CODE'];
            $arPrice['PRINT_VALUE'] = str_replace(
                '#',
                number_format(
                    $arPrice['VALUE'],
                    $bUseDecimals ? intval($arFormat['DECIMALS_COUNT']) : 0,
                    $arFormat['DELIMITER_DECIMAL'],
                    $arFormat['DELIMITER_THOUSANDS']
                ),
                $arFormat['FORMAT']
            );
        } else {
            $arPrice['CURRENCY'] = null;
            $arPrice['PRINT_VALUE'] = $arPrice['VALUE'];
        }

        return $arPrice;
    }

    public static function ConvertAndFormatAsArray($fPrice, $sFromCurrency = null, $sToCurrency = null, $sLanguageID = LANGUAGE_ID)
    {
        return static::FormatAsArray(static::Convert($fPrice, $sFromCurrency, $sToCurrency), $sToCurrency, $sLanguageID);
    }

    public static function ConvertAndFormatAsString($fPrice, $sFromCurrency = null, $sToCurrency = null, $sLanguageID = LANGUAGE_ID)
    {
        $arPrice = static::ConvertAndFormatAsArray($fPrice, $sFromCurrency, $sToCurrency, $sLanguageID);
        return $arPrice['PRINT_VALUE'];
    }
}
?>