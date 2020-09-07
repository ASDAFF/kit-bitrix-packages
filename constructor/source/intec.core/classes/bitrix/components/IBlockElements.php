<?
namespace intec\core\bitrix\components;

use CIBlock;
use CIBlockElement;
use CFile;
use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

/**
 * Class IBlockElements
 * @package intec\core\bitrix\components
 * @author apocalypsisdimon@gmail.com
 */
class IBlockElements extends IBlockSections
{
    /**
     * @var integer|integer[]|null
     */
    protected $_elementsId;
    /**
     * @var string|string[]|null
     */
    protected $_elementsCode;

    /**
     * Шаблон URL элемента
     * @var string|null
     */
    protected $_urlElement;

    /**
     * Получает идентификаторы элементов.
     * @return integer|integer[]|false|null
     */
    protected function getElementsId()
    {
        return $this->_elementsId;
    }

    /**
     * Устанавливает идентификаторы элементов.
     * @param integer|integer[]|false|null $value
     */
    protected function setElementsId($value)
    {
        if ($value !== null)
            if (Type::isNumeric($value)) {
                $value = Type::toInteger($value);
                $value = $value > 0 ? $value : null;
            } else if (Type::isArray($value)) {
                $value = array_filter($value);

                if (empty($value))
                    $value = null;
            } else if ($value !== false) {
                $value = null;
            }

        $this->_elementsId = $value;

        return $this;
    }

    /**
     * Получает коды элементов.
     * @return string|string[]|false|null
     */
    protected function getElementsCode()
    {
        return $this->_elementsCode;
    }

    /**
     * Устанавливает коды элементов.
     * @param string|string[]|false|null $value
     */
    protected function setElementsCode($value)
    {
        if ($value !== null)
            if (Type::isArray($value)) {
                $value = array_filter($value);

                if (empty($value))
                    $value = null;
            } else if ($value !== false) {
                $value = Type::toString($value);
            }

        $this->_elementsCode = $value;

        return $this;
    }

    /**
     * Возвращает шаблон URL элемента.
     * @return string
     */
    protected function getUrlElement()
    {
        return $this->_urlElement;
    }

    /**
     * @inheritdoc
     * @param null $element
     */
    protected function setUrlTemplates($root = null, $section = null, $element = null)
    {
        if ($element !== null)
            $element = Type::toString($element);

        $this->_urlElement = $element;

        return parent::setUrlTemplates($root, $section);
    }

    /**
     * Возвращает результат выборки.
     * @param array|null $arSort
     * @param array|null $arFilter
     * @param integer|null $iCount
     * @param integer|null $iPage
     * @param array|false $arGroup
     * @param array|false $arSelect
     * @return array
     */
    protected function getElements($arSort = null, $arFilter = null, $iCount = null, $iPage = null, $arGroup = false, $arSelect = [])
    {
        global $APPLICATION;

        $arResult = [];

        if (!Loader::includeModule('iblock'))
            return $arResult;

        $bExecute = $this->getIsGlobal();

        $iId = $this->getIBlockId();
        $sType = $this->getIBlockType();

        $arFilter = Type::isArray($arFilter) ? $arFilter : [];
        $arSort = Type::isArray($arSort) ? $arSort : [];
        $iCount = Type::toInteger($iCount);
        $iCount = !empty($iCount) ? $iCount : null;
        $iPage = Type::toInteger($iPage);
        $iPage = !empty($iPage) && $iCount !== null ? $iPage : null;
        $arGroup = Type::isArray($arGroup) ? $arGroup : false;
        $arSelect = Type::isArray($arSelect) ? $arSelect : [];

        $arSectionsId = $this->getSectionsId();
        $arSectionsCode = $this->getSectionsCode();
        $arElementsId = $this->getElementsId();
        $arElementsCode = $this->getElementsCode();
        $arNavigation = [];

        if ($iCount !== null)
            $arNavigation['nPageSize'] = $iCount;

        if ($iPage !== null)
            $arNavigation['iNumPage'] = $iPage;

        if ($sType !== null)
            $arFilter['IBLOCK_TYPE'] = $sType;

        if ($iId !== null) {
            $arFilter['IBLOCK_ID'] = $iId;
            $bExecute = true;
        }

        if ($arSectionsId !== null)
            $arFilter['SECTION_ID'] = $arSectionsId;

        if ($arSectionsCode !== null)
            $arFilter['SECTION_CODE'] = $arSectionsCode;

        if ($arElementsId !== null) {
            $arFilter['ID'] = $arElementsId;
            $bExecute = true;
        }

        if ($arElementsCode !== null) {
            $arFilter['CODE'] = $arElementsCode;
            $bExecute = true;
        }

        if (!$bExecute)
            return $arResult;

        if ($iId !== null && $APPLICATION->GetShowIncludeAreas()) {
            $arButtons = CIBlock::GetPanelButtons($iId, 0,
                Type::isNumeric($arSectionsId) ? $arSectionsId : 0,
                array(
                    'SECTION_BUTTONS' => false
                )
            );

            $this->AddIncludeAreaIcons(
                CIBlock::GetComponentMenu(
                    $APPLICATION->GetPublicShowMode(),
                    $arButtons
                )
            );
        }

        $arImages = [];
        $rsResult = CIBlockElement::GetList($arSort, $arFilter, $arGroup, $arNavigation, $arSelect);
        $rsResult->SetUrlTemplates($this->getUrlElement(), $this->getUrlSection(), $this->getUrlRoot());

        while ($rsElement = $rsResult->GetNextElement()) {
            $arElement = $rsElement->GetFields();
            $arElement['PROPERTIES'] = $rsElement->GetProperties();

            if (!empty($arElement['PREVIEW_PICTURE']))
                $arImages[] = $arElement['PREVIEW_PICTURE'];

            if (!empty($arElement['DETAIL_PICTURE']))
                $arImages[] = $arElement['DETAIL_PICTURE'];

            $buttons = CIBlock::GetPanelButtons(
                $arElement['IBLOCK_ID'],
                $arElement['ID'],
                $arElement['SECTION_ID'],
                array(
                    'SECTION_BUTTONS' => false,
                    'SESSID' => false,
                    'CATALOG' => true
                )
            );

            $arElement['EDIT_LINK'] = $buttons['edit']['edit_element']['ACTION_URL'];
            $arElement['DELETE_LINK'] = $buttons['edit']['delete_element']['ACTION_URL'];
            $arResult[$arElement['ID']] = $arElement;
        }

        if (!empty($arImages)) {
            $rsImages = CFile::GetList(array(), array(
                '@ID' => implode(',', $arImages)
            ));
            $arImages = array();

            while ($arImage = $rsImages->GetNext()) {
                $arImage['SRC'] = CFile::GetFileSRC($arImage);
                $arImages[$arImage['ID']] = $arImage;
            }

            foreach ($arResult as &$arElement) {
                if (!empty($arElement['PREVIEW_PICTURE']))
                    $arElement['PREVIEW_PICTURE'] = ArrayHelper::getValue($arImages, $arElement['PREVIEW_PICTURE']);

                if (!empty($arElement['DETAIL_PICTURE']))
                    $arElement['DETAIL_PICTURE'] = ArrayHelper::getValue($arImages, $arElement['DETAIL_PICTURE']);
            }
        }

        return $arResult;
    }
}